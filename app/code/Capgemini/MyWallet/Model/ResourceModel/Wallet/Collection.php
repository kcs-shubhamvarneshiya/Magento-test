<?php
/**
 * Wallet Collection resource model
 *
 * @category  Capgemini
 * @author    Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2020 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\MyWallet\Model\ResourceModel\Wallet;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'Capgemini\MyWallet\Model\Wallet',
            'Capgemini\MyWallet\Model\ResourceModel\Wallet'
        );
    }
}
