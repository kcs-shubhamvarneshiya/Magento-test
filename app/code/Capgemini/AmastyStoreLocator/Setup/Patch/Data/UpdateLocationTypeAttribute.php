<?php
/**
 * Capgemini_AmastyStoreLocator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\AmastyStoreLocator\Setup\Patch\Data;

use Amasty\Base\Model\Serializer;
use Amasty\Storelocator\Model\AttributeFactory;
use Amasty\Storelocator\Model\ResourceModel\Attribute;
use Amasty\Storelocator\Model\ResourceModel\Options;
use Amasty\Storelocator\Model\ResourceModel\Options\CollectionFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Update location_type attribute
 */
class UpdateLocationTypeAttribute implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    protected $moduleDataSetup;
    /**
     * @var AttributeFactory
     */
    protected $attributeFactory;
    /**
     * @var Attribute
     */
    protected $attributeResource;
    /**
     * @var CollectionFactory
     */
    protected $optionsCollectionFactory;
    /**
     * @var Options
     */
    protected $optionsResource;
    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * Constructor
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        AttributeFactory $attributeFactory,
        Attribute $attributeResource,
        CollectionFactory $optionsCollectionFactory,
        Options $optionsResource,
        Serializer $serializer,
    ) {

        $this->moduleDataSetup = $moduleDataSetup;
        $this->attributeFactory = $attributeFactory;
        $this->attributeResource = $attributeResource;
        $this->optionsCollectionFactory = $optionsCollectionFactory;
        $this->optionsResource = $optionsResource;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritDoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $attribute = $this->attributeFactory->create();
        $this->attributeResource->load($attribute, 'location_type', 'attribute_code');

        if ($attribute->getId()) {
            $labelsToReplace = ['Showroom' => 'Visual Comfort Showrooms',
                'Dealer' => 'Dealers/Retail Partners'];

            /* @var \Amasty\Storelocator\Model\ResourceModel\Options\Collection $collection */
            $collection = $this->optionsCollectionFactory->create();
            $collection->addFieldToFilter('attribute_id', ['eq' => $attribute->getId()]);

            foreach ($collection as $option) {
                $labels = $option->getData('options_serialized');
                $labels = $this->serializer->unserialize($labels);
                $wasModified = false;
                foreach ($labels as $key => $label) {
                    if (isset($labelsToReplace[$label])) {
                        $labels[$key] = $labelsToReplace[$label];
                        $wasModified = true;
                    }
                }
                if ($wasModified) {
                    $option->setData('options_serialized', $this->serializer->serialize($labels));
                    $this->optionsResource->save($option);
                }
            }
        }

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritDoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getAliases()
    {
        return [];
    }
}