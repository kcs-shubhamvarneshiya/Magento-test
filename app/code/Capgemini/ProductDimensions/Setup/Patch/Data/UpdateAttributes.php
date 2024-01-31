<?php
/**
 * Capgemini_ProductDimensions
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_ProductDimensions
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */
declare(strict_types=1);

namespace Capgemini\ProductDimensions\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * UpdateAttributes Class
 */
class UpdateAttributes implements DataPatchInterface
{
    /**
     * ModuleDataSetupInterface
     *
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $_moduleDataSetup;

    /**
     * EavSetupFactory
     *
     * @var EavSetupFactory
     */
    private EavSetupFactory $_eavSetupFactory;

    /**
     * Short description
     *
     * @param ModuleDataSetupInterface $moduleDataSetup Param description
     * @param EavSetupFactory          $eavSetupFactory Param description
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->_moduleDataSetup = $moduleDataSetup;
        $this->_eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $this->_moduleDataSetup]);
        foreach (range(1, 10) as $x) {
            $eavSetup->updateAttribute(
                Product::ENTITY,
                'dimension_'.$x,
                [
                    'used_in_product_listing' => 1,
                ]
            );
            $eavSetup->updateAttribute(
                Product::ENTITY,
                'specification_bottom_'.$x,
                [
                    'used_in_product_listing' => 1,
                ]
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return [];
    }
}
