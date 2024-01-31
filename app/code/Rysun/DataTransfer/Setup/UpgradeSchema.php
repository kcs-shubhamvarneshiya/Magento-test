<?php

namespace Rysun\DataTransfer\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $connection = $setup->getConnection();
            $connection->addColumn(
                $setup->getTable('eav_attribute_option_value'),
                'sql_serv_id',
                [
                    'type' => Table::TYPE_INTEGER,
                    'length' => null,
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => false,
                    'comment' => 'SQL Server Id'
                ]
            );
            $connection->addColumn(
                $setup->getTable('eav_attribute_option'),
                'sql_serv_id',
                [
                    'type' => Table::TYPE_INTEGER,
                    'length' => null,
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => false,
                    'comment' => 'SQL Server Id'
                ]
            );
        }

        $setup->endSetup();
    }
}
