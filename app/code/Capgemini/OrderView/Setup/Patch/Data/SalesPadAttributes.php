<?php

namespace Capgemini\OrderView\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Psr\Log\LoggerInterface;

class SalesPadAttributes implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;
    /**
     * @var LoggerInterface
     */
    protected $_logger;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory,
        LoggerInterface $logger
    ) {
        $this->_logger = $logger;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
    }
    /**
     * @inheritdoc
     */
    public function apply()
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $connection = $customerSetup->getSetup()->getConnection();
        $this->setupCustomerSalesSubRep($connection);
        $this->setupCustomerSalesRep($connection);
    }
    public function setupCustomerSalesSubRep($connection)
    {
        $eavAattribute = $connection->select()->from(
            "eav_attribute",
            ['attribute_id']
        )->where('attribute_code = "customer_sales_subrep"');
        $eavAattributeId = $connection->fetchOne($eavAattribute);
        $select = $connection->select()
            ->from(
                ['e' => 'customer_entity'],
                ['firstname', 'lastname', 'email','entity_id']
            );
        $select->where('entity_id not in(SELECT entity_id
FROM customer_entity_varchar
WHERE attribute_id = '.$eavAattributeId.' )');
        $data = $connection->fetchAll($select);
        $insertData = [];
        $doneCustomers=[];
        foreach ($data as $customer)
        {
            $doneCustomers[] =['email' => $customer['email'],'name' => $customer['firstname']." ".$customer['lastname'], 'entity_id'=>$customer['entity_id']];
            $insertData[] = [
                'entity_id'=>$customer['entity_id'],
                'attribute_id'=>$eavAattributeId,
            ];
        }
        $insertMultiple = $connection->insertMultiple("customer_entity_varchar",$insertData);
        if($insertMultiple > 0)
        {
            $this->_logger->critical("Customer customer_sales_subrep Start");
            $this->_logger->critical(var_export($doneCustomers,true));
            $this->_logger->critical("Customer customer_sales_subrep End");
        }
    }
    public function setupCustomerSalesRep($connection)
    {
        $eavAattribute = $connection->select()->from(
            "eav_attribute",
            ['attribute_id']
        )->where('attribute_code = "customer_sales_rep"');
        $eavAattributeId = $connection->fetchOne($eavAattribute);
        $select = $connection->select()
            ->from(
                ['e' => 'customer_entity'],
                ['firstname', 'lastname', 'email','entity_id']
            );
        $select->where('entity_id not in(SELECT entity_id
FROM customer_entity_varchar
WHERE attribute_id = '.$eavAattributeId.' )');

        $data = $connection->fetchAll($select);
        $insertData = [];
        $doneCustomers=[];
        foreach ($data as $customer)
        {
            $doneCustomers[] =['email' => $customer['email'],'name' => $customer['firstname']." ".$customer['lastname'], 'entity_id'=>$customer['entity_id']];
            $insertData[] = [
                'entity_id'=>$customer['entity_id'],
                'attribute_id'=>$eavAattributeId,
            ];
        }
        $insertMultiple = $connection->insertMultiple("customer_entity_varchar",$insertData);
        if($insertMultiple > 0)
        {
            $this->_logger->critical("Customer customer_sales_rep Start");
            $this->_logger->critical(var_export($doneCustomers,true));
            $this->_logger->critical("Customer customer_sales_rep End");
        }
    }
    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}
