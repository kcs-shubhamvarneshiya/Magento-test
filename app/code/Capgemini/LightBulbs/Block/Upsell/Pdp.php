<?php

namespace Capgemini\LightBulbs\Block\Upsell;

use Magento\Framework\Url\Helper\Data as UrlHelper;

class Pdp extends \Capgemini\LightBulbs\Block\Upsell
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * Pdp constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Serialize\Serializer\Json $json
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param UrlHelper $urlHelper
     * @param \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Capgemini\DataLayer\Helper\Data $dataLayerHelper
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        UrlHelper $urlHelper,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Capgemini\DataLayer\Helper\Data $dataLayerHelper,
        \Magento\Framework\Registry $registry,
        \Capgemini\LightBulbs\Helper\Data $bulbHelper,
        array $data = []
    ) {
        parent::__construct($context, $json, $productRepository, $urlHelper, $imageBuilder, $priceCurrency, $dataLayerHelper, $bulbHelper, $data);
        $this->registry = $registry;
    }

    /**
     * @return \Magento\Catalog\Model\Product|null
     */
    public function getMainProduct()
    {
        if (!$this->hasData('product')) {
            $this->setData('product', $this->registry->registry('product'));
        }
        return $this->getData('product');
    }
}
