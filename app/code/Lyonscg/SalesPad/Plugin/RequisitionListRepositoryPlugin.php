<?php

namespace Lyonscg\SalesPad\Plugin;

use Lyonscg\SalesPad\Api\SyncRepositoryInterface;
use Lyonscg\SalesPad\Api\DeletedQuoteRepositoryInterface;
use Lyonscg\SalesPad\Helper\Quote as QuoteHelper;
use Lyonscg\SalesPad\Model\Api\Quote as QuoteApi;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\RequisitionList\Api\RequisitionListRepositoryInterface;
use Magento\RequisitionList\Api\Data\RequisitionListInterface;
use Magento\RequisitionList\Api\Data\RequisitionListExtensionFactory;
use Magento\RequisitionList\Api\Data\RequisitionListItemExtensionFactory;

class RequisitionListRepositoryPlugin
{
    /**
     * @var RequisitionListExtensionFactory
     */
    protected $extensionFactory;

    /**
     * @var RequisitionListItemExtensionFactory
     */
    protected $itemExtensionFactory;

    /**
     * @var QuoteApi
     */
    protected $quoteApi;

    /**
     * @var QuoteHelper
     */
    protected $quoteHelper;

    /**
     * @var SyncRepositoryInterface
     */
    protected $syncRepository;

    /**
     * @var DeletedQuoteRepositoryInterface
     */
    protected $deletedQuoteRepository;

    public function __construct(
        RequisitionListExtensionFactory $extensionFactory,
        RequisitionListItemExtensionFactory $itemExtensionFactory,
        QuoteApi $quoteApi,
        QuoteHelper $quoteHelper,
        SyncRepositoryInterface $syncRepository,
        DeletedQuoteRepositoryInterface $deletedQuoteRepository
    ) {
        $this->extensionFactory = $extensionFactory;
        $this->itemExtensionFactory = $itemExtensionFactory;
        $this->quoteApi = $quoteApi;
        $this->quoteHelper = $quoteHelper;
        $this->syncRepository = $syncRepository;
        $this->deletedQuoteRepository = $deletedQuoteRepository;
    }

    public function afterGet(RequisitionListRepositoryInterface $subject, RequisitionListInterface $result)
    {
        $extensionAttributes = $result->getExtensionAttributes() ?? $this->extensionFactory->create();
        $extensionAttributes->setSalesPadCustomerNum($result->getSalesPadCustomerNum());
        $extensionAttributes->setSalespadSalesDocNum($result->getSalespadSalesDocNum());
        $extensionAttributes->setSalespadBillingAddressCode($result->getSalespadBillingAddressCode());
        $extensionAttributes->setSalespadShippingAddressCode($result->getSalespadShippingAddressCode());
        $extensionAttributes->setStoreId($result->getStoreId());
        $result->setExtensionAttributes($extensionAttributes);
        // add data to items
        return $this->addDataToRequisitionListItems($result);
    }

    public function afterGetList(RequisitionListRepositoryInterface $subject, SearchResultsInterface $results)
    {
        foreach ($results->getItems() as $list) {
            $this->afterGet($subject, $list);
        }
        return $results;
    }

    protected function addDataToRequisitionListItems(RequisitionListInterface $list)
    {
        $listItems = $list->getItems();
        if (null !== $listItems) {
            /** @var \Magento\RequisitionList\Api\Data\RequisitionListItemInterface $listItem */
            foreach ($listItems as $listItem) {
                $extensionAttributes = $listItem->getExtensionAttributes() ?? $this->itemExtensionFactory->create();
                $extensionAttributes->setSalespadLineNum($listItem->getSalespadLineNum());
                $extensionAttributes->setSalespadComponentSeqNum($listItem->getSalespadComponentSeqNum());
                $extensionAttributes->setSalespadSalesDocNum($listItem->getSalespadSalesDocNum());
                $listItem->setExtensionAttributes($extensionAttributes);
            }
        }
        return $list;
    }

    public function afterSave(RequisitionListRepositoryInterface $subject, RequisitionListInterface $result)
    {
        //Adding store_id to requisition list in the similar way the po_number is added to it
        //in \Lyonscg\RequisitionList\Plugin\RequisitionListRepositoryPlugin::afterSave method.
        if (!$result->getStoreId()) {
            $customer = $this->quoteHelper->getCustomer($result);
            if ($customer) {
                $result = $this->quoteHelper->setStoreId($result, $customer->getStoreId());
                $result->save();
            }
        }
        // if we create a RequisitionList by pulling it in from SalesPad, we don't need to sync it back
        if (!$result->getData('__no_sync') === true) {
            $this->syncRepository->addRequisitionList($result);
        }
        return $result;
    }

    /**
     * @param RequisitionListRepositoryInterface $subject
     * @param bool $result
     * @param RequisitionListInterface $list
     * @return mixed
     */
    public function afterDelete(RequisitionListRepositoryInterface $subject, $result, RequisitionListInterface $list)
    {
        if (!$result) {
            // if list wasn't deleted, bail out now
            return $result;
        }
        $this->deletedQuoteRepository->add($list);
        // block syncing a delete for things like adding to cart
        if ($list->getData('__no_sync') === true) {
            return $result;
        }
        $this->syncRepository->addRequisitionListForDelete($list);
        return $result;
    }
}
