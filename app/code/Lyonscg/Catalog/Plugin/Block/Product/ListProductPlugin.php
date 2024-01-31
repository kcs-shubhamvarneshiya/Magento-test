<?php

namespace Lyonscg\Catalog\Plugin\Block\Product;

use Capgemini\BloomreachThematic\Model\TechnicalProduct;
use Capgemini\CompanyType\Model\Config;
use Lyonscg\Catalog\Helper\Data;

class ListProductPlugin
{
    /**
     * @var \Lyonscg\Catalog\Helper\Data
     */
    protected $helper;
    /**
     * @var bool
     */
    private $isTradeCustomer = false;

    /**
     * ListProductPlugin constructor.
     * @param Data $helper
     * @param Config $companyTypeConfig
     */
    public function __construct(
        \Lyonscg\Catalog\Helper\Data $helper,
        \Capgemini\CompanyType\Model\Config $companyTypeConfig
    ) {
        $this->helper = $helper;
        $this->isTradeCustomer = $companyTypeConfig->getCustomerCompanyType() === \Capgemini\CompanyType\Model\Config::TRADE;
    }

    /**
     * Switch product for correct child product
     *
     * @param \Magento\Catalog\Block\Product\ListProduct $subject
     * @param \Magento\Catalog\Model\Product $product
     * @param string $imageId
     * @param array $attributes
     * @return array
     */
    public function beforeGetImage(\Magento\Catalog\Block\Product\ListProduct $subject, $product, $imageId, $attributes = [])
    {
        $product = $product->getData(TechnicalProduct::IS_THEMATIC_PRODUCT_DATA_KEY)
            ? $product
            : $this->helper->getChildProduct($product);
        return [$product, $imageId, $attributes];
    }

    public function beforeGetProductPrice(\Magento\Catalog\Block\Product\ListProduct $subject, $product)
    {
        $child = $this->helper->getChildProduct($product);
        try {
            if (!$this->helper->hasSalePrice($product)) {

                return [$child];
            }
        } catch (\Exception $exception) {

            return [$child];
        }

        if ($product->isComposite() && !$this->isTradeCustomer) {
            $child->setData(
                'final_price_model',
                $product->getPriceInfo()->getPrice('final_price')
            );
        }

        return [$child];
    }
}
