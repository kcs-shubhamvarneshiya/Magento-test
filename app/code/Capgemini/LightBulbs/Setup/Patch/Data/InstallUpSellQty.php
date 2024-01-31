<?php


namespace Capgemini\LightBulbs\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class InstallUpSellQty implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    public function apply()
    {
        $upSellQty = [
            'link_type_id' => \Magento\Catalog\Model\Product\Link::LINK_TYPE_UPSELL,
            'product_link_attribute_code' => 'qty',
            'data_type' => 'int'
        ];

        $this->moduleDataSetup->getConnection()->insert(
            $this->moduleDataSetup->getTable('catalog_product_link_attribute'),
            $upSellQty
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
