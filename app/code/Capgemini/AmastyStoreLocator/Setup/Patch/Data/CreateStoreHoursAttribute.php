<?php
/**
 * Capgemini_AmastyStoreLocator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\AmastyStoreLocator\Setup\Patch\Data;

use Amasty\Storelocator\Model\AttributeFactory;
use Amasty\Storelocator\Model\ResourceModel\Attribute;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Install store_hours attribute
 */
class CreateStoreHoursAttribute implements DataPatchInterface
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
    protected $attributerResource;

    /**
     * Constructor
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        AttributeFactory $attributeFactory,
        Attribute $attributerResource
    ) {

        $this->moduleDataSetup = $moduleDataSetup;
        $this->attributeFactory = $attributeFactory;
        $this->attributerResource = $attributerResource;
    }

    /**
     * {@inheritDoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $data = [
            'attribute_code' => 'store_hours',
            'frontend_label' => 'Store Hours',
            'frontend_input' => 'text',
            'is_required' => 0
        ];
        $attribute = $this->attributeFactory->create();
        $attribute->setData($data);

        $this->attributerResource->save($attribute);
        $this->attributerResource->saveOptions($data, $attribute->getId());

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