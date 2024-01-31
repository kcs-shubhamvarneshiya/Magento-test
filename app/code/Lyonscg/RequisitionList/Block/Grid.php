<?php
/**
 * Lyonscg_RequisitionList
 *
 * @category  Lyons
 * @package   Lyonscg_RequisitionList
 * @author    Tetiana Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */
namespace Lyonscg\RequisitionList\Block;

use Lyonscg\RequisitionList\Block\Item as ItemProcess;
use Magento\Framework\View\Element\Template\Context;
use Magento\RequisitionList\Api\Data\RequisitionListItemInterface;
use Magento\RequisitionList\Model\RequisitionList\ItemSelector;
use Magento\RequisitionList\Model\RequisitionListItem\Validation;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Grid
 * @package Lyonscg\RequisitionList\Block
 */
class Grid extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Validation
     */
    private $validation;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ItemSelector
     */
    private $itemSelector;

    /**
     * @var int
     */
    private $itemErrorCount = 0;

    /**
     * @var array
     */
    private $errorsByItemId = [];


    /**
     * @param Context $context
     * @param Validation $validation
     * @param StoreManagerInterface $storeManager
     * @param ItemSelector $itemSelector
     * @param Item $itemProcess
     * @param array $data [optional]
     */
    public function __construct(
        Context $context,
        Validation $validation,
        StoreManagerInterface $storeManager,
        ItemSelector $itemSelector,
        ItemProcess $itemProcess,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->validation = $validation;
        $this->storeManager = $storeManager;
        $this->itemSelector = $itemSelector;
        $this->itemProcess = $itemProcess;
    }

    /**
     * Get count of items with errors.
     *
     * @return int
     */
    public function getItemErrorCount()
    {
        return $this->itemErrorCount;
    }

    /**
     * Get list of items that are included in requisition list.
     *
     * @return RequisitionListItemInterface|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getRequisitionListItems()
    {
        $requisitionId = $this->getRequest()->getParam('requisition_id');
        if ($requisitionId === null) {
            return null;
        }

        $items = $this->itemSelector->selectAllItemsFromRequisitionList(
            $requisitionId,
            $this->storeManager->getWebsite()->getId()
        );
        foreach ($items as $item) {
            $this->checkForItemError($item);
        }
        uasort($items, function (RequisitionListItemInterface $firstItem, RequisitionListItemInterface $secondItem) {
            $isFirstItemError = !empty($this->errorsByItemId[$firstItem->getId()]);
            $isSecondItemError = !empty($this->errorsByItemId[$secondItem->getId()]);

            return (int)$isSecondItemError - (int)$isFirstItemError;
        });

        return $items;
    }

    /**
     * Check if product is enabled and its quantity is available.
     *
     * @param RequisitionListItemInterface $item
     * @return bool
     */
    private function checkForItemError(RequisitionListItemInterface $item)
    {
        try {
            $errors = $this->validation->validate($item);

            if (count($errors)) {
                $this->errorsByItemId[$item->getId()] = $errors;
                $this->itemErrorCount++;
            }
            $isItemHasErrors = (count($errors) > 0);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $this->itemErrorCount++;
            $this->errorsByItemId[$item->getId()] = [__('The SKU was not found in the catalog.')];
            $isItemHasErrors = true;
        }

        return $isItemHasErrors;
    }

    /**
     * Get errors for requisition list item.
     *
     * @param RequisitionListItemInterface $item
     * @return array
     */
    public function getItemErrors(RequisitionListItemInterface $item)
    {
        return !empty($this->errorsByItemId[$item->getId()]) ? $this->errorsByItemId[$item->getId()] : [];
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getQuoteTotals()
    {
        $totals = ['total' => 0.0, 'grandTotal' => 0.0];
        $listItems = $this->getRequisitionListItems();
        foreach ($listItems as $item) {
            $this->itemProcess->setItem($item);
            $totals['total'] += $this->itemProcess->getFinalPrice() * $item->getQty() * 1;
        }
        $totals['grandTotal'] = $totals['total'] + $totals['total'];

        return $totals;
    }

    /**
     * @param float $price
     * @return float
     */
    public function getFormattedTotal($price)
    {
        return $this->itemProcess->formatProductPrice($price);
    }
}
