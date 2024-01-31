<?php

namespace Lyonscg\ConfigurableSimple\Helper;

use Magento\Framework\Phrase;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Store\Model\ScopeInterface;


class Output extends \Magento\Catalog\Helper\Output
{
    const XML_PATH_OVERRIDE_ATTRIBUTES = 'lyonscg_configurablesimple/general/override_attributes';

    const XML_PATH_SPECIFICATION_ATTRIBUTES = 'lyonscg_configurablesimple/general/specification_attributes';

    protected $_childProducts = [];

    protected $_overrideAttributes = [];

    protected $_specificationAttributes = [];

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Catalog\Helper\Data $catalogData,
        \Magento\Framework\Escaper $escaper,
        PriceCurrencyInterface $priceCurrency,
        $directivePatterns = [],
        array $handlers = []
    ) {
        parent::__construct($context, $eavConfig, $catalogData, $escaper, $directivePatterns, $handlers);
        $this->priceCurrency = $priceCurrency;
    }

    public function getChildProducts(\Magento\Catalog\Model\Product $product)
    {
        if ($product->getTypeId() !== \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            return [];
        } else {
            if (!isset($this->_childProducts[$product->getId()])) {
                $attributeCodes = [];
                foreach ($product->getAttributes() as $attribute) {
                    if ($attribute->getIsVisibleOnFront() || $attribute->getIsVisible()) {
                        $attributeCodes[] = $attribute->getAttributeCode();
                    }
                }
                $childProducts = $product->getTypeInstance()->getUsedProductCollection($product);
                $childProducts->addAttributeToSelect($attributeCodes)
                    ->setStoreId($product->getStoreId());
                $this->_childProducts[$product->getId()] = array_values($childProducts->getItems());
            }
            return $this->_childProducts[$product->getId()];
        }
    }

    /**
     * If checkOverride is true, it will only pull attributes that are set to override
     * the same attribute in configurable products.  See ticket CLMI-270.
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param bool $checkOverride
     * @return array
     */
    public function getAdditionalData(\Magento\Catalog\Model\Product $product, $checkOverride = false)
    {
        $data = [];
        $attributes = $product->getAttributes();
        foreach ($attributes as $attribute) {
            if (in_array($attribute->getAttributeCode(), $this->getSpecificationAttributes())) {
                $value = $attribute->getFrontend()->getValue($product);

                if ($value instanceof Phrase) {
                    $value = (string)$value;
                } elseif ($attribute->getFrontendInput() == 'price' && is_string($value)) {
                    $value = $this->priceCurrency->convertAndFormat($value);
                }
                if($value === null){
                    $value = '';
                }
                if (isset($value)) {
                    try {
                        $value = $this->productAttribute($product, $value, $attribute->getAttributeCode());
                    } catch (\Exception $e) {
                        $this->_logger->notice($e->getMessage());
                    }
                    $data[$attribute->getAttributeCode()] = [
                        'label' => $attribute->getStoreLabel(),
                        'value' => $value,
                        'code' => $attribute->getAttributeCode(),
                    ];
                }
            }
        }
        if(!empty($data)){
            $additionalData = $data;
            foreach($additionalData as $data) {
                if (!isset($data['value'])) {
                    continue;
                }
                if (!empty($data['value'])) {
                    $additionalData[$data['code']]['value'] = preg_replace_callback('/([\d.]+)("|in)/i', function($matches) {
                        $value = $matches[1];
                        return round((floor($value) * 2.54)) . 'cm';
                    }, $additionalData[$data['code']]['value']);
                    $additionalData[$data['code']]['value'] = preg_replace_callback('/([\d.]+)("|cm)/i', function($matches) {
                        $value = $matches[1];
                        return round((floor($value) / 2.54)) . '"';
                    }, $additionalData[$data['code']]['value']);
                    $additionalData[$data['code']]['value'] = preg_replace_callback('/([\d.]+)(\'|ft)/i', function($matches) {
                        $value = $matches[1];
                        return round((floor($value) * 0.3048)) . 'm';
                    }, $additionalData[$data['code']]['value']);
                    $additionalData[$data['code']]['value'] = preg_replace_callback('/([\d.]+)(\'|lbs)/i', function($matches) {
                        $value = $matches[1];
                        return round((floor($value) * 0.453592)) . 'kg';
                    }, $additionalData[$data['code']]['value']);
                }
            }
            return $additionalData;
        }
        return $data;
    }

    /**
     * Determine if we should display the attribute on the front-end
     *
     * If $checkOverride is true, it will see if the attribute should override
     * the same configurable attribute.
     *
     * @param \Magento\Eav\Model\Entity\Attribute\AbstractAttribute $attribute
     * @param bool $checkOverride
     * @return bool
     */
    protected function isVisibleOnFrontend(
        \Magento\Eav\Model\Entity\Attribute\AbstractAttribute $attribute,
        $checkOverride = false
    ) {
        if (!$attribute->getIsVisibleOnFront()) {
            return false;
        }
        if ($checkOverride) {
            return $this->shouldAttributeOverride($attribute->getAttributeCode());
        } else {
            return true;
        }
    }

    /**
     * @param string $attributeCode
     * @return bool
     */
    public function shouldAttributeOverride($attributeCode)
    {
        return in_array($attributeCode, $this->_getOverrideAttributes());
    }

    /**
     * @return string[]
     */
    protected function _getOverrideAttributes()
    {
        if (empty($this->_overrideAttributes)) {
            $configValue = $this->scopeConfig->getValue(
                self::XML_PATH_OVERRIDE_ATTRIBUTES,
                ScopeInterface::SCOPE_STORE
            );
            $this->_overrideAttributes = explode(',', $configValue);
        }
        return $this->_overrideAttributes;
    }

    /**
     * @return string[]
     */
    public function getSpecificationAttributes()
    {
        if (empty($this->_specificationAttributes)) {
            $configValue = $this->scopeConfig->getValue(
                self::XML_PATH_SPECIFICATION_ATTRIBUTES,
                ScopeInterface::SCOPE_STORE
            );
            $this->_specificationAttributes = explode(',', str_replace(' ', '', $configValue));
        }
        return $this->_specificationAttributes;
    }
}
