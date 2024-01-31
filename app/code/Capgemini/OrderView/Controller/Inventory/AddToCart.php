<?php

namespace Capgemini\OrderView\Controller\Inventory;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Quote\Api\CartRepositoryInterface;

class AddToCart extends \Magento\Framework\App\Action\Action
{
    protected $resultJsonFactory;
    protected $checkoutSession;
    protected $productRepository;
    protected $cartRepository;
    protected $redirect;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        CheckoutSession $checkoutSession,
        ProductRepositoryInterface $productRepository,
        CartRepositoryInterface $cartRepository,
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->cartRepository = $cartRepository;
        $this->redirect = $redirectFactory;
    }

    public function execute()
    {
        $postData = $this->getRequest()->getPostValue();
        $resultRedirect = $this->redirect->create();

        try {


            $quote = $this->checkoutSession->getQuote();

            foreach ($postData['qty'] as $key => $value) {
                if ($value <= 0) {
                    continue;
                }
                if ($value > 10000) {
                    $this->messageManager->addErrorMessage(__('The maximum you may purchase is 10,000.', $key));
                    return $resultRedirect->setRefererUrl();
                }

                try {
                    $product = $this->productRepository->get($key);

                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage(__('Product not found for SKU: %1', $key));
                    return $resultRedirect->setRefererUrl();
                }

                $quoteItem = $quote->getItemByProduct($product);

                if ($quoteItem) {
                    $quoteItem->setQty($quoteItem->getQty() + $value);
                } else {
                    $quote->addProduct($product, $value);
                }
            }

            $this->cartRepository->save($quote);

            $this->messageManager->addSuccessMessage(__('Products added to cart successfully'));

            return $resultRedirect->setRefererUrl();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred: %1', $e->getMessage()));
            return $resultRedirect->setRefererUrl();
        }
    }
}