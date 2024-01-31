<?php

namespace Lyonscg\RequisitionList\Model\RequisitionListItem;

use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\DataObject;
use Magento\RequisitionList\Api\RequisitionListManagementInterface;
use Magento\RequisitionList\Api\RequisitionListRepositoryInterface;
use Magento\RequisitionList\Model\RequisitionListItem\Locator;
use Magento\RequisitionList\Model\RequisitionListItem\Options\Builder;
use Magento\RequisitionList\Model\RequisitionListProduct;

/**
 * Prepare and save requisition list item.
 */
class SaveHandler extends \Magento\RequisitionList\Model\RequisitionListItem\SaveHandler
{
    /**
     * @var RequisitionListRepositoryInterface
     */
    private $_requisitionListRepository;

    /**
     * @var Builder
     */
    private $_optionsBuilder;

    /**
     * @var RequisitionListManagementInterface
     */
    private $_requisitionListManagement;

    /**
     * @var Locator
     */
    private $_requisitionListItemLocator;

    /**
     * @var RequisitionListProduct
     */
    private $_requisitionListProduct;

    /**
     * @var StockRegistryInterface
     */
    private $_stockRegistry;

    /**
     * @param RequisitionListRepositoryInterface $requisitionListRepository
     * @param Builder $optionsBuilder
     * @param RequisitionListManagementInterface $requisitionListManagement
     * @param Locator $requisitionListItemLocator
     * @param RequisitionListProduct $requisitionListProduct
     * @param StockRegistryInterface $stockRegistry
     */
    public function __construct(
        RequisitionListRepositoryInterface $requisitionListRepository,
        Builder $optionsBuilder,
        RequisitionListManagementInterface $requisitionListManagement,
        Locator $requisitionListItemLocator,
        RequisitionListProduct $requisitionListProduct,
        StockRegistryInterface $stockRegistry
    ) {
        $this->_requisitionListRepository = $requisitionListRepository;
        $this->_optionsBuilder = $optionsBuilder;
        $this->_requisitionListManagement = $requisitionListManagement;
        $this->_requisitionListItemLocator = $requisitionListItemLocator;
        $this->_requisitionListProduct = $requisitionListProduct;
        $this->_stockRegistry = $stockRegistry;
        parent::__construct(
            $this->_requisitionListRepository,
            $this->_optionsBuilder,
            $this->_requisitionListManagement,
            $this->_requisitionListItemLocator,
            $this->_requisitionListProduct,
            $this->_stockRegistry
        );
    }

    /**
     * Set options and save requisition list item.
     *
     * @param DataObject $productData
     * @param array $options
     * @param int $itemId
     * @param int $listId
     * @return \Magento\Framework\Phrase
     */
    public function saveItem(DataObject $productData, array $options, $itemId, $listId)
    {
        $sku = (string)$productData->getSku();
        $qty = $this->_retrieveQty($productData);
        if (!$this->_isDecimalQtyUsed($sku)) {
            $qty = (int)$qty;
        }

        $requisitionList = $this->_requisitionListRepository->get($listId);
        $itemOptions = $this->_optionsBuilder->build($options, $itemId, false);
        $item = $this->_requisitionListItemLocator->getItem($itemId);
        $item->setQty($qty);
        $item->setOptions($itemOptions);
        $item->setSku($sku);

        $items = $requisitionList->getItems();

        if ($item->getId()) {
            foreach ($items as $i => $existItem) {
                if ($existItem->getId() == $item->getId()) {
                    $items[$i] = $item;
                }
            }
        } else {
            $items[] = $item;
        }

        $product = $this->_requisitionListProduct->getProduct($productData->getSku());
        if ($item->getId()) {
            $message = __('%1 has been updated in your requisition list.', $product->getName());
        } else {
            $message = __(
                'Product %1 has been added to the requisition list %2.',
                $product->getName(),
                $requisitionList->getName()
            );
        }

        $this->_requisitionListManagement->setItemsToList($requisitionList, $items);
        $this->_requisitionListRepository->save($requisitionList);

        $items = $requisitionList->getItems();
        $items = $this->processLightBulbs($items, $options);
        if (!empty($items)) {
            $this->_requisitionListManagement->setItemsToList($requisitionList, $items);
            $this->_requisitionListRepository->save($requisitionList);
        }
        return $message;
    }

    /**
     * Retrieve qty param
     *
     * @param DataObject $productData
     * @return float
     */
    private function _retrieveQty(DataObject $productData): float
    {
        $qty = (float)$productData->getOptions('qty');
        if ($qty <= 0) {
            $qty = 1.0;
        }

        return $qty;
    }

    /**
     * Is stock item qty uses decimal
     *
     * @param string $sku
     * @return bool
     */
    private function _isDecimalQtyUsed(string $sku): bool
    {
        $stockItem = $this->_stockRegistry->getStockItemBySku($sku);

        return (bool)$stockItem->getIsQtyDecimal();
    }

    /**
     * @param array $items
     * @param array $options
     * @return array
     */
    private function processLightBulbs($items, $options)
    {
        try {
            if (!empty($options['lightbulb'])) {
                foreach ($options['lightbulb'] as $bulbOption) {
                    if (!empty($bulbOption['id'])) {
                        $option['product'] = $bulbOption['id'];
                        $option['selected_configurable_option'] = '';
                        $option['related_product'] = '';
                        $option['item'] = $bulbOption['id'];
                        $option['form_key'] = $options['form_key'];
                        $option['qty'] = $bulbOption['qty'] * $options['qty'] * 1;
                        $itemOptions = $this->_optionsBuilder->build($option, 0, false);
                        $item = $this->_requisitionListItemLocator->getItem(0);
                        $item->setQty($bulbOption['qty']);
                        $item->setOptions($itemOptions);
                        $item->setSku($bulbOption['sku']);
                        if ($item->getId()) {
                            foreach ($items as $i => $existItem) {
                                if ($existItem->getId() == $item->getId()) {
                                    $items[$i] = $item;
                                }
                            }
                        } else {
                            $items[] = $item;
                        }
                    }
                }
            }
            return $items;
        } catch (\Exception $e) {
            return [];
        }
    }
}
