<?php

namespace Lyonscg\SalesPad\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;

class EnsureCorrectSalesPadCustomerNumsForCompanyUsers implements DataPatchInterface
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
     */
    public function __construct(\Magento\Framework\App\ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public function apply()
    {
        $connection = $this->resourceConnection->getConnection();
        $companyAdvancedCustomerEntityTable = $this->resourceConnection->getTableName('company_advanced_customer_entity');
        $companyTable = $this->resourceConnection->getTableName('company');
        $salespadCustomerLinkTable = $this->resourceConnection->getTableName('salespad_customer_link');

        $dataPreparingSelect = $connection->select()
            ->from(
                ['adv' => $companyAdvancedCustomerEntityTable],
                ['adv_customer_id' => 'adv.customer_id', 'scl_sales_pad_customer_num' => 'scl.sales_pad_customer_num']
            )
            ->joinLeft(['com' => $companyTable], 'com.entity_id=adv.company_id', [''])
            ->join(['scl' => $salespadCustomerLinkTable], 'com.super_user_id=scl.customer_id', [''])
            ->where('adv.customer_id<>com.super_user_id AND adv.company_id<>0');
        $updateSelect = $connection->select()
            ->join(
                ['sel' => $dataPreparingSelect],
                'sel.adv_customer_id=customer_id',
                ['sales_pad_customer_num' => 'sel.scl_sales_pad_customer_num']
            );

        $connection->beginTransaction();
        try {
            $sql = $connection->updateFromSelect($updateSelect, ['spcl' => $salespadCustomerLinkTable]);
            $connection->query($sql);
            $connection->commit();
        } catch (\Exception $exception) {
            $connection->rollBack();
        }

        return $this;
    }
}
