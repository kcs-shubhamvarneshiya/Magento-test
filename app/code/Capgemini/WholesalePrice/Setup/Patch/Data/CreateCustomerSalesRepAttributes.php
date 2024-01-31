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

class CreateCustomerSalesRepAttributes implements DataPatchInterface
{
    /**
     * ModuleDataSetupInterface
     *
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $_moduleDataSetup;

    /**
     * @var CustomerSetupFactory
     */
    private CustomerSetupFactory $_customerSetupFactory;

    /**
     * @var Config
     */
    private Config $_eavConfig;

    /**
     * Short description
     *
     * @param ModuleDataSetupInterface $moduleDataSetup Param description
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory,
        Config $eavConfig
    ) {
        $this->_moduleDataSetup = $moduleDataSetup;
        $this->_customerSetupFactory = $customerSetupFactory;
        $this->_eavConfig = $eavConfig;
    }

    public function apply()
    {
        $customerSetup = $this->_customerSetupFactory->create(['setup' => $this->_moduleDataSetup]);
        $customerEntity = $customerSetup->getEavConfig()->getEntityType(Customer::ENTITY);
        $attributeSetId = $customerSetup->getDefaultAttributeSetId(
            $customerEntity->getEntityTypeId()
        );
        $attributeGroup = $customerSetup->getDefaultAttributeGroupId(
            $customerEntity->getEntityTypeId(),
            $attributeSetId
        );

        $attributes = [
            'customer_sales_rep',
            'customer_sales_subrep'
        ];

        foreach ($attributes as $attributeCode) {
            $customerSetup->addAttribute(
                Customer::ENTITY,
                $attributeCode,
                [
                    'type' => 'varchar',
                    'input' => 'text',
                    'label' => $attributeCode,
                    'required' => false,
                    'default' => null,
                    'visible' => false,
                    'user_defined' => true,
                    'system' => false,
                    'position' => 300
                ]
            );
            $newAttribute = $this->_eavConfig->getAttribute(Customer::ENTITY, $attributeCode);
            $newAttribute->addData([
                'used_in_forms' => ['adminhtml_customer'],
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroup
            ]);
            $newAttribute->save();
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
