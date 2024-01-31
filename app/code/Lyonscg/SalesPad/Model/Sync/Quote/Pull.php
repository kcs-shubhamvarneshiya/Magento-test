<?php

namespace Lyonscg\SalesPad\Model\Sync\Quote;

use Lyonscg\SalesPad\Model\Api\Logger;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\App\RequestInterface;

class Pull
{
    const SALES_DOC_NUM = 'Sales_Doc_Num';

    const PROJECT_NAME = 'xJob_Name';

    const ITEM_NUMBER = 'Item_Number';

    const QUANTITY = 'Quantity';

    const LINE_NUM = 'Line_Num';

    const COMPONENT_SEQ_NUM = 'Component_Seq_Num';

    const SIDEMARK = 'xSidemark';

    const COMMENT = 'Comment';

    const SOURCE = 'Source';

    const DELETED_FILTER = [
        [
            "field" => "Source",
            "condition" => "eq",    //equal
            "value" => "Open"       //exists
        ]
    ];

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var \Lyonscg\SalesPad\Helper\Customer
     */
    protected $customerHelper;

    /**
     * @var \Magento\RequisitionList\Api\RequisitionListRepositoryInterface
     */
    protected $requisitionListRepository;

    /**
     * @var \Magento\RequisitionList\Model\RequisitionList\Items
     */
    protected $requisitionListItemRepository;

    /**
     * @var \Magento\RequisitionList\Api\Data\RequisitionListInterfaceFactory
     */
    protected $requisitionListFactory;

    /**
     * @var \Magento\RequisitionList\Api\Data\RequisitionListItemInterfaceFactory
     */
    protected $requisitionListItemFactory;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable
     */
    protected $configurableType;

    /**
     * @var \Lyonscg\SalesPad\Model\Api\SalesPad\SalesDocument
     */
    protected $salesDocument;

    /**
     * @var \Lyonscg\SalesPad\Model\Api\SalesPad\SalesLineItem
     */
    protected $salesLineItem;

    /**
     * @var \Magento\RequisitionList\Model\RequisitionListItem\Options\Builder
     */
    protected $optionsBuilder;

    /**
     * @var \Magento\RequisitionList\Model\RequisitionListItem\Locator
     */
    protected $requisitionListItemLocator;

    /**
     * @var \Magento\RequisitionList\Api\RequisitionListManagementInterface
     */
    protected $requisitionListManagement;

    /**
     * @var \Magento\RequisitionList\Model\RequisitionListProduct
     */
    protected $requisitionListProduct;

    /**
     * @var \Lyonscg\SalesPad\Helper\Quote
     */
    protected $quoteHelper;

    /**
     * @var \Lyonscg\SalesPad\Api\SyncRepositoryInterface
     */
    protected $syncRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @var \Magento\Framework\Api\Search\FilterGroupBuilder
     */
    protected $filterGroupBuilder;

    /**
     * @var \Lyonscg\SalesPad\Api\DeletedQuoteRepositoryInterface
     */
    protected $deletedQuoteRepository;

    /**
     * @var \Lyonscg\SalesPad\Helper\Email
     */
    protected $emailHelper;

    // state variables

    /**
     * @var null|\Magento\Customer\Model\Customer
     */
    protected $customer;

    /**
     * @var string|false
     */
    protected $customerNum;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * Pull constructor.
     * @param Logger $logger
     * @param \Lyonscg\SalesPad\Helper\Customer $customerHelper
     * @param \Magento\RequisitionList\Api\RequisitionListRepositoryInterface $requisitionListRepository
     * @param \Magento\RequisitionList\Model\RequisitionList\Items $requisitionListItemRepository
     * @param \Magento\RequisitionList\Api\Data\RequisitionListInterfaceFactory $requisitionListFactory
     * @param \Magento\RequisitionList\Api\Data\RequisitionListItemInterfaceFactory $requisitionListItemFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableType
     * @param \Lyonscg\SalesPad\Model\Api\SalesPad\SalesDocument $salesDocument
     * @param \Lyonscg\SalesPad\Model\Api\SalesPad\SalesLineItem $salesLineItem
     * @param \Magento\RequisitionList\Model\RequisitionListItem\Options\Builder $optionsBuilder
     * @param \Magento\RequisitionList\Model\RequisitionListItem\Locator $requisitionListItemLocator
     * @param \Magento\RequisitionList\Api\RequisitionListManagementInterface $requisitionListManagement
     * @param \Magento\RequisitionList\Model\RequisitionListProduct $requisitionListProduct
     * @param \Lyonscg\SalesPad\Helper\Quote $quoteHelper
     * @param \Lyonscg\SalesPad\Api\SyncRepositoryInterface $syncRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     * @param \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder
     * @param \Lyonscg\SalesPad\Api\DeletedQuoteRepositoryInterface $deletedQuoteRepository
     * @param \Lyonscg\SalesPad\Helper\Email $emailHelper
     */
    public function __construct(
        Logger $logger,
        \Lyonscg\SalesPad\Helper\Customer $customerHelper,
        \Magento\RequisitionList\Api\RequisitionListRepositoryInterface $requisitionListRepository,
        \Magento\RequisitionList\Model\RequisitionList\Items $requisitionListItemRepository,
        \Magento\RequisitionList\Api\Data\RequisitionListInterfaceFactory $requisitionListFactory,
        \Magento\RequisitionList\Api\Data\RequisitionListItemInterfaceFactory $requisitionListItemFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableType,
        \Lyonscg\SalesPad\Model\Api\SalesPad\SalesDocument $salesDocument,
        \Lyonscg\SalesPad\Model\Api\SalesPad\SalesLineItem $salesLineItem,
        \Magento\RequisitionList\Model\RequisitionListItem\Options\Builder $optionsBuilder,
        \Magento\RequisitionList\Model\RequisitionListItem\Locator $requisitionListItemLocator,
        \Magento\RequisitionList\Api\RequisitionListManagementInterface $requisitionListManagement,
        \Magento\RequisitionList\Model\RequisitionListProduct $requisitionListProduct,
        \Lyonscg\SalesPad\Helper\Quote $quoteHelper,
        \Lyonscg\SalesPad\Api\SyncRepositoryInterface $syncRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder,
        \Lyonscg\SalesPad\Api\DeletedQuoteRepositoryInterface $deletedQuoteRepository,
        \Lyonscg\SalesPad\Helper\Email $emailHelper,
        RequestInterface $request
    ) {
        $this->logger = $logger;
        $this->customerHelper = $customerHelper;
        $this->requisitionListRepository = $requisitionListRepository;
        $this->requisitionListItemRepository = $requisitionListItemRepository;
        $this->requisitionListFactory = $requisitionListFactory;
        $this->requisitionListItemFactory = $requisitionListItemFactory;
        $this->productRepository = $productRepository;
        $this->configurableType = $configurableType;
        $this->salesDocument = $salesDocument;
        $this->salesLineItem = $salesLineItem;
        $this->optionsBuilder = $optionsBuilder;
        $this->requisitionListItemLocator = $requisitionListItemLocator;
        $this->requisitionListManagement = $requisitionListManagement;
        $this->requisitionListProduct = $requisitionListProduct;
        $this->quoteHelper = $quoteHelper;
        $this->syncRepository = $syncRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->deletedQuoteRepository = $deletedQuoteRepository;
        $this->emailHelper = $emailHelper;
        $this->request = $request;

        $this->customer = null;
        $this->customerNum = false;
    }

    /**
     * Pull quotes for customer
     */
    public function execute(\Magento\Customer\Api\Data\CustomerInterface $customer)
    {
        $customerNum = $this->customerHelper->getCustomerNum($customer->getId());
        if (!$customerNum) {
            return;
        }

        $this->customer = $customer;
        $this->customerNum = $customerNum;

        $skipUpdatesFromSalesPad = [];

        $this->_deleteMagentoQuotes();

        // sync any non-synced quotes for this customer
        $requisitionLists = $this->getRequisitionListsForCustomer($customerNum);
        foreach ($requisitionLists->getItems() as $requisitionList) {
            /** @var \Magento\RequisitionList\Api\Data\RequisitionListInterface $requisitionList */
            $quoteSync = $this->syncRepository->getQuoteToSyncByRequisitionListId($requisitionList->getId());
            if ($quoteSync) {
                if (!$quoteSync->canSync()) {

                    continue;
                }

                $quoteNum = $this->quoteHelper->getSalesDocNum($requisitionList);
                if (!$this->_syncQuoteAndItems($quoteSync, $quoteNum)) {
                    // failed to sync our changes, so don't update this quote from SalesPad yet
                    $skipUpdatesFromSalesPad[] = $quoteNum;
                }
            }
        }

        // we have a customer, now check SalesPad to see if we have quotes
        try {
            $quoteData = $this->_getQuotesFromSalesPad();

            foreach ($quoteData as $quote) {
                $quoteNum = trim($quote[self::SALES_DOC_NUM]);
                if (in_array($quoteNum, $skipUpdatesFromSalesPad)) {
                    $this->logger->debug("Skipping quote pull for $quoteNum because it has not synced yet");
                } else {
                    try {
                        $this->_processSalesPadQuote($quote);
                    } catch (\Exception $e) {
                        $this->logger->debug("Exception while processing quote data: " . $e->getMessage() . "\n\n" . $e->getTraceAsString());
                    }
                }
            }
        } catch (\Exception $e) {
            $this->logger->debug("Exception while pulling quote data: " . $e->getMessage() . "\n\n" . $e->getTraceAsString());
        } finally {
            $this->customer = null;
            $this->customerNum = false;
        }
    }

    /**
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @return bool
     * @throws NoSuchEntityException
     */
    public function executeRequisitionListView(\Magento\Customer\Api\Data\CustomerInterface $customer)
    {
        $customerNum = $this->customerHelper->getCustomerNum($customer->getId());
        if (!$customerNum) {
            return false;
        }

        $this->customer = $customer;
        $this->customerNum = $customerNum;

        $quoteId = $this->request->getParam('requisition_id');
        if (!empty($quoteId)) {
            $quote = $this->requisitionListRepository->get($quoteId);
            $quoteNum = $this->quoteHelper->getSalesDocNum($quote);
            $quoteSync = $this->syncRepository->getQuoteToSyncByRequisitionListId($quoteId);
            if ($this->_syncQuoteAndItems($quoteSync, $quoteNum)) {
                $spQuopte = $this->salesDocument->getBySalesDocId($this->salesDocument::TYPE_QUOTE, $quoteNum);
                $this->_processSalesPadQuote($spQuopte);
            } else {

                return false;
            }
        }

        return true;
    }

    /**
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @return bool
     * @throws NoSuchEntityException
     */
    public function executeRequisitionListIndex(\Magento\Customer\Api\Data\CustomerInterface $customer)
    {
        $customerNum = $this->customerHelper->getCustomerNum($customer->getId());
        if (!$customerNum) {
            return false;
        }

        $this->customer = $customer;
        $this->customerNum = $customerNum;

        $quotesToSync = $this->syncRepository->getQuotesToSync(null, null, $customer->getId());
        foreach ($quotesToSync as $quoteSync) {
            if (!$quoteSync->canSync()) {
                continue;
            }

            $this->_syncQuote($quoteSync);
            $quote = $this->requisitionListRepository->get($quoteSync->getQuoteId());
            $salespadSalesDocNum = $this->quoteHelper->getSalesDocNum($quote);
            $quoteItemsToSync = $this->syncRepository->getQuoteItemsToSyncForQuoteId($quoteSync->getQuoteId());
            foreach ($quoteItemsToSync as $quoteItemSync) {
                if (!$quoteItemSync->canSync()) {

                    continue;
                }

                $quoteItemSync->setSalesDocNum($salespadSalesDocNum);
                $failed = false;
                try {
                    if (!$quoteItemSync->sync()) {
                        $failed = true;
                    }
                } catch (NoSuchEntityException $e) {
                    $this->logger->debug(__('Quote Item %1 does not exist', $quoteItemSync->getItemId()));
                } catch (\Exception $e) {
                    $this->logger->debug($e);
                    $failed = true;
                }
                if ($failed) {
                    $quoteItemSync->setSyncAttempts($quoteItemSync->getSyncAttempts() + 1);
                    $this->syncRepository->saveQuoteItemEntry($quoteItemSync);
                } else {
                    $this->syncRepository->deleteQuoteItemEntry($quoteItemSync);
                }
            }
        }
        return true;
    }

    protected function _getQuotesFromSalesPad()
    {
        $quotes = $this->salesDocument->getByCustomerNum(
            $this->salesDocument::TYPE_QUOTE,
            $this->customerNum, 0, 1,
            self::DELETED_FILTER
        );
        $quotes = $quotes['items'] ?? [];
        $result = [];
        foreach ($quotes as $quoteData) {
            // CLMI-902 - if we have deleted the quote already, don't re-create it
            if (!$this->deletedQuoteRepository->existsBySalesDocNum($quoteData[self::SALES_DOC_NUM])) {
                $result[] = $quoteData;
            }
        }
        return $result;
    }

    protected function _getSalesPadQuoteItems($salesDocNum)
    {
        $items = $this->salesLineItem->search($this->salesLineItem::TYPE_QUOTE, $salesDocNum);
        return $items['Items'] ?? [];
    }

    protected function _processSalesPadQuote(array $quote)
    {
        $salesDocNum = trim($quote[self::SALES_DOC_NUM]);
        $items = $this->_getSalesPadQuoteItems($salesDocNum);
        $magentoQuote = $this->_getMagentoQuote($salesDocNum);
        if (!$magentoQuote) {
            $this->_createQuoteInMagento($quote, $items);
        } else {
            $this->_updateMagentoQuote($magentoQuote, $quote, $items);
        }
    }

    protected function _getMagentoQuote($salesDocNum)
    {
        $list = $this->quoteHelper->getListBySalesDocNum($salesDocNum, $this->customerNum);
        if (!$list || !$list->getId()) {
            return false;
        } else {
            return $list;
        }
    }

    protected function _createQuoteInMagento(array $quote, array $items)
    {
        $salesDocNum = trim($quote[self::SALES_DOC_NUM]);
        // create requisition list
        /** @var \Magento\RequisitionList\Api\Data\RequisitionListInterface $list */
        $list = $this->requisitionListFactory->create();
        $list->setName(trim($this->_getUserFieldData($quote, self::PROJECT_NAME)));
        $list->setDescription(trim($this->_getUserFieldData($quote, 'xWebOrderNotes')));
        $list->setCustomerId($this->customer->getId());
        // don't need to sync list to SalesPad we just pulled from SalesPad
        $list->setData('__no_sync', true);
        $this->quoteHelper->setSalesDocNum($list, $salesDocNum);
        $list->setSalespadSalesDocNum($salesDocNum);
        $list->setSalesPadCustomerNum($this->customerNum);
        $list->setPoNumber(trim($quote['Customer_PO_Num']));

        $this->requisitionListRepository->save($list);

        $listItems = [];

        // add items to it
        foreach ($items as $itemData) {

            $listItems[] = $this->_createRequisitionListItem($itemData);
        }
        $this->requisitionListManagement->setItemsToList($list, $listItems);
        $this->requisitionListRepository->save($list);

        return $list;
    }

    public function _createRequisitionListItem($itemData)
    {
        /** @var \Magento\RequisitionList\Api\Data\RequisitionListItemInterface $listItem */
        $qty = intval(trim($itemData[self::QUANTITY]));
        $sku = trim($itemData[self::ITEM_NUMBER]);
        $salesDocNum = trim($itemData[self::SALES_DOC_NUM]);
        $lineNum = trim($itemData[self::LINE_NUM] ?? '');
        $componentSeqNum = trim($itemData[self::COMPONENT_SEQ_NUM] ?? '');

        if ($this->syncRepository->hasItemBeenDeleted($salesDocNum, $lineNum, $componentSeqNum)) {
            // item has been deleted, but has not synced yet, so don't re-create it
            return null;
        }

        $sidemark = trim($this->_getUserFieldData($itemData, self::SIDEMARK));
        $commentsLineItem = trim($itemData[self::COMMENT] ?? '');
        if (!$qty) {
            throw new \Exception("No quantity specified for item: $sku");
        }

        $productData = $this->_getProductData($sku, $qty);
        if (!$productData) {
            // no options
            throw new \Exception("Cannot find configurable options for: $sku");
        }
        $options = $productData->getOptions();

        $itemId = 0;
        $itemOptions = $this->optionsBuilder->build($options, $itemId, false);
        $item = $this->requisitionListItemLocator->getItem($itemId);
        $item->setQty($qty);
        $item->setOptions($itemOptions);
        $item->setSku($productData->getSku());
        $this->quoteHelper->setItemData($item, $salesDocNum, $lineNum, $componentSeqNum);
        $item->setSalespadSalesDocNum($salesDocNum);
        $item->setSalespadLineNum($lineNum);
        $item->setSalespadComponentSeqNum($componentSeqNum);
        $item->setSidemark($sidemark);
        $item->setCommentsLineItem($commentsLineItem);
        // don't need to sync items either
        $item->setData('__no_sync', true);
        return $item;
    }

    /**
     * @param $requisitionList \Magento\RequisitionList\Api\Data\RequisitionListInterface
     * @param $sku
     */
    protected function _getItemFromListBySku($requisitionList, $sku)
    {
        $sku = strtolower(trim($sku));
        foreach ($requisitionList->getItems() as $listItem) {
            // get the child sku from the item, if it's a child item
            $itemSku = strtolower($this->quoteHelper->getItemSku($listItem));
            if ($itemSku === $sku) {
                return $listItem;
            }
        }
        return false;
    }

    /**
     * @param \Magento\RequisitionList\Api\Data\RequisitionListInterface $magentoQuote
     * @param array $quote
     * @param array $salesPadItems
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    protected function _updateMagentoQuote($magentoQuote, array $quote, array $salesPadItems)
    {
        $updated = false;
        $magentoQuote->setData('__no_sync', true);
        $items = $magentoQuote->getItems();
        foreach ($items as $item) {
            // make sure we don't sync any items that we update in this way
            $item->setData('__no_sync', true);
        }

        $description = trim($this->_getUserFieldData($quote, 'xWebOrderNotes'));
        $poNumber = trim($quote['Customer_PO_Num']);
        $name = trim($this->_getUserFieldData($quote, self::PROJECT_NAME));

        if ($description != $magentoQuote->getDescription()) {
            $updated = true;
            $magentoQuote->setDescription($description);
        }
        if ($poNumber != $this->quoteHelper->getPoNumber($magentoQuote)) {
            $updated = true;
            $this->quoteHelper->setPoNumber($magentoQuote, $poNumber);
        }
        if ($name != $magentoQuote->getName()) {
            $updated = true;
            $magentoQuote->setName($name);
        }

        $itemsToDelete = $this->_getItemsToDelete($items, $salesPadItems);

        $newItems = [];
        foreach ($salesPadItems as $itemData) {
            $sku = trim($itemData[self::ITEM_NUMBER]);
            try {
                $listItem = $this->_getItemFromListBySku($magentoQuote, $sku);
                if (!$listItem) {
                    $listItem = $this->_createRequisitionListItem($itemData);
                    $newItems[] = $listItem;
                    $updated = true;
                } else {
                    if ($this->_updateRequisitionListItem($listItem, $itemData)) {
                        $updated = true;
                    }
                }
            } catch (\Exception $e) {
                $this->logger->debug(
                    sprintf("Processing requisition list item error.
                                    sku: %s,
                                    Sales_Doc_Num: %s,
                                    Magento quote_id: %s,
                                    salespad_customer_num: %s,
                                    salespad_sales_doc_num: %s,
                                    Magento customer_id: %s",
                    $sku,
                        $itemData['Sales_Doc_Num'] ?? "",
                        $magentoQuote->getId(),
                        $magentoQuote->getSalesPadCustomerNum(),
                        $magentoQuote->getSalespadSalesDocNum(),
                        $magentoQuote->getCustomerId()
                    )
                );
            }
        }
        $items = array_merge($items, $newItems);
        if ($updated) {
            $magentoQuote->setItems($items);
            $this->requisitionListRepository->save($magentoQuote);
        }

        // Delete items that were removed by SalesPad
        foreach ($itemsToDelete as $item) {
            try {
                $item->setData('__no_sync', true);
                $this->requisitionListItemRepository->delete($item);
            } catch (\Exception $e) {
                $this->logger->debug('Error deleting quote item: ' . $item->getId() . ':' . $e->getMessage() . "\n\n" . $e->getTraceAsString());
            }
        }
    }

    /**
     * @param \Magento\RequisitionList\Api\Data\RequisitionListInterface $magentoQuote
     * @param array $quote
     * @param array $salesPadItems
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    protected function _updateMagentoQuoteItems($magentoQuote, array $salesPadItems)
    {
        $updated = false;
        $items = $magentoQuote->getItems();
        foreach ($items as $item) {
            // make sure we don't sync any items that we update in this way
            $item->setData('__no_sync', true);
        }

        $itemsToDelete = $this->_getItemsToDelete($items, $salesPadItems);

        $newItems = [];
        foreach ($salesPadItems as $itemData) {
            $sku = trim($itemData[self::ITEM_NUMBER]);
            try {
                $listItem = $this->_getItemFromListBySku($magentoQuote, $sku);
                if (!$listItem) {
                    $listItem = $this->_createRequisitionListItem($itemData);
                    $newItems[] = $listItem;
                    $updated = true;
                } else {
                    if ($this->_updateRequisitionListItem($listItem, $itemData)) {
                        $updated = true;
                    }
                }
            } catch (\Exception $e) {
                $this->logger->debug(
                    sprintf("Processing requisition list item error.
                                    sku: %s,
                                    Sales_Doc_Num: %s,
                                    Magento quote_id: %s,
                                    salespad_customer_num: %s,
                                    salespad_sales_doc_num: %s,
                                    Magento customer_id: %s",
                        $sku,
                        $itemData['Sales_Doc_Num'] ?? "",
                        $magentoQuote->getId(),
                        $magentoQuote->getSalesPadCustomerNum(),
                        $magentoQuote->getSalespadSalesDocNum(),
                        $magentoQuote->getCustomerId()
                    )
                );
            }
        }
        $items = array_merge($items, $newItems);
        if ($updated) {
            $magentoQuote->setItems($items);
            $this->requisitionListRepository->save($magentoQuote);
        }

        // Delete items that were removed by SalesPad
        foreach ($itemsToDelete as $item) {
            try {
                $item->setData('__no_sync', true);
                $this->requisitionListItemRepository->delete($item);
            } catch (\Exception $e) {
                $this->logger->debug('Error deleting quote item: ' . $item->getId() . ':' . $e->getMessage() . "\n\n" . $e->getTraceAsString());
            }
        }
    }

    protected function _getItemsToDelete(array $quoteItems, array $salesPadItems)
    {
        $keptItems = [];
        foreach ($salesPadItems as $salesPadItem) {
            $num = trim($salesPadItem[self::SALES_DOC_NUM]);
            $line = trim($salesPadItem[self::LINE_NUM]);
            $seq = trim($salesPadItem[self::COMPONENT_SEQ_NUM]);
            $keptItems[] = $num . '-' . $line . '-' . $seq;
        }
        $itemsToDelete = [];
        foreach ($quoteItems as $item) {
            /** @var \Magento\RequisitionList\Api\Data\RequisitionListItemInterface $item */
            $itemNum = $this->quoteHelper->getItemSalesDocNum($item);
            $itemLine = $this->quoteHelper->getItemLineNum($item);
            $itemSeq = $this->quoteHelper->getItemComponentSeqNum($item);
            if ($itemLine === '' || $itemLine === null || $itemSeq === '' || $itemSeq === null) {
                // don't delete items that have not been synced
                continue;
            }
            $key = $itemNum . '-' . $itemLine . '-' . $itemSeq;
            if (!in_array($key, $keptItems)) {
                $itemsToDelete[] = $item;
            }
        }
        return $itemsToDelete;
    }

    protected function _updateRequisitionListItem($item, array $itemData)
    {
        /** @var \Magento\RequisitionList\Api\Data\RequisitionListItemInterface $item */
        $qty = intval(trim($itemData[self::QUANTITY]));
        $sku = trim($itemData[self::ITEM_NUMBER]);
        $salesDocNum = trim($itemData[self::SALES_DOC_NUM]);
        $lineNum = trim($itemData[self::LINE_NUM] ?? '');
        $componentSeqNum = trim($itemData[self::COMPONENT_SEQ_NUM] ?? '');
        $sidemark = trim($this->_getUserFieldData($itemData, self::SIDEMARK));
        $commentsLineItem = trim($itemData[self::COMMENT] ?? '');
        if (!$qty) {
            throw new \Exception("No quantity specified for item: $sku");
        }

        $update = false;
        if ($item->getQty() != $qty) {
            $item->setQty($qty);
            $update = true;
        }
        if ($this->quoteHelper->getItemSidemark($item) != $sidemark) {
            $item->setSidemark($sidemark);
            $update = true;
        }
        if ($this->quoteHelper->getItemComment($item) != $commentsLineItem) {
            $item->setCommentsLineItem($commentsLineItem);
            $update = true;
        }
        if ($this->quoteHelper->getItemLineNum($item) != $lineNum) {
            $item->setSalespadLineNum($lineNum);
            $update = true;
        }
        if ($this->quoteHelper->getItemComponentSeqNum($item) != $componentSeqNum) {
            $item->setSalespadComponentSeqNum($componentSeqNum);
            $update = true;
        }

        if ($update) {
            // update extension attributes if anything was changed
            $this->quoteHelper->setItemData($item, $salesDocNum, $lineNum, $componentSeqNum);
        }
        return $update;
    }

    protected function _deleteMagentoQuotes()
    {
        $quoteData = $this->_getQuotesFromSalesPad();
        $salesDocNums = [];

        foreach ($quoteData as $quote) {
            if (strtolower(trim($quote[self::SOURCE])) !== 'void') {
                $salesDocNums[] = trim($quote[self::SALES_DOC_NUM]);
            }
        }

        $magentoQuotes = $this->getRequisitionListsForCustomer($this->customerNum);
        foreach ($magentoQuotes->getItems() as $magentoQuote) {
            /** @var \Magento\RequisitionList\Api\Data\RequisitionListItemInterface $magentoQuote */
            $salesDocNum = $magentoQuote->getSalespadSalesDocNum();
            if (!$salesDocNum) {
                // quote not yet synced to SalesPad, so we don't delete this one
                continue;
            }
            if (!in_array($salesDocNum, $salesDocNums)) {
                $this->logger->debug('SalesPad Quote Pull: removing quote: ' . $magentoQuote->getId());
                $magentoQuote->setData('__no_sync', true);
                $this->requisitionListRepository->delete($magentoQuote);
            }
        }

    }

    public function _getUserFieldData(array $quote, $key)
    {
        $fields = $quote['UserFieldNames'] ?? [];
        $idx = -1;
        for ($i = 0; $i < count($fields); $i++) {
            if ($fields[$i] === $key) {
                $idx = $i;
                break;
            }
        }

        if ($idx === -1) {
            return '';
        }

        $data = $quote['UserFieldData'] ?? [];
        return $data[$idx] ?? '';
    }

    protected function _getProductData($sku, $qty)
    {
        // get child product
        $product = $this->productRepository->get($sku);
        if ($product->getTypeId() === \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            // should not see configurables as far as I know
            throw new \Exception("Configurable SKU specified in import: $sku");
        }
        // get parent
        $parentProduct = $this->_getParentProduct($product);
        if ($parentProduct === true) {
            // simple product that isn't part of a configurable, so no need for options
            return new \Magento\Framework\DataObject([
                'sku' => $sku,
                'options' => [
                    'product' => $product->getId(),
                    'related_product' => '',
                    'item' => $product->getId(),
                    'qty' => $qty
                ],
            ]);
        }

        // now we can figure out the options
        $options = [];
        foreach ($this->configurableType->getConfigurableAttributes($parentProduct) as $attribute) {
            $productAttribute = $attribute->getProductAttribute();
            $productAttributeId = $productAttribute->getId();
            $attributeValue = $product->getData($productAttribute->getAttributeCode());
            $options[$productAttributeId] = $attributeValue;
        }

        return new \Magento\Framework\DataObject([
            'sku' => $parentProduct->getSku(),
            'options' => [
                'product' => $parentProduct->getId(),
                'selected_configurable_option' => $product->getId(),
                'related_product' => '',
                'item' => $parentProduct->getId(),
                'super_attribute' => $options,
                'qty' => $qty
            ]
        ]);
    }

    protected function _getParentProduct(\Magento\Catalog\Api\Data\ProductInterface $childProduct)
    {
        try {
            return $this->productRepository->get($childProduct->getBasecode());
        } catch (\Throwable $e) {
            $parentIds = $this->configurableType->getParentIdsByChild($childProduct->getId());
            if (empty($parentIds)) {
                // no parents, naked simple product
                return true;
            }
            return $this->productRepository->getById($parentIds[0]);
        }
    }

    protected function getRequisitionListsForCustomer($customerNum)
    {
        if ($customerNum) {
            $filter = $this->filterBuilder
                ->setField('main_table.customer_id')
                ->setConditionType('eq')
                ->setValue($this->customer->getId())
                ->create();
            $spFilter = $this->filterBuilder
                ->setField('main_table.sales_pad_customer_num')
                ->setConditionType('eq')
                ->setValue($customerNum)
                ->create();
            $filterGroup = $this->filterGroupBuilder
                ->addFilter($filter)
                ->addFilter($spFilter)
                ->create();
            $this->searchCriteriaBuilder->setFilterGroups([$filterGroup]);
        } else {
            $this->searchCriteriaBuilder->addFilter('main_table.customer_id', $this->customer->getId());
        }
        $searchCriteria = $this->searchCriteriaBuilder->create();
        return $this->requisitionListRepository->getList($searchCriteria);
    }

    /**
     * @param \Lyonscg\SalesPad\Api\Data\QuoteSyncInterface $quoteSync
     */
    protected function _syncQuoteAndItems($quoteSync, $salesDocNum)
    {
        $quoteFailed = false;
        try {
            if ($quoteSync->sync()) {
                $this->syncRepository->deleteQuoteEntry($quoteSync);
                $quoteItemsToSync = $this->syncRepository->getQuoteItemsToSyncForQuoteId($quoteSync->getQuoteId());
                foreach ($quoteItemsToSync as $quoteItemSync) {
                    if (!$quoteItemSync->canSync()) {

                        continue;
                    }

                    $quoteItemSync->setSalesDocNum($salesDocNum);
                    $failed = false;
                    try {
                        if (!$quoteItemSync->sync()) {
                            $failed = true;
                        }
                    } catch (NoSuchEntityException $e) {
                        $this->logger->debug(__('Quote Item %1 does not exist', $quoteItemSync->getItemId()));
                    } catch (\Exception $e) {
                        $this->logger->debug($e);
                        $failed = true;
                    }

                    if ($failed) {
                        $quoteItemSync->setSyncAttempts($quoteItemSync->getSyncAttempts() + 1);
                        $this->syncRepository->saveQuoteItemEntry($quoteItemSync);
                    } else {
                        $this->syncRepository->deleteQuoteItemEntry($quoteItemSync);
                    }
                }
            } else {
                $quoteFailed = true;
            }
        } catch (\Exception $e) {
            $this->logger->debug($e);
            $quoteFailed = true;
        }

        if ($quoteFailed) {
            $this->logger->debug('Failed to sync quote: ' . $quoteSync->getId() . ' before pulling quote data');
            $quoteSync->setSyncAttempts($quoteSync->getSyncAttempts() + 1);
            $this->syncRepository->saveQuoteEntry($quoteSync);
            if ($quoteSync->getSyncAttempts() == 1) {
                $this->emailHelper->sendQuoteErrorEmail($quoteSync);
            }
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param \Lyonscg\SalesPad\Api\Data\QuoteSyncInterface $quoteSync
     */
    protected function _syncQuoteItems($quoteId, $salesDocNum)
    {
        $quoteFailed = false;
        try {
            $quoteItemsToSync = $this->syncRepository->getQuoteItemsToSyncForQuoteId($quoteId);
            foreach ($quoteItemsToSync as $quoteItemSync) {
                if (!$quoteItemSync->canSync()) {

                    continue;
                }

                $quoteItemSync->setSalesDocNum($salesDocNum);
                $failed = false;
                try {
                    if (!$quoteItemSync->sync()) {
                        $failed = true;
                    }
                } catch (NoSuchEntityException $e) {
                    $this->logger->debug(__('Quote Item %1 does not exist', $quoteItemSync->getItemId()));
                } catch (\Exception $e) {
                    $this->logger->debug($e);
                    $failed = true;
                }

                if ($failed) {
                    $quoteItemSync->setSyncAttempts($quoteItemSync->getSyncAttempts() + 1);
                    $this->syncRepository->saveQuoteItemEntry($quoteItemSync);
                } else {
                    $this->syncRepository->deleteQuoteItemEntry($quoteItemSync);
                }
            }
        } catch (\Exception $e) {
            $this->logger->debug($e);
            $quoteFailed = true;
        }

        if ($quoteFailed) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param \Lyonscg\SalesPad\Api\Data\QuoteSyncInterface $quoteSync
     */
    protected function _syncQuote($quoteSync)
    {
        $quoteFailed = false;
        try {
            if ($quoteSync->sync()) {
                $this->syncRepository->deleteQuoteEntry($quoteSync);
            } else {
                $quoteFailed = true;
            }
        } catch (\Exception $e) {
            $this->logger->debug($e);
            $quoteFailed = true;
        }

        if ($quoteFailed) {
            $this->logger->debug('Failed to sync quote: ' . $quoteSync->getId() . ' before pulling quote data');
            $quoteSync->setSyncAttempts($quoteSync->getSyncAttempts() + 1);
            $this->syncRepository->saveQuoteEntry($quoteSync);
            if ($quoteSync->getSyncAttempts() == 1) {
                $this->emailHelper->sendQuoteErrorEmail($quoteSync);
            }
        }
    }
}
