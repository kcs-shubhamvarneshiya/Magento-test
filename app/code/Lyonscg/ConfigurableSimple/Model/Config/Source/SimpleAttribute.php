<?php

namespace Lyonscg\ConfigurableSimple\Model\Config\Source;

class SimpleAttribute implements \Magento\Framework\Option\ArrayInterface
{
    protected $eavConfig;

    protected $attributes = [];

    protected $optionArray = [];

    public function __construct(
        \Magento\Eav\Model\Config $eavConfig
    ) {
        $this->eavConfig = $eavConfig;
    }

    protected function _getAttributes()
    {
        if (empty($this->attributes)) {
            $attributes = $this->eavConfig->getEntityAttributes(\Magento\Catalog\Model\Product::ENTITY);
            foreach ($attributes as $attribute) {
                if ($attribute->getIsVisibleOnFront() || $attribute->getIsVisible()) {
                    $this->attributes[$attribute->getAttributeCode()] = $attribute->getDefaultFrontendLabel();
                }
            }
            ksort($this->attributes);
        }
        return $this->attributes;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (empty($this->optionArray)) {
            foreach ($this->_getAttributes() as $code => $label) {
                $this->optionArray[] = ['value' => $code, 'label' => $label];
            }
        }
        return $this->optionArray;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return $this->_getAttributes();
    }
}
