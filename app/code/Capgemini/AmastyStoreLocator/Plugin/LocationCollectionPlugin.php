<?php
/**
 * Capgemini_AmastyStoreLocator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\AmastyStoreLocator\Plugin;

use Amasty\Storelocator\Model\ResourceModel\Location\Collection;
use Capgemini\AmastyStoreLocator\Model\LocationType;

/**
 * Plugin for the \Amasty\Storelocator\Model\ResourceModel\Location\Collection
 */
class LocationCollectionPlugin
{
    /**
     * @var LocationType
     */
    protected $locationType;

    /**
     * @param LocationType $locationType
     */
    public function __construct(
        LocationType $locationType
    ) {
        $this->locationType = $locationType;
    }

    /**
     * Add filter by location_type
     *
     * @param Collection $subject
     * @return array
     */
    public function beforeApplyDefaultFilters(Collection $subject): array
    {
        $attributeTableAlias = 'location_type';
        $attributeId = $this->locationType->getAttributeId();
        if ($attributeId) {
            $subject->getSelect()
                ->joinLeft(
                    [$attributeTableAlias => $subject->getTable('amasty_amlocator_store_attribute')],
                    "main_table.id = $attributeTableAlias.store_id AND $attributeTableAlias.attribute_id = $attributeId"
                )->order("$attributeTableAlias.value");
        }
        return [];
    }
}
