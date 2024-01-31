<?php

namespace Rysun\DataTransfer\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Setup\Exception;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        try {
            $installer->startSetup();

            $table = $installer->getConnection()->newTable(
                $installer->getTable('rysun_product_pricing')
            )->addColumn(
                'pricing_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Pricing Id'
            )->addColumn(
                'sql_serv_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => true
                ],
                'SQL Server Id'
            )->addColumn(
                'sql_serv_prod_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => true
                ],
                'Sql Server Product Id'
            )->addColumn(
                'product_sku',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '255',
                ['nullable' => false],
                'Product Sku'
            )->addColumn(
                'price',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'Price'
            )->setComment(
                'Product Pricing Table for SKU Builder'
            );

            $installer->getConnection()->createTable($table);


            // ==============================Feature Start==================================

            $table = $installer->getConnection()->newTable(
                $installer->getTable('rysun_features')
            )->addColumn(
                'feature_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Feature Id'
            )->addColumn(
                'sql_serv_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => true
                ],
                'SQL Server Id'
            )->addColumn(
                'feature_desc',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '255',
                ['nullable' => false],
                'Feature Description'
            )->addColumn(
                'is_active',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => true
                ],
                'Is Active'
            )->setComment(
                'Feature Table'
            );

            $installer->getConnection()->createTable($table);

            // ==============================Feature End==================================


            // ==============================Product Feature Start==================================

            $table = $installer->getConnection()->newTable(
                $installer->getTable('rysun_product_features')
            )->addColumn(
                'product_feature_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Product Feature Id'
            )->addColumn(
                'sql_serv_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => true
                ],
                'SQL Server Id'
            )->addColumn(
                'sql_serv_prod_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => true
                ],
                'Sql Server Product Id'
            )->addColumn(
                'feature_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => true
                ],
                'Feature Id'
            )->addColumn(
                'sort_order',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => true
                ],
                'Sort Order'
            )->addColumn(
                'is_active',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => true
                ],
                'Is Active'
            )->setComment(
                'Feature Table'
            );

            $installer->getConnection()->createTable($table);

            // ==============================Product Feature End==================================

            $installer->endSetup();
        } catch (Exception $err) {
            \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->info($err->getMessage());
        }
    }
}
