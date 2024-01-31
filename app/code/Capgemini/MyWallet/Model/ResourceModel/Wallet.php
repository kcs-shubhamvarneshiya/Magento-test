<?php
/**
 * Wallet resource model
 *
 * @category  Capgemini
 * @package   Capgemini_MyWallet
 * @author    Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2020 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\MyWallet\Model\ResourceModel;

class Wallet extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Construct
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param string|null $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('payfabric_wallet', 'wallet_id');
    }

    /**
     * @param $customerId
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCustomerWallets($customerId)
    {
        $select = $this->getConnection()->select()->from(
            ['payfabric_wallet' => $this->getMainTable()],
            ['wallet_id', 'customer_id', 'cc_last4', 'card_name', 'card_exp_date', 'created_at', 'is_default', 'cc_nickname']
        )->where(
            'customer_id = ?',
            $customerId
        );
        $wallets = $this->getConnection()->fetchAll($select);

        return $wallets;
    }

    /**
     * @param $customerId
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function clearIsDefaultWalletFlag($customerId)
    {
        $this->getConnection()->update(
            $this->getMainTable(),
            [
                'is_default' => false,
            ],
            [
                'customer_id = ?' => $customerId
            ]
        );
    }
}
