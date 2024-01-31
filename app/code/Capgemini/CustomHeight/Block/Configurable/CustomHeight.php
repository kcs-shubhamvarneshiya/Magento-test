<?php

namespace Capgemini\CustomHeight\Block\Configurable;

use Capgemini\CustomHeight\Helper\PriceHeight as PriceHeightHelper;
use Magento\Framework\App\RequestInterface;

/**
 * Class CustomHeight
 * @author Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2021 Capgemini, Inc. (www.capgemini.com)
 */
class CustomHeight extends \Magento\Catalog\Block\Product\View\AbstractView
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
     * @var RequestInterface
     */
    private $request;

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
        RequestInterface $request,
        array $data = []
    ) {
        parent::__construct($context, $arrayUtils, $data);
        $this->priceHeightHelper = $priceHeightHelper;
        $this->request = $request;
    }

    /**
     * @return bool
     */
    public function isCustomHeightDisplayed()
    {
        return $this->priceHeightHelper->isCustomHeightDisplayed($this->getProduct());
    }

    /**
     * @return array
     */
    public function getHeightPricing()
    {
        return $this->priceHeightHelper->getHeightPricingForAllProducts($this->getProduct());
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

    /**
     * @return mixed
     */
    public function getStaticNoteText()
    {
        return $this->priceHeightHelper->getStaticNoteText();
    }

    /**
     * @return mixed
     */
    public function getAvailabilityMessage()
    {
        return $this->priceHeightHelper->getAvailabilityMessage();
    }

    /**
     * @return bool|string
     */
    public function getHeightPricingForAllProducts()
    {
        if (count($this->priceHeightHelper->heightPricingForAllProducts) == 0) {
            $this->getHeightPricing();
            if (count($this->priceHeightHelper->heightPricingForAllProducts) == 0) {
                return $this->priceHeightHelper->serializeValue([]);
            }
        }
        $sortedArrayWithHeightPricing = [];
        foreach ($this->priceHeightHelper->heightPricingForAllProducts as $sku => $heightOption){
            $counter = 1;
            foreach ($heightOption as $key => $option){
                $sortedArrayWithHeightPricing[$sku][$counter] = $option;
                $counter++;
            }
        }
        return $this->priceHeightHelper->serializeValue($sortedArrayWithHeightPricing);
    }

    public function getSelectedCustomHeightValue()
    {
        return $this->request->getParam('custom_height_value');
    }
}
