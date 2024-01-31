<?php
namespace Rysun\DataTransfer\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class ProdIsPdpAttr implements DataPatchInterface
{
    public function __construct(
        private ModuleDataSetupInterface $moduleDataSetup,
        private EavSetupFactory $eavSetupFactory
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function apply() {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute(
            Product::ENTITY,
            'is_pdp',
            [
                'type' => 'int',
                'label' => 'Is PDP',
                'input' => 'boolean',
                'sort_order' => 100,
                'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true, 
                'default' => null,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'group' => 'General Information'
            ]
        );
        $eavSetup->addAttribute(
            Product::ENTITY,
            'product_points',
            [
                'group' => 'General Information',
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'Product Points',
                'input' => 'textarea',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'wysiwyg_enabled' => true,
                'unique' => false,
                'apply_to' => ''
            ]
        );
        
        
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}