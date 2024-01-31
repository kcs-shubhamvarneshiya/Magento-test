<?php

namespace Lyonscg\SalesPad\Api;

use Lyonscg\SalesPad\Api\Data\CustomerSyncInterface;
use Lyonscg\SalesPad\Api\Data\OrderSyncInterface;
use Lyonscg\SalesPad\Api\Data\QuoteItemSyncInterface;
use Lyonscg\SalesPad\Api\Data\QuoteSyncInterface;
use Magento\Customer\Model\Customer as CustomerModel;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\RequisitionList\Api\Data\RequisitionListInterface;
use Magento\RequisitionList\Api\Data\RequisitionListItemInterface;
use Magento\Sales\Api\Data\OrderInterface;

interface SyncRepositoryInterface
{
    /**
     * @param CustomerModel $customer
     * @return CustomerSyncInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function addCustomer(CustomerModel $customer);

    /**
     * @param CustomerSyncInterface $customerSync
     */
    public function saveCustomerEntry(CustomerSyncInterface $customerSync);

    /**
     * @param CustomerSyncInterface $customerSync
     */
    public function deleteCustomerEntry(CustomerSyncInterface $customerSync);

    /**
     * @param int $id
     * @throws NoSuchEntityException
     */
    public function deleteCustomerEntryById(int $id);

    /**
     * @param $customerId
     * @return Sync\Customer
     * @throws NoSuchEntityException
     */
    public function getByCustomerId($customerId);

    /**
     * @param OrderInterface $order
     * @return $this
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function addOrder(OrderInterface $order);

    /**
     * @param OrderSyncInterface $orderSync
     */
    public function saveOrderEntry(OrderSyncInterface $orderSync);

    /**
     * @param OrderSyncInterface $orderSync
     */
    public function deleteOrderEntry(OrderSyncInterface $orderSync);

    /**
     * @param int $id
     * @throws NoSuchEntityException
     */
    public function deleteOrderEntryById(int $id);

    /**
     * @param $limit
     * @param $storeId
     * @return int[]
     */
    public function getCustomerIdsToSync($limit, $storeId = null);

    /**
     * @param $limit
     * @param $storeId
     * @return int[]
     */
    public function getOrderIdsToSync($limit, $storeId = null);

    /**
     * @param $limit
     * @param $storeId
     * @return \Lyonscg\SalesPad\Api\Data\CustomerSyncInterface[]
     */
    public function getCustomersToSync($limit, $storeId = null);

    /**
     * @param $limit
     * @param $storeId
     * @return \Lyonscg\SalesPad\Api\Data\OrderSyncInterface[]
     */
    public function getOrdersToSync($limit, $storeId = null);

    /**
     * @param RequisitionListInterface $list
     * @return $this
     */
    public function addRequisitionList(RequisitionListInterface $list);

    /**
     * @param RequisitionListInterface $list
     * @return $this
     */
    public function addRequisitionListForDelete(RequisitionListInterface $list);

    /**
     * @param QuoteSyncInterface $quoteSync
     */
    public function saveQuoteEntry(QuoteSyncInterface $quoteSync);

    /**
     * @param QuoteSyncInterface $quoteSync
     */
    public function deleteQuoteEntry(QuoteSyncInterface $quoteSync);

    /**
     * @param int $id
     * @throws NoSuchEntityException
     */
    public function deleteQuoteEntryById(int $id);

    /**
     * @param $limit
     * @param $storeId
     * @return int[]
     */
    public function getQuoteIdsToSync($limit, $storeId = null);

    /**
     * @param $limit
     * @param $storeId
     * @param $customerId
     * @return QuoteSyncInterface[]
     */
    public function getQuotesToSync($limit = null, $storeId = null, $customerId = null);

    /**
     * @param $listId
     * @return QuoteSyncInterface|null
     */
    public function getQuoteToSyncByRequisitionListId($listId);

    /**
     * @param RequisitionListItemInterface $list
     * @return $this
     */
    public function addRequisitionListItem(RequisitionListItemInterface $item);

    /**
     * @param RequisitionListItemInterface $list
     * @return $this
     */
    public function addRequisitionListItemForDelete(RequisitionListItemInterface $item);

    /**
     * @param QuoteItemSyncInterface $quoteSync
     */
    public function saveQuoteItemEntry(QuoteItemSyncInterface $quoteItemSync);

    /**
     * @param QuoteItemSyncInterface $quoteSync
     */
    public function deleteQuoteItemEntry(QuoteItemSyncInterface $quoteItemSync);

    /**
     * @param $quoteId
     * @return QuoteItemSyncInterface[]
     */
    public function getQuoteItemsToSyncForQuoteId($quoteId);

    /**
     * @param $limit
     * @return int[]
     */
    public function getQuoteItemIdsToSync($limit);

    /**
     * @param $limit
     * @return QuoteItemSyncInterface[]
     */
    public function getQuoteItemsToSync($limit);

    /**
     * @param $salesDocNum
     * @param $lineNum
     * @param $componentSeqNum
     * @return boolean
     */
    public function hasItemBeenDeleted($salesDocNum, $lineNum, $componentSeqNum);
}
