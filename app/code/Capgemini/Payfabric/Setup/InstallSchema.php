<?php
/**
 * Capgemini_Payfabric
 *
 * @category   Capgemini
 * @author    Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2020 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\Payfabric\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 * @package Capgemini\Payfabric\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $setup->getConnection()->addColumn(
            $setup->getTable('quote_payment'),
            'payfabric_wallet_id',
            [
                'type' => 'text',
                'nullable' => true,
                'comment' => 'Payfabric Wallet ID',
            ]
        );
        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order_payment'),
            'payfabric_wallet_id',
            [
                'type' => 'text',
                'nullable' => true,
                'comment' => 'Payfabric Wallet ID',
            ]
        );


        /**
         * Create table 'payfabric_wallet'
         */
        $table = $setup->getConnection()->newTable(
            $setup->getTable('payfabric_wallet')
        )->addColumn(
            'wallet_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'primary' => true, 'unsigned' => true ,  'auto_increment' => true ],
            'Entity ID Primary Key'
        )->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Customer Id'
        )->addColumn(
            'cc_last4',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true]
        )->addColumn(
            'card_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true]
        )->addColumn(
            'card_exp_date',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true]

        )->addColumn(
            'payfabric_wallet_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true]

        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['nullable' => false]
        );

        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
