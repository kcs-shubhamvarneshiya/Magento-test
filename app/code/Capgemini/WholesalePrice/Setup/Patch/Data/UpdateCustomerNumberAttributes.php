<?php
/**
 * Capgemini_WholesalePrice
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_WholesalePrice
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */

declare(strict_types=1);

namespace Capgemini\WholesalePrice\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Config;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * class UpdateDivisionAttribute
 */
class UpdateCustomerNumberAttributes implements DataPatchInterface
{
    /**
     * ModuleDataSetupInterface
     *
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $_moduleDataSetup;

    /**
     * CustomerSetupFactory
     *
     * @var CustomerSetupFactory
     */
    private CustomerSetupFactory $_customerSetupFactory;

    /**
     * Short description
     *
     * @param ModuleDataSetupInterface $moduleDataSetup Param description
     * @param CustomerSetupFactory          $customerSetupFactory Param description
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->_moduleDataSetup = $moduleDataSetup;
        $this->_customerSetupFactory = $customerSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->_customerSetupFactory->create(['setup' => $this->_moduleDataSetup]);
        $customerTypeLabels = ['VC Customer Number', 'Tech Customer Number', 'GL Customer Number'];
        foreach (['customer_number_vc','customer_number_tech','customer_number_gl'] as $key => $value) {
            $eavSetup->updateAttribute(
                Customer::ENTITY,
                $value,
                [
                    'label' => $customerTypeLabels[$key],
                    'is_used_in_grid' => true,
                    'is_visible_in_grid' => true,
                    'is_filterable_in_grid' => true,
                    'is_searchable_in_grid' => true
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
