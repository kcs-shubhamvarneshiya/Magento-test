<?php


namespace Lyonscg\Catalog\Observer;

use Magento\Catalog\Model\Product;
use Magento\Framework\Event\ObserverInterface;

class CatalogProductViewConfig implements ObserverInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Lyonscg\Catalog\Helper\Data
     */
    protected $helper;

    /**
     * CatalogProductViewConfig constructor.
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Lyonscg\Catalog\Helper\Data $helper
    ) {
        $this->registry = $registry;
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $this->getProduct();
        if (!$product || $product->getTypeId() !== \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            return;
        }
        $childProduct = $this->_getChildProduct($product);
        if (!$childProduct) {
            // no child has the basecodedisplayimage attribute set, so don't change anything
            return;
        }
        $responseObject = $observer->getEvent()->getResponseObject();
        $this->_updatePrices($product, $childProduct, $responseObject);
    }

    /**
     * @param Product $product
     * @return Product|null
     */
    protected function _getChildProduct(Product $product)
    {
        return $this->helper->getChildProduct($product);
    }

    /**
     * @param Product $parentProduct
     * @param Product $childProduct
     * @param $responseObject
     */
    protected function _updatePrices(Product $parentProduct, Product $childProduct, $responseObject)
    {
        $parentPriceInfo = $parentProduct->getPriceInfo();
        $childPriceInfo = $childProduct->getPriceInfo();
        $additionalOptions = [];
        if (is_array($responseObject->getAdditionalOptions())) {
            $additionalOptions = $responseObject->getAdditionalOptions();
        }

        $additionalOptions['prices'] = [
            'oldPrice'   => [
                'amount'      => $childPriceInfo->getPrice('regular_price')->getAmount()->getValue() * 1,
                'adjustments' => []
            ],
            'basePrice'  => [
                'amount'      => $childPriceInfo->getPrice('final_price')->getAmount()->getBaseAmount() * 1,
                'adjustments' => []
            ],
            'finalPrice' => [
                'amount'      => $parentPriceInfo->getPrice('final_price')->getAmount()->getValue() * 1,
                'adjustments' => []
            ]
        ];
        $responseObject->setAdditionalOptions($additionalOptions);
    }

    /**
     * @return Product|null
     */
    protected function getProduct()
    {
        return $this->registry->registry('product');
    }
}
