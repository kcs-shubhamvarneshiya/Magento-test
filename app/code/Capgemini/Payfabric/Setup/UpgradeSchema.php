<?php
/**
 * Capgemini_Payfabric
 *
 * @category   Capgemini
 * @author    Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2020 Capgemini, Inc (www.capgemini.com)
 */

namespace Capgemini\Payfabric\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * Update DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws \Zend_Db_Exception
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '0.0.2', '<')) {
            $this->_createIsDefaultFieldinPayfabricTable($setup);
        }

        if (version_compare($context->getVersion(), '0.0.3', '<')) {
            $this->_createOwnerNameFieldinPayfabricTable($setup);
        }
        $installer->endSetup();
    }

    /**
     * @param $setup
     */
    protected function _createIsDefaultFieldinPayfabricTable($setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('payfabric_wallet'),
            'is_default',
            [
                'type' => 'boolean',
                'nullable' => true,
                'comment' => 'Is card default',
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('payfabric_wallet'),
            'cc_nickname',
            [
                'type' => 'text',
                'nullable' => true,
                'comment' => 'Card Nickname',
            ]
        );
    }

    /**
     * @param $setup
     */
    protected function _createOwnerNameFieldinPayfabricTable($setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('payfabric_wallet'),
            'cc_holder_name',
            [
                'type' => 'text',
                'nullable' => true,
                'comment' => 'Cardholder name',
            ]
        );
    }
}
