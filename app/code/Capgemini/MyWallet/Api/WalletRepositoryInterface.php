<?php
/**
 * Wallet Repository Interface
 *
 * @category  Lyons
 * @package   Capgemini_MyWallet
 * @author    Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2020 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\MyWallet\Api;

interface WalletRepositoryInterface
{
    /**
     * Create Wallet service
     *
     * @param int $customerId
     * @param Data\WalletInterface $wallet
     * @return \Capgemini\MyWallet\Api\Data\WalletInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save($customerId, \Capgemini\MyWallet\Api\Data\WalletInterface $wallet);

    /**
     * Get info about wallet  by wallet id
     *
     * @param int $customerId
     * @param int $walletId
     * @param int $storeId
     * @return \Capgemini\MyWallet\Api\Data\WalletInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($customerId, $walletId, $storeId = null);

    /**
     * Delete wallet
     *
     * @param int $customerId
     * @param \Capgemini\MyWallet\Api\Data\WalletInterface $wallet wallet which will deleted
     * @return bool Will returned True if deleted
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function delete($customerId, \Capgemini\MyWallet\Api\Data\WalletInterface $wallet);

    /**
     * Delete wallet by identifier
     *
     * @param int $customerId
     * @param string $walletId
     * @return bool Will returned True if deleted
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById($customerId, $walletId);

    /**
     * Get wallet list
     *
     * @param int $customerId
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Capgemini\MyWallet\Api\Data\WalletSearchResultsInterface
     */
    public function getList($customerId, \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
