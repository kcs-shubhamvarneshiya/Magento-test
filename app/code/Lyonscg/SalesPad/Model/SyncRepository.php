<?php

namespace Lyonscg\SalesPad\Model;

use Lyonscg\SalesPad\Api\Data\AbstractSyncInterface;
use Lyonscg\SalesPad\Api\Data\CustomerSyncInterface;
use Lyonscg\SalesPad\Api\Data\OrderSyncInterface;
use Lyonscg\SalesPad\Api\Data\QuoteItemSyncInterface;
use Lyonscg\SalesPad\Api\Data\QuoteSyncInterface;
use Lyonscg\SalesPad\Helper\Quote as QuoteHelper;
use Lyonscg\SalesPad\Model\Sync\CustomerFactory as CustomerSyncFactory;
use Lyonscg\SalesPad\Model\Sync\OrderFactory as OrderSyncFactory;
use Lyonscg\SalesPad\Model\Sync\QuoteFactory as QuoteSyncFactory;
use Lyonscg\SalesPad\Model\Sync\QuoteItemFactory as QuoteItemSyncFactory;
use Lyonscg\SalesPad\Model\ResourceModel\Sync\Customer\CollectionFactory as CustomerCollectionFactory;
use Lyonscg\SalesPad\Model\ResourceModel\Sync\Order\CollectionFactory as OrderCollectionFactory;
use Lyonscg\SalesPad\Model\ResourceModel\Sync\Quote\CollectionFactory as QuoteCollectionFactory;
use Lyonscg\SalesPad\Model\ResourceModel\Sync\QuoteItem\CollectionFactory as QuoteItemCollectionFactory;
use Lyonscg\SalesPad\Api\SyncRepositoryInterface;
use Magento\Customer\Model\Customer as CustomerModel;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\RequisitionList\Api\Data\RequisitionListInterface;
use Magento\RequisitionList\Api\Data\RequisitionListItemInterface;
use Magento\Sales\Api\Data\OrderInterface;

class SyncRepository implements SyncRepositoryInterface
{
    /**
     * @var CustomerSyncFactory
     */
    protected $customerSyncFactory;

    /**
     * @var OrderSyncFactory
     */
    protected $orderSyncFactory;

    protected $quoteSyncFactory;

    protected $quoteItemSyncFactory;

    /**
     * @var CustomerCollectionFactory
     */
    protected $customerCollectionFactory;

    /**
     * @var OrderCollectionFactory
     */
    protected $orderCollectionFactory;

    protected $quoteCollectionFactory;

    protected $quoteItemCollectionFactory;

    protected $quoteHelper;

    /**
     * SyncRepository constructor.
     * @param CustomerSyncFactory $customerSyncFactory
     * @param OrderSyncFactory $orderSyncFactory
     */
    public function __construct(
        CustomerSyncFactory $customerSyncFactory,
        OrderSyncFactory $orderSyncFactory,
        QuoteSyncFactory $quoteSyncFactory,
        QuoteItemSyncFactory $quoteItemSyncFactory,
        CustomerCollectionFactory $customerCollectionFactory,
        OrderCollectionFactory $orderCollectionFactory,
        QuoteCollectionFactory $quoteCollectionFactory,
        QuoteItemCollectionFactory $quoteItemCollectionFactory,
        QuoteHelper $quoteHelper
    ) {
        $this->customerSyncFactory = $customerSyncFactory;
        $this->orderSyncFactory = $orderSyncFactory;
        $this->quoteSyncFactory = $quoteSyncFactory;
        $this->quoteItemSyncFactory = $quoteItemSyncFactory;
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->quoteCollectionFactory = $quoteCollectionFactory;
        $this->quoteItemCollectionFactory = $quoteItemCollectionFactory;
        $this->quoteHelper = $quoteHelper;
    }

    /**
     * @param CustomerModel $customer
     * @return CustomerSyncInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function addCustomer(CustomerModel $customer)
    {
        // TODO - add logging
        /** @var \Lyonscg\SalesPad\Model\Sync\Customer $customerSync */
        $customerSync = $this->_getByCustomerId($customer->getId());
        if (!$customerSync->getId()) {
            $customerSync->setCustomerId($customer->getId())
                ->setStoreId($customer->getStoreId());
            $this->saveCustomerEntry($customerSync);
        }
        return $customerSync;
    }

    /**
     * @param CustomerSyncInterface $customerSync
     */
    public function saveCustomerEntry(CustomerSyncInterface $customerSync)
    {
        $customerSync->getResource()->save($customerSync);
    }

    /**
     * @param CustomerSyncInterface $customerSync
     */
    public function deleteCustomerEntry(CustomerSyncInterface $customerSync)
    {
        $customerSync->getResource()->delete($customerSync);
    }

    /**
     * @param int $id
     * @throws NoSuchEntityException
     */
    public function deleteCustomerEntryById(int $id)
    {
        /** @var CustomerSyncInterface $orderSync */
        $customerSync = $this->customerSyncFactory->create();
        $customerSync->getResource()->load($customerSync, $id);

        if (!$customerSync->getId()) {
            throw new NoSuchEntityException(__('CustomerSync entry with an id of ' . $id . 'does not exist.'));
        }

        $this->deleteCustomerEntry($customerSync);
    }

    /**
     * @param $customerId
     * @return Sync\Customer
     */
    protected function _getByCustomerId($customerId)
    {
        /**
         * @var \Lyonscg\SalesPad\Model\Sync\Customer $customerSync
         */
        $customerSync = $this->customerSyncFactory->create();
        $customerSync->load($customerId, 'customer_id');
        return $customerSync;
    }

    /**
     * @param $customerId
     * @return Sync\Customer
     * @throws NoSuchEntityException
     */
    public function getByCustomerId($customerId)
    {
        $customerSync = $this->_getByCustomerId($customerId);
        if (!$customerSync->getId()) {
            throw NoSuchEntityException::singleField('customerId', $customerId);
        } else {
            return $customerSync;
        }
    }

    /**
     * @param OrderInterface $order
     * @return $this
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function addOrder(OrderInterface $order)
    {
        /** @var \Lyonscg\SalesPad\Model\Sync\Order $orderSync */
        $orderSync = $this->orderSyncFactory->create();
        $orderSync->setOrderId($order->getId())
            ->setStoreId($order->getStoreId())
            ->setSyncAttempts(0)
            ->setLastSyncAttemptAt(null);
        $this->saveOrderEntry($orderSync);
        return $this;
    }

    /**
     * @param OrderSyncInterface $orderSync
     */
    public function saveOrderEntry(OrderSyncInterface $orderSync)
    {
        $orderSync->getResource()->save($orderSync);
    }

    /**
     * @param OrderSyncInterface $orderSync
     */
    public function deleteOrderEntry(OrderSyncInterface $orderSync)
    {
        $orderSync->getResource()->delete($orderSync);
    }

    /**
     * @param int $id
     * @throws NoSuchEntityException
     */
    public function deleteOrderEntryById(int $id)
    {
        /** @var OrderSyncInterface $orderSync */
        $orderSync = $this->orderSyncFactory->create();
        $orderSync->getResource()->load($orderSync, $id);

        if (!$orderSync->getId()) {
            throw new NoSuchEntityException(__('OrderSync entry with an id of ' . $id . 'does not exist.'));
        }

        $this->deleteOrderEntry($orderSync);
    }

    /**
     * @param $limit
     * @param $storeId
     * @return int[]
     */
    public function getCustomerIdsToSync($limit, $storeId = null)
    {
        /** @var $collection \Lyonscg\SalesPad\Model\ResourceModel\Sync\Customer\Collection */
        $collection = $this->customerCollectionFactory->create();

        if ($storeId !== null) {
            $collection->addFieldToFilter('store_id', $storeId);
        }

        return $collection->getCustomerIds($limit);
    }

    /**
     * @param $limit
     * @param $storeId
     * @return \Lyonscg\SalesPad\Api\Data\CustomerSyncInterface[]
     */
    public function getCustomersToSync($limit, $storeId = null)
    {
        $limit = intval($limit);
        /** @var $collection \Lyonscg\SalesPad\Model\ResourceModel\Sync\Customer\Collection */
        $collection = $this->customerCollectionFactory->create();

        if ($storeId !== null) {
            $collection->addFieldToFilter('store_id', $storeId);
        }

        if ($limit && $limit > 0) {
            $collection->getSelect()->limit($limit);
        }
        $collection->setOrder('sync_attempts', $collection::SORT_ORDER_ASC);
        $collection->setOrder('sync_id', $collection::SORT_ORDER_ASC);
        return $collection->getItems();
    }

    /**
     * @param $limit
     * @param $storeId
     * @return int[]
     */
    public function getOrderIdsToSync($limit, $storeId = null)
    {
        /** @var $collection \Lyonscg\SalesPad\Model\ResourceModel\Sync\Order\Collection */
        $collection = $this->orderCollectionFactory->create();

        if ($storeId !== null) {
            $collection->addFieldToFilter('main_table.store_id', $storeId);
        }

        return $collection->getOrderIds($limit);
    }

    /**
     * @param $limit
     * @param $storeId
     * @return \Lyonscg\SalesPad\Api\Data\OrderSyncInterface[]
     */
    public function getOrdersToSync($limit, $storeId = null)
    {
        $limit = intval($limit);
        /** @var $collection \Lyonscg\SalesPad\Model\ResourceModel\Sync\Order\Collection */
        $collection = $this->orderCollectionFactory->create();

        if ($storeId !== null) {
            $collection->addFieldToFilter('main_table.store_id', $storeId);
        }

        if ($limit && $limit > 0) {
            $collection->getSelect()->limit($limit);
        }
        $collection->setOrder('sync_attempts', $collection::SORT_ORDER_ASC);
        $collection->setOrder('sync_id', $collection::SORT_ORDER_ASC);
        return $collection->getItems();
    }

    /**
     * @param $quoteId
     * @param $action
     * @return \Lyonscg\SalesPad\Api\Data\QuoteSyncInterface

     */
    protected function _getByQuoteId($quoteId, $action)
    {
        /** @var \Lyonscg\SalesPad\Model\ResourceModel\Sync\Quote\Collection $collection */
        $collection = $this->quoteCollectionFactory->create();
        $collection->addFieldToFilter('quote_id', ['eq' => $quoteId])
                   ->addFieldToFilter('sync_action', ['eq' => $action]);
        return $collection->load()->getFirstItem();
    }

    /**
     * @param $quoteItemId
     * @param $action
     * @return \Lyonscg\SalesPad\Api\Data\QuoteItemSyncInterface
     */
    protected function _getByQuoteItemId($quoteItemId, $action)
    {
        /** @var \Lyonscg\SalesPad\Model\ResourceModel\Sync\Quote\Collection $collection */
        $collection = $this->quoteItemCollectionFactory->create();
        $collection->addFieldToFilter('item_id', ['eq' => $quoteItemId])
                   ->addFieldToFilter('sync_action', ['eq' => $action]);
        return $collection->load()->getFirstItem();
    }

    public function addRequisitionList(RequisitionListInterface $list)
    {
        $quoteSync = $this->_getByQuoteId($list->getId(), AbstractSyncInterface::ACTION_SEND);
        if (!$quoteSync->getId()) {
            $quoteSync->setQuoteId($list->getId())
                ->setCustomerId($list->getCustomerId())
                ->setStoreId($list->getExtensionAttributes()->getStoreId())
                ->setSyncAttempts(0)
                ->setLastSyncAttemptAt(null)
                ->setSyncAction(AbstractSyncInterface::ACTION_SEND);
            $this->saveQuoteEntry($quoteSync);
        }
        return $quoteSync;
    }

    public function addRequisitionListForDelete(RequisitionListInterface $list)
    {
        $salesDocNum = $this->quoteHelper->getSalesDocNum($list);
        if (!$salesDocNum) {
            // no sales doc num, so it was never synced, so no need to delete
            return null;
        }
        $quoteSync = $this->_getByQuoteId($list->getId(), AbstractSyncInterface::ACTION_DELETE);
        if (!$quoteSync->getId()) {
            $quoteSync->setQuoteId($list->getId())
                ->setCustomerId($list->getCustomerId())
                ->setStoreId($list->getExtensionAttributes()->getStoreId())
                ->setSyncAttempts(0)
                ->setLastSyncAttemptAt(null)
                ->setSalespadSalesDocNum($salesDocNum)
                ->setSyncAction(AbstractSyncInterface::ACTION_DELETE);
            $this->saveQuoteEntry($quoteSync);
        }
        return $quoteSync;
    }

    public function saveQuoteEntry(QuoteSyncInterface $quoteSync)
    {
        $quoteSync->getResource()->save($quoteSync);
    }

    public function deleteQuoteEntry(QuoteSyncInterface $quoteSync)
    {
        $quoteSync->getResource()->delete($quoteSync);
    }

    /**
     * @param int $id
     * @throws NoSuchEntityException
     */
    public function deleteQuoteEntryById(int $id)
    {
        /** @var QuoteSyncInterface $orderSync */
        $quoteSync = $this->quoteSyncFactory->create();
        $quoteSync->getResource()->load($quoteSync, $id);

        if (!$quoteSync->getId()) {
            throw new NoSuchEntityException(__('QuoteSync entry with an id of ' . $id . 'does not exist.'));
        }

        $this->deleteQuoteEntry($quoteSync);
    }

    public function getQuoteIdsToSync($limit, $storeId = null)
    {
        /** @var $collection \Lyonscg\SalesPad\Model\ResourceModel\Sync\Quote\Collection */
        $collection = $this->quoteCollectionFactory->create();

        if ($storeId !== null) {
            $collection->addFieldToFilter('store_id', $storeId);
        }

        return $collection->getQuoteIds($limit);
    }

    public function getQuotesToSync($limit = null, $storeId = null, $customerId = null)
    {
        $limit = intval($limit);
        /** @var $collection \Lyonscg\SalesPad\Model\ResourceModel\Sync\Quote\Collection */
        $collection = $this->quoteCollectionFactory->create();

        if ($storeId !== null) {
            $collection->addFieldToFilter('store_id', $storeId);
        }

        if ($customerId !== null) {
            $collection->addFieldToFilter('customer_id', $customerId);
        }

        if ($limit && $limit > 0) {
            $collection->getSelect()->limit($limit);
        }
        $collection->setOrder('sync_attempts', $collection::SORT_ORDER_ASC);
        $collection->setOrder('sync_id', $collection::SORT_ORDER_ASC);
        return $collection->getItems();
    }

    /**
     * @param $listId
     * @return QuoteSyncInterface|null
     */
    public function getQuoteToSyncByRequisitionListId($listId)
    {
        /** @var $collection \Lyonscg\SalesPad\Model\ResourceModel\Sync\Quote\Collection */
        $collection = $this->quoteCollectionFactory->create();
        $collection->addFieldToFilter('quote_id', ['eq' => $listId]);
        if ($collection->getSize()) {
            return $collection->getFirstItem();
        } else {
            return null;
        }
    }

    /**
     * @param $quoteId
     * @return QuoteItemSyncInterface[]
     */
    public function getQuoteItemsToSyncForQuoteId($quoteId)
    {
        /** @var $collection \Lyonscg\SalesPad\Model\ResourceModel\Sync\QuoteItem\Collection */
        $collection = $this->quoteItemCollectionFactory->create();
        $collection->addFieldToFilter('quote_id', ['eq' => $quoteId]);
        return $collection->getItems();
    }

    public function addRequisitionListItem(RequisitionListItemInterface $item)
    {
        $quoteItemSync = $this->_getByQuoteItemId($item->getId(), AbstractSyncInterface::ACTION_SEND);
        if (!$quoteItemSync->getId()) {
            $quoteItemSync->setQuoteId($item->getRequisitionListId())
                ->setItemId($item->getId())
                ->setSyncAttempts(0)
                ->setLastSyncAttemptAt(null)
                ->setSyncAction(AbstractSyncInterface::ACTION_SEND);
            $this->saveQuoteItemEntry($quoteItemSync);
        }
        return $quoteItemSync;
    }

    public function addRequisitionListItemForDelete(RequisitionListItemInterface $item)
    {
        $quoteItemSync = $this->_getByQuoteItemId($item->getId(), AbstractSyncInterface::ACTION_DELETE);
        if (!$quoteItemSync->getId()) {
            $salesDocNum = $this->quoteHelper->getItemSalesDocNum($item);
            if ($salesDocNum === null) {
                return null;
            }
            $quoteItemSync->setSalesDocNum($salesDocNum);

            $lineNum = $this->quoteHelper->getItemLineNum($item);
            if ($lineNum === null) {
                return null;
            }
            $quoteItemSync->setLineNum($lineNum);

            $componentSeqNum = $this->quoteHelper->getItemComponentSeqNum($item);
            if ($componentSeqNum === null) {
                return null;
            }
            $quoteItemSync->setComponentSeqNum($componentSeqNum);

            $quoteItemSync->setQuoteId($item->getRequisitionListId())
                ->setItemId($item->getId())
                ->setSyncAttempts(0)
                ->setLastSyncAttemptAt(null)
                ->setSyncAction(AbstractSyncInterface::ACTION_DELETE);

            $this->saveQuoteItemEntry($quoteItemSync);
        }
        return $quoteItemSync;
    }

    public function saveQuoteItemEntry(QuoteItemSyncInterface $quoteItemSync)
    {
        $quoteItemSync->getResource()->save($quoteItemSync);
    }

    public function deleteQuoteItemEntry(QuoteItemSyncInterface $quoteItemSync)
    {
        $quoteItemSync->getResource()->delete($quoteItemSync);
    }

    public function getQuoteItemIdsToSync($limit)
    {
        /** @var $collection \Lyonscg\SalesPad\Model\ResourceModel\Sync\QuoteItem\Collection */
        $collection = $this->quoteItemCollectionFactory->create();
        return $collection->getItemIds($limit);
    }

    public function getQuoteItemsToSync($limit)
    {
        $limit = intval($limit);
        /** @var $collection \Lyonscg\SalesPad\Model\ResourceModel\Sync\QuoteItem\Collection */
        $collection = $this->quoteItemCollectionFactory->create();
        if ($limit && $limit > 0) {
            $collection->getSelect()->limit($limit);
        }
        $collection->setOrder('sync_attempts', $collection::SORT_ORDER_ASC);
        $collection->setOrder('sync_id', $collection::SORT_ORDER_ASC);
        return $collection->getItems();
    }

    public function hasItemBeenDeleted($salesDocNum, $lineNum, $componentSeqNum)
    {
        /** @var $collection \Lyonscg\SalesPad\Model\ResourceModel\Sync\QuoteItem\Collection */
        $collection = $this->quoteItemCollectionFactory->create();

        $collection->addFieldToFilter('salespad_sales_doc_num', $salesDocNum);
        $collection->addFieldToFilter('salespad_line_num', $lineNum);
        $collection->addFieldToFilter('salespad_component_seq_num', $componentSeqNum);
        $collection->addFieldToFilter('sync_action', AbstractSyncInterface::ACTION_DELETE);

        $item = $collection->getFirstItem();
        return $item->getId() && $item->getSyncAction() === AbstractSyncInterface::ACTION_DELETE;
    }
}
