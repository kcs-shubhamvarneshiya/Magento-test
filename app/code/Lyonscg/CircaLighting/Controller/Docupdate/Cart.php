<?php

namespace Lyonscg\CircaLighting\Controller\Docupdate;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Checkout\Model\Cart\RequestQuantityProcessor;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote\Item;

class Cart extends Action
{
    protected $checkoutSession;

    protected $formKeyValidator;

    protected $quantityProcessor;

    protected $json;

    protected $cartRepository;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        RequestQuantityProcessor $requestQuantityProcessor,
        Json $json,
        CartRepositoryInterface $cartRepository
    ) {
        parent::__construct($context);
        $this->checkoutSession = $checkoutSession;
        $this->formKeyValidator = $formKeyValidator;
        $this->quantityProcessor = $requestQuantityProcessor;
        $this->json = $json;
        $this->cartRepository = $cartRepository;
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->validateFormKey();

            $cartData = $this->getRequest()->getParam('cart');

            $this->validateCartData($cartData);

            $cartData = $this->quantityProcessor->process($cartData);
            $quote = $this->checkoutSession->getQuote();

            $changed = false;
            foreach ($cartData as $itemId => $itemInfo) {
                $item = $quote->getItemById($itemId);
                $qty = isset($itemInfo['qty']) ? (double) $itemInfo['qty'] : 0;
                $sidemark = isset($itemInfo['sidemark']) ? $itemInfo['sidemark'] : '';
                $comment = isset($itemInfo['comments_line_item']) ? $itemInfo['comments_line_item'] : '';
                if ($item) {
                    if ($this->updateItem($item, $qty, $sidemark, $comment)) {
                        $changed = true;
                    }
                }
            }

            if ($changed) {
                $this->saveQuote($quote);
            }

            $this->jsonResponse();
        } catch (LocalizedException $e) {
            $this->jsonResponse($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
            $this->jsonResponse('Something went wrong while saving the page. Please refresh the page and try again.');
        }
    }

    /**
     * Validates the Request HTTP method
     *
     * @return void
     * @throws NotFoundException
     */
    private function validateRequest()
    {
        if ($this->getRequest()->isPost() === false) {
            throw new NotFoundException(__('Page Not Found'));
        }
    }

    /**
     * Validates form key
     *
     * @return void
     * @throws LocalizedException
     */
    private function validateFormKey()
    {
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            throw new LocalizedException(
                __('Something went wrong while saving the page. Please refresh the page and try again.')
            );
        }
    }

    /**
     * Validates cart data
     *
     * @param array|null $cartData
     * @return void
     * @throws LocalizedException
     */
    private function validateCartData($cartData = null)
    {
        if (!is_array($cartData)) {
            throw new LocalizedException(
                __('Something went wrong while saving the page. Please refresh the page and try again.')
            );
        }
    }

    private function jsonResponse(string $error = '')
    {
        $this->getResponse()->representJson(
            $this->json->serialize($this->getResponseData($error))
        );
    }

    /**
     * Returns response data.
     *
     * @param string $error
     * @return array
     */
    private function getResponseData(string $error = ''): array
    {
        $response = ['success' => true];

        if (!empty($error)) {
            $response = [
                'success' => false,
                'error_message' => $error,
            ];
        }

        return $response;
    }

    /**
     * Updates quote item, returns true if item is updated
     *
     * @param Item $item
     * @param float $qty
     *
     * @return boolean
     * @throws LocalizedException
     */
    private function updateItem(Item $item, float $qty, string $sidemark, string $comment)
    {
        $changed = false;
        if ($qty > 0 && $item->getQty() != $qty) {
            $item->clearMessage();
            $item->setQty($qty);

            if ($item->getHasError()) {
                throw new LocalizedException(__($item->getMessage()));
            }
            $changed = true;
        }
        if ($item->getSidemark() != $sidemark) {
            $item->setSidemark($sidemark);
            $changed = true;
        }
        if ($item->getCommentsLineItem() != $comment) {
            $item->setCommentsLineItem($comment);
            $changed = true;
        }
        $extAttrs = $item->getExtensionAttributes();
        if ($extAttrs) {
            $extAttrs->setSidemark($sidemark);
            $extAttrs->setCommentsLineItem($comment);
            $item->setExtensionAttributes($extAttrs);
        }
        return $changed;
    }

    private function saveQuote(CartInterface $quote)
    {
        $this->cartRepository->save($quote);
    }
}
