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

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Short description
 *
 * @category  Capgemini
 * @package   Capgemini_ProductDimensions
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */
class AddProductsAttributes implements DataPatchInterface
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
            $this->_createAttribute(
                $eavSetup,
                'dimension_'.$x,
                'Dimensions Attributes '.$x
            );
            $this->_createAttribute(
                $eavSetup,
                'specification_bottom_'.$x,
                'Specifications Attributes '.$x
            );
        }
    }

    /**
     * Short description
     *
     * @param $eavSetup
     * @param $attributeCode
     * @param $label
     *
     * @return void
     */
    private function _createAttribute($eavSetup, $attributeCode, $label)
    {
        $eavSetup->addAttribute('catalog_product', $attributeCode, [
            'type' => 'text',
            'backend' => '',
            'frontend' => '',
            'label' => $label,
            'input' => 'textarea',
            'class' => '',
            'source' => '',
            'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => true,
            'used_in_product_listing' => false
        ]);
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
