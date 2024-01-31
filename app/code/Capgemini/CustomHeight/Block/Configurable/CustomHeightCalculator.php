<?php

namespace Capgemini\CustomHeight\Block\Configurable;

use Capgemini\CustomHeight\Helper\PriceHeight as PriceHeightHelper;
use Magento\Framework\Serialize\SerializerInterface as Serializer;
use function _PHPStan_9a6ded56a\React\Promise\Stream\first;

/**
 * Class CustomHeight
 * @author Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2021 Capgemini, Inc. (www.capgemini.com)
 */
class CustomHeightCalculator extends \Magento\Catalog\Block\Product\View\AbstractView
{
    /**
     * @var PriceHeightHelper
     */
    protected $priceHeightHelper;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $currentProduct = null;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * CustomHeight constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Stdlib\ArrayUtils $arrayUtils
     * @param PriceHeightHelper $priceHeightHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Stdlib\ArrayUtils $arrayUtils,
        PriceHeightHelper $priceHeightHelper,
        Serializer $serializer,
        array $data = []
    ) {
        parent::__construct($context, $arrayUtils, $data);
        $this->priceHeightHelper = $priceHeightHelper;
        $this->serializer = $serializer;
    }

    /**
     * Allows reuse of this block in the CLP
     * @param \Magento\Catalog\Model\Product $product
     * @return $this
     */
    public function setProduct(\Magento\Catalog\Model\Product $product)
    {
        $this->currentProduct = $product;
        return $this;
    }

    /**
     * @return \Magento\Catalog\Model\Product|null
     */
    public function getProduct()
    {
        if ($this->currentProduct !== null) {
            return $this->currentProduct;
        } else {
            return parent::getProduct();
        }
    }

    public function getRoomConfiguration()
    {
        return $this->priceHeightHelper->getRoomConfiguration();
    }


    public function getJsonRoomConfiguration()
    {
        return $this->priceHeightHelper->getJsonRoomConfiguration();
    }

    /**
     * @return array
     */
    public function getHeightPricing()
    {
        return $this->priceHeightHelper->getHeightPricingForAllProducts($this->getProduct());
    }

    /**
     * @return bool|string
     */
    public function getProductConfiguration()
    {
        $products = $this->priceHeightHelper->getAttributeFromChilds($this->getProduct());
        $product = array_shift($products);

        $productConfig['minHeight'] = (float) $product['heightMin'] ?? null;
        $productConfig['oaHeight'] = (float) $product['oaHeight'] ?? null;
        $productConfig['maxHeight'] = $this->priceHeightHelper->getMaxHeight();

        return $this->serializer->serialize($productConfig);
    }
}
