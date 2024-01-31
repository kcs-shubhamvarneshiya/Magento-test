<?php

namespace Lyonscg\SalesPad\Plugin;

use Lyonscg\SalesPad\Api\SyncRepositoryInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\RequisitionList\Api\Data\RequisitionListItemExtensionFactory;
use Magento\RequisitionList\Api\Data\RequisitionListItemInterface;
use Magento\RequisitionList\Model\RequisitionList\Items;

class RequisitionListItemRepositoryPlugin
{
    /**
     * @var SyncRepositoryInterface
     */
    protected $syncRepository;

    protected $itemExtensionFactory;

    /**
     *
     * RequisitionListItemRepositoryPlugin constructor.
     * @param SyncRepositoryInterface $syncRepository
     */
    public function __construct(
        SyncRepositoryInterface $syncRepository,
        RequisitionListItemExtensionFactory $itemExtensionFactory
    ) {
        $this->syncRepository = $syncRepository;
        $this->itemExtensionFactory = $itemExtensionFactory;
    }

    public function afterSave(Items $subject, $result, RequisitionListItemInterface $item)
    {
        // if we create a RequisitionList by pulling it in from SalesPad, we don't need to sync it back
        if (!$item->getData('__no_sync') === true) {
            $this->syncRepository->addRequisitionListItem($item);
        }
        return $result;
    }

    public function afterDelete(Items $subject, $result, RequisitionListItemInterface $item)
    {
        if (!$item->getData('__no_sync') === true) {
            $this->syncRepository->addRequisitionListItemForDelete($item);
        }
        return $result;
    }

    public function afterGet(Items $subject, RequisitionListItemInterface $result)
    {
        return $this->_loadItemData($result);
    }

    public function afterGetList(Items $subject, SearchResultsInterface $results)
    {
        foreach ($results->getItems() as $item) {
            $this->_loadItemData($item);
        }
        return $results;
    }

    protected function _loadItemData(RequisitionListItemInterface $item)
    {
        $extensionAttributes = $item->getExtensionAttributes() ?? $this->itemExtensionFactory->create();
        $extensionAttributes->setSalespadSalesDocNum($item->getSalespadSalesDocNum());
        $extensionAttributes->setSalespadLineNum($item->getSalespadLineNum());
        $extensionAttributes->setSalespadComponentSeqNum($item->getSalespadComponentSeqNum());
        $item->setExtensionAttributes($extensionAttributes);
        return $item;
    }
}
