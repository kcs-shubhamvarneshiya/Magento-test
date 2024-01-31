<?php
/**
 * Copyright © 2016 Lyons Consulting Group, LLC. All rights reserved.
 */

namespace Lyonscg\ImportExport\Setup;

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
        $setup->startSetup();

        $this->addCronField($setup);

        $setup->endSetup();
    }

    /**
     * Add cron field to scheduled operation table
     *
     * @param SchemaSetupInterface $setup
     */
    protected function addCronField(SchemaSetupInterface $setup)
    {
        /**
         * Add media type property to the Gallery entry table
         */
        $setup->getConnection()->addColumn(
            $setup->getTable('magento_scheduled_operations'),
            'cron',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 64,
                'nullable' => false,
                'default' => '',
                'comment' => 'CRON value'
            ]
        );
    }
}
