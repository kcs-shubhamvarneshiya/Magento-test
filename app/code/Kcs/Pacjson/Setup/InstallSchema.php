<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Created By : Nirav Modi
 */
namespace Kcs\Pacjson\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $installer = $setup;

        $installer->startSetup();
        /**
         * Create table 'kcs_pacjson'
         */
        if (!$installer->tableExists('kcs_pacjson')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('kcs_pacjson')
            )->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true,
                ],
                'ID'
            )->addColumn(
                'pid',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => false,
                    'nullable' => true,
                    'primary' => false,
                    'unsigned' => true,
                ],
                'Product ID'
            )->addColumn(
                'pname',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable => false',
                ],
                'Product Name'
            )->addColumn(
                'attribute_combination',
                Table::TYPE_TEXT,
                '2M',
                [
                    'nullable => false',
                ],
                'Attribute Combination'
            )->addColumn(
                'option_combination',
                Table::TYPE_TEXT,
                '2M',
                [],
                'Option Combination'
            )->addColumn(
                'option_combination_json',
                Table::TYPE_TEXT,
                '2M',
                [],
                'Option Combination Json'
            )->addColumn(
                'status',
                Table::TYPE_SMALLINT,
                null,
                [
                    'nullable' => false,
                ],
                'Status'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable' => false,
                    'default' => Table::TIMESTAMP_INIT,
                ],
                'Created At'
            )->addColumn(
                'sql_serv_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => false,
                    'nullable' => false,
                    'primary' => false,
                    'unsigned' => true,
                ],
                'Sql Server ID'
            )->addColumn(
                'sql_serv_prod_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => false,
                    'nullable' => false,
                    'primary' => false,
                    'unsigned' => true,
                ],
                'Sql Server Prod ID'
            )->setComment('Product Attribute Combination Json Table');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
