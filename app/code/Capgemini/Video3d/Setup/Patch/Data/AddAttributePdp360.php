<?php

namespace Capgemini\Video3d\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Api\AttributeManagementInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddAttributePdp360 implements DataPatchInterface, PatchVersionInterface
{
    const PDP_360 = 'pdp_360';

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
            self::PDP_360,
            [
                'group' => 'General Information',
                'type' => 'varchar',
                'label' => '360 Model Filename',
                'input' => 'text',
                'backend' => '',
                'source' => '',
                'required' => false,
                'default' => '',
                'sort_order' => 1500,
                'used_in_product_listing' => true,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL
            ]
        );

        $attributeSetId = $eavSetup->getAttributeSetId(Product::ENTITY, 'Default');
        $groupId = $eavSetup->getAttributeGroupId(Product::ENTITY, $attributeSetId, 'content');
        $this->attributeManagement->assign(
            Product::ENTITY,
            $attributeSetId,
            $groupId,
            self::PDP_360,
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
