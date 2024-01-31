<?php
/**
 * Capgemini_CompanyType
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_CompanyType
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */

declare(strict_types=1);

namespace Capgemini\CompanyType\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Config;

class CreatePurchaseLimitProductsAttributes implements DataPatchInterface
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
            'can_purchase_vc_signature',
            'can_purchase_vc_fan',
            'can_purchase_vc_modern',
            'can_purchase_vc_arch',
            'can_purchase_vc_studio',
            'can_purchase_gl',
            'can_purchase_gl_fan'
        ];

        foreach ($attributes as $attributeCode) {
            $customerSetup->addAttribute(
                Customer::ENTITY,
                $attributeCode,
                [
                    'type' => 'int',
                    'input' => 'boolean',
                    'label' => $attributeCode,
                    'required' => false,
                    'default' => 0,
                    'visible' => true,
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
