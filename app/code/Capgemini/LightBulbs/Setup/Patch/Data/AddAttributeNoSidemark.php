<?php

namespace Capgemini\LightBulbs\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Api\AttributeManagementInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddAttributeNoSidemark implements DataPatchInterface, PatchVersionInterface
{
    const NO_SIDEMARK = 'no_sidemark';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var AttributeManagementInterface
     */
    private $attributeManagement;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        AttributeManagementInterface $attributeManagement
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeManagement = $attributeManagement;
    }

    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute(
            Product::ENTITY,
            self::NO_SIDEMARK,
            [
                'type' => 'int',
                'label' => 'No Sidemark',
                'input' => 'boolean',
                'backend' => '',
                'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                'required' => false,
                'default' => '0',
                'sort_order' => 1500,
                'global' => ScopedAttributeInterface::SCOPE_STORE
            ]
        );

        $attributeSetId = $eavSetup->getAttributeSetId(Product::ENTITY, 'Default');
        $groupId = $eavSetup->getAttributeGroupId(Product::ENTITY, $attributeSetId, 'content');
        $this->attributeManagement->assign(
            Product::ENTITY,
            $attributeSetId,
            $groupId,
            self::NO_SIDEMARK,
            1000
        );

    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public static function getVersion()
    {
        return '1.0.0';
    }
}
