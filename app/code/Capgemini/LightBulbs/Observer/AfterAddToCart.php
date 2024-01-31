<?php
/**
 * Lyonscg_LightBulbs
 *
 * @category  Lyons
 * @package   Lyonscg_LightBulbs
 * @author    Logan Montgomery<logan.montgomery@capgemini.com>
 * @author    Tanya Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */

namespace Capgemini\LightBulbs\Observer;

use Capgemini\LightBulbs\Plugin\Checkout\Cart\AddPlugin;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class AfterAddToCart
 * @package Capgemini\LightBulbs\Observer
 */
class AfterAddToCart implements ObserverInterface
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * If true, then we are currently adding lightbulbs to cart from the checkboxes on PDP and
     * should ignore subsequent calls to this observer until it is set to false again.
     * @var bool
     */
    protected $addingLightbulbs = false;

    /**
     * AfterAddToCart constructor.
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param RequestInterface $request
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        RequestInterface $request,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->request = $request;
        $this->productRepository = $productRepository;
        $this->messageManager = $messageManager;
    }

    /**
     * clear the last added products from the session
     * @param EventObserver $observer
     */
    public function execute(EventObserver $observer)
    {
        if ($this->addingLightbulbs) {
            // prevent infinite loops
            return;
        }
        $quoteItem = $observer->getEvent()->getData('quote_item');
        // check if we are adding lightbulbs
        $lightbulbs = $this->request->getPost('lightbulb', []);
        if (!empty($lightbulbs)) {
            $this->addingLightbulbs = true;
            $this->_addLightbulbs($quoteItem, $lightbulbs, $quoteItem);
            $this->addingLightbulbs = false;
        }
    }

    /**
     * @param \Magento\Quote\Model\Quote\Item $item
     * @param array $lightbulbs
     * @param \Magento\Quote\Model\Quote\Item $quoteItem
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _addLightbulbs(\Magento\Quote\Model\Quote\Item $item, array $lightbulbs, $quoteItem)
    {
        if (!$item || !($item instanceof \Magento\Quote\Model\Quote\Item)) {
            $this->messageManager->addErrorMessage(
                __('There was a problem adding the extra products to cart.')
            );
            return;
        }
        $data = [];
        $offerLightbulb = true;
        foreach ($lightbulbs as $lightbulb) {
            if (empty($lightbulb['sku'])) {
                $offerLightbulb = false;

                continue;
            }
            if (!empty($lightbulb['id'])) {
                $id = $lightbulb['id'];
                try {
                    $product = $this->productRepository->getById($id);
                    $qty = 1;
                    $qty = !empty($qty = $lightbulb['qty']) ? $qty : 1;
                    $requestedQty = (!empty($requestedQty = $this->request->getParam('qty'))) ? $requestedQty : 1;
                    $qty = $requestedQty * $qty;
                    $data[] = [
                        'product' => $product,
                        'params' => new \Magento\Framework\DataObject(['qty' => $qty])
                    ];
                    $offerLightbulb = false;
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage(
                        __('There was a problem adding the extra products to cart.')
                    );
                    return;
                }
            }
            if ($offerLightbulb) {
                if ($quoteItem && $quoteItem instanceof \Magento\Quote\Model\Quote\Item) {
                    $this->checkoutSession->setData(AddPlugin::LAST_ADDED, $quoteItem);
                }
            }
        }

        if (empty($data)) {
            return;
        }

        // add products to cart
        $quote = $this->checkoutSession->getQuote();
        foreach ($data as $addRequest) {
            try {
                $quote->addProduct($addRequest['product'], $addRequest['params']);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('We were unable to add %1 to your cart.', $addRequest['product']->getName())
                );
            }
        }
    }
}
