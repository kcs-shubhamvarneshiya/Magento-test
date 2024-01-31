<?php
namespace Capgemini\CustomHeight\Observer;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Api\Data\CartItemExtensionFactory;
use Capgemini\CustomHeight\Helper\PriceHeight;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Class AfterAddToCart
 * @author Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2021 Capgemini, Inc. (www.capgemini.com)
 */
class AfterAddToCart implements ObserverInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var CartItemExtensionFactory
     */
    protected $itemExtensionFactory;

    protected $priceHeightHelper;

    protected $cutDownCost;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;
    /**
     * AfterAddToCart constructor.
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param RequestInterface $request
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        RequestInterface $request,
        CartItemExtensionFactory $itemExtensionFactory,
        PriceHeight $priceHeightHelper,
        ScopeConfigInterface $scopeConfig,
        ProductRepositoryInterface $productRepository
    ) {
        $this->request = $request;
        $this->itemExtensionFactory = $itemExtensionFactory;
        $this->priceHeightHelper = $priceHeightHelper;
        $this->scopeConfig = $scopeConfig;
        $this->cutDownCost = $this->scopeConfig->getValue(PriceHeight::CUT_DOWN_COST,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE);
        $this->productRepository = $productRepository;
    }

    /**
     * clear the last added products from the session
     * @param EventObserver $observer
     */
    public function execute(EventObserver $observer)
    {
        $quoteItem = $observer->getEvent()->getData('quote_item');

        $customHeightPrice = $this->request->getPost('custom_height_price');
        $customHeightValue = (int)$this->request->getPost('custom_height_value');
        if ($customHeightPrice && (int)$customHeightPrice !== 0){
            $product = $quoteItem->getProduct();
            $finalPrice = $product->getFinalPrice($quoteItem->getQty());
            $newPrice = $finalPrice + $customHeightPrice;
            $quoteItem->setCustomPrice($newPrice);
            $quoteItem->setOriginalCustomPrice($newPrice);
            $quoteItem->setCustomHeightPrice($customHeightPrice);
            $quoteItem->setCustomHeightValue($customHeightValue);
            $simpleProduct = $this->productRepository->get($quoteItem->getSku());
            $rodQty = $simpleProduct->getRodqty();
            $oaHeight = (float)$simpleProduct->getOaheight();
            if ($customHeightValue < $oaHeight) {
                $customHeightCost = $this->cutDownCost;
            } else {
                $customHeightCost = $this->priceHeightHelper->getCostByHeight($oaHeight - $customHeightValue);
            }
            if ($customHeightCost) {
                $quoteItem->setCustomHeightCost($customHeightCost * $rodQty);
            }
            $quoteItem->getProduct()->setIsSuperMode(true);
        }

    }
}
