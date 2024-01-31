<?php
namespace Rysun\DataTransfer\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Category;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CatProdIsArchi implements DataPatchInterface
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
            'is_architech_data',
            [
                'type' => 'int',
                'label' => 'Is Architech Data?',
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
            Category::ENTITY,
            'is_architech_data',
            [
                'type' => 'int',
                'label' => 'Is Architech Data?',
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