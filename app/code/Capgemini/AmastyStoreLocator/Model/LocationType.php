<?php
/**
 * Capgemini_AmastyStoreLocator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\AmastyStoreLocator\Model;

/**
 * Location type attribute loader
 */
class LocationType
{
    CONST CODE = 'location_type';

    /**
     * @var \Amasty\Storelocator\Model\ResourceModel\Attribute\CollectionFactory
     */
    protected $attributeCollectionFactory;

    /**
     * @var array
     */
    protected $attribute;

    /**
     * @param \Amasty\Storelocator\Model\ResourceModel\Attribute\CollectionFactory $attributeCollectionFactory
     */
    public function __construct(
        \Amasty\Storelocator\Model\ResourceModel\Attribute\CollectionFactory $attributeCollectionFactory
    ) {
        $this->attributeCollectionFactory = $attributeCollectionFactory;
    }

    /**
     * @return array:false
     */
    public function getAttribute()
    {
        if (!isset($this->attribute)) {
            /* @var \Amasty\Storelocator\Model\ResourceModel\Attribute\Collection $collection */
            $collection = $this->attributeCollectionFactory->create();
            $collection->addFieldToFilter('attribute_code', self::CODE);
            $attributes = $collection->preparedAttributes();
            if (count($attributes) > 0) {
                $this->attribute = array_shift($attributes);
            } else {
                $this->attribute = null;
            }
        }
        return $this->attribute;
    }

    /**
     * Get option ID by label
     *
     * @param $optionLabel
     */
    public function getOptionId($optionLabel)
    {
        if ($this->getAttribute()) {
            $options = $this->getAttribute()['options'];
            foreach ($options as $option)
            {
                if ($option['label'] == $optionLabel) {
                    return $option['value'];
                }
            }
        }
        return null;
    }

    /**
     * @return int
     */
    public function getAttributeId()
    {
        if ($this->getAttribute()) {
            return $this->getAttribute()['attribute_id'];
        }
        return null;
    }
}
