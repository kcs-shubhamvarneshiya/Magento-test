<?php

namespace Lyonscg\ConfigurableSimple\Model\Config\Source;

class HiddenSpecificationAttributes implements \Magento\Framework\Option\ArrayInterface
{
    protected $attributesToHide = [
        'criteria_1',
        'criteria_2',
        'criteria_3',
        'criteria_4',
        'criteria_5',
        'criteria_6',
        'criteria_7',
        'chain_length',
        'shade_details',
        'trademark',
        'note',
        'notes_circa',
        'designer',
        'style',
        'basecode',
        'bulb_sku'
    ];

    protected $options = [];

    protected $eavConfig;

    public function __construct(
        \Magento\Eav\Model\Config $eavConfig
    ) {
        $this->eavConfig = $eavConfig;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if (empty($this->options)) {
            foreach ($this->attributesToHide as $attributeCode) {
                $attribute = $this->eavConfig->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $attributeCode);
                if ($attribute) {
                    $this->options[$attributeCode] = $attribute->getDefaultFrontendLabel();
                } else {
                    $this->options[$attributeCode] = $attributeCode;
                }
            }
        }
        return $this->options;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        foreach ($this->toArray() as $code => $label) {
            $options[] = [
                'value' => $code,
                'label' => $label
            ];
        }
        return $options;
    }
}
