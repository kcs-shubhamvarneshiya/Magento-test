<?php

namespace Lyonscg\Catalog\Plugin\Block\Product\Renderer\Listing;

use Magento\Framework\App\ObjectManager;
use Magento\Swatches\Block\Product\Renderer\Listing\Configurable;

class ConfigurablePlugin
{
    /**
     * @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable\Variations\Prices
     */
    private $variationPrices;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    private $jsonEncoder;

    /**
     * @var \Lyonscg\Catalog\Helper\Data
     */
    protected $helper;

    /**
     * ConfigurablePlugin constructor.
     * @param \Lyonscg\Catalog\Helper\Data $helper
     * @param \Magento\Framework\Serialize\Serializer\Json $jsonEncoder
     * @param \Magento\ConfigurableProduct\Model\Product\Type\Configurable\Variations\Prices|null $variationPrices
     */
    public function __construct(
        \Lyonscg\Catalog\Helper\Data $helper,
        \Magento\Framework\Serialize\Serializer\Json $jsonEncoder,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable\Variations\Prices $variationPrices = null
    ) {
        $this->helper = $helper;
        $this->jsonEncoder = $jsonEncoder;
        $this->variationPrices = $variationPrices ?: ObjectManager::getInstance()->get(
            \Magento\ConfigurableProduct\Model\Product\Type\Configurable\Variations\Prices::class
        );
    }

    public function afterGetPricesJson(Configurable $subject, $prices)
    {
        $product = $this->helper->getChildProduct($subject->getProduct());
        return $this->jsonEncoder->serialize(
            $this->variationPrices->getFormattedPrices($product->getPriceInfo())
        );
    }
}
