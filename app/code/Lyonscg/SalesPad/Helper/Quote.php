<?php

namespace Lyonscg\SalesPad\Helper;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Data as CatalogHelper;
use Magento\Catalog\Model\Product;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\RequisitionList\Api\RequisitionListRepositoryInterface;
use Magento\RequisitionList\Api\Data\RequisitionListInterface;
use Magento\RequisitionList\Api\Data\RequisitionListItemInterface;
use Magento\RequisitionList\Api\Data\RequisitionListExtensionFactory;
use Magento\RequisitionList\Api\Data\RequisitionListItemExtensionFactory;
use Magento\RequisitionList\Model\RequisitionListItemProduct;
use Magento\RequisitionList\Model\ResourceModel\RequisitionList\CollectionFactory as RequisitionListCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class Quote extends \Magento\Framework\App\Helper\AbstractHelper
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
     * @var RequisitionListRepositoryInterface
     */
    protected $requisitionListRepository;

    /**
     * @var CatalogHelper
     */
    protected $catalogHelper;

    /**
     * @var RequisitionListItemProduct
     */
    protected $requisitionListItemProduct;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    protected $productRepository;

    protected $requisitionListCollectionFactory;

    protected $filterBuilder;

    protected $filterGroupBuilder;

    protected $searchCriteriaBuilder;

    protected $sortOrderBuilder;

    protected $_requisitionListById = [];

    /**
     * Quote constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param RequisitionListExtensionFactory $extensionFactory
     * @param RequisitionListItemExtensionFactory $itemExtensionFactory
     * @param RequisitionListRepositoryInterface $requisitionListRepository
     * @param CatalogHelper $catalogHelper
     * @param RequisitionListItemProduct $requisitionListItemProduct
     * @param StoreManagerInterface $storeManager
     * @param CustomerRepositoryInterface $customerRepository
     * @param ProductRepositoryInterface $productRepository
     * @param RequisitionListCollectionFactory $requisitionListCollectionFactory
     * @param FilterBuilder $filterBuilder
     * @param FilterGroupBuilder $filterGroupBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        RequisitionListExtensionFactory $extensionFactory,
        RequisitionListItemExtensionFactory $itemExtensionFactory,
        RequisitionListRepositoryInterface $requisitionListRepository,
        CatalogHelper $catalogHelper,
        RequisitionListItemProduct $requisitionListItemProduct,
        StoreManagerInterface $storeManager,
        CustomerRepositoryInterface $customerRepository,
        ProductRepositoryInterface $productRepository,
        RequisitionListCollectionFactory $requisitionListCollectionFactory,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder
    )
    {
        parent::__construct($context);
        $this->extensionFactory = $extensionFactory;
        $this->itemExtensionFactory = $itemExtensionFactory;
        $this->requisitionListRepository = $requisitionListRepository;
        $this->catalogHelper = $catalogHelper;
        $this->requisitionListItemProduct = $requisitionListItemProduct;
        $this->storeManager = $storeManager;
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
        $this->requisitionListCollectionFactory = $requisitionListCollectionFactory;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
    }

    public function setCustomerNum(RequisitionListInterface $list, $customerNum)
    {
        $extensionAttributes = $list->getExtensionAttributes() ?? $this->extensionFactory->create();
        // note capitalization of Pad, to keep the same as the customer version
        $extensionAttributes->setSalesPadCustomerNum($customerNum);
        $list->setExtensionAttributes($extensionAttributes);
        $list->setSalesPadCustomerNum($customerNum);
        return $list;
    }

    public function setSalesDocNum(RequisitionListInterface $list, $salesDocNum)
    {
        $extensionAttributes = $list->getExtensionAttributes() ?? $this->extensionFactory->create();
        $extensionAttributes->setSalespadSalesDocNum($salesDocNum);
        $list->setExtensionAttributes($extensionAttributes);
        $list->setSalespadSalesDocNum($salesDocNum);
        return $list;
    }

    public function setAddressCode(RequisitionListInterface $list, $addressCode, $addressType)
    {
        $extensionAttributes = $list->getExtensionAttributes() ?? $this->extensionFactory->create();
        if ($addressType == 'billing') {
            $extensionAttributes->setSalespadBillingAddressCode($addressCode);
            $list->setSalespadBillingAddressCode($addressCode);
        } else if ($addressType == 'shipping') {
            $extensionAttributes->setSalespadShippingAddressCode($addressCode);
            $list->setSalespadShippingAddressCode($addressCode);
        }

        $list->setExtensionAttributes($extensionAttributes);
        return $list;
    }

    /**
     * Put extension attributes onto RL items from the Salespad response data, also put the data on the
     * object itself so that it is saved to the database.  Call after sending the items to Salespad.
     * @param RequisitionListItemInterface $listItem
     * @param $salesDocNum
     * @param $lineNum
     * @return RequisitionListItemInterface
     */
    public function setItemData($listItem, $salesDocNum, $lineNum, $componentSeqNum)
    {
        $extensionAttributes = $listItem->getExtensionAttributes() ?? $this->itemExtensionFactory->create();
        $extensionAttributes->setSalespadSalesDocNum($salesDocNum);
        $extensionAttributes->setSalespadLineNum($lineNum);
        $extensionAttributes->setSalespadComponentSeqNum($componentSeqNum);
        $listItem->setSalespadSalesDocNum($salesDocNum);
        $listItem->setSalespadLineNum($lineNum);
        $listItem->setSalespadComponentSeqNum($componentSeqNum);
        $listItem->setExtensionAttributes($extensionAttributes);
        return $listItem;
    }

    /**
     * @param RequisitionListInterface|int $list
     * @return bool|string
     */
    public function getSalesDocNum($list)
    {
        if (!($list instanceof RequisitionListInterface)) {
            $list = $this->requisitionListRepository->get($list);
        }
        $extensionAttributes = $list->getExtensionAttributes();
        $salesDocNum = false;
        if ($extensionAttributes) {
            // try to get from extension attributes
            $salesDocNum = $extensionAttributes->getSalespadSalesDocNum();
        }

        if (!$salesDocNum) {
            // not in extension attributes, or extension attributes don't exist
            $salesDocNum = $list->getSalespadSalesDocNum();
        }
        return $salesDocNum;
    }

    public function getCustomerNum(RequisitionListInterface $list)
    {
        $extensionAttributes = $list->getExtensionAttributes();
        $customerNum = false;
        if ($extensionAttributes) {
            // try to get from extension attributes
            $customerNum = $extensionAttributes->getSalesPadCustomerNum();
        }

        if (!$customerNum) {
            // not in extension attributes, or extension attributes don't exist
            $customerNum = $list->getSalesPadCustomerNum();
        }
        return $customerNum;
    }

    public function getSubtotal(RequisitionListInterface $list)
    {
        $total = 0.0;
        foreach ($list->getItems() as $item) {
            $itemPrice = $this->getItemPrice($item, false);
            $total += ($itemPrice * $item->getQty());
        }
        return $total;
    }

    public function getTax(RequisitionListInterface $list)
    {
        return $this->getGrandTotal($list) - $this->getSubtotal($list);
    }

    public function getGrandTotal(RequisitionListInterface $list)
    {
        $total = 0.0;
        foreach ($list->getItems() as $item) {
            $itemPrice = $this->getItemPrice($item, true);
            $total += ($itemPrice * $item->getQty());
        }
        return $total;
    }

    public function getItemPrice(RequisitionListItemInterface $item, $includingTax = true)
    {
        $list = $this->_getList($item->getRequisitionListId());
        $customer = $this->getCustomer($list);
        if ($customer !== null) {
            $customerGroup = $customer->getGroupId();
            $store = $customer->getStoreId();
        } else {
            $customerGroup = null;
            $store = null;
        }
        try {
            $product = $this->requisitionListItemProduct->getProduct($item);
            if ($customerGroup !== null) {
                $product->setCustomerGroupId($customerGroup);
            }
            if ($store !== null) {
                $product->setStoreId($store);
            }

            return $this->catalogHelper->getTaxPrice(
                $product,
                $product->getPriceModel()->getFinalPrice($item->getQty(), $product),
                $includingTax,
                null,
                null,
                null,
                $this->storeManager->getStore(),
                null,
                false
            );
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return 0.0;
        }
    }

    public function getItemOrigPrice(RequisitionListItemInterface $item, $includingTax = true)
    {
        try{
            $product = $this->requisitionListItemProduct->getProduct($item);

            /** @var Product $product */
            return $this->catalogHelper->getTaxPrice(
                $product,
                $product->getPrice(),
                $includingTax,
                null,
                null,
                null,
                $this->storeManager->getStore(),
                null,
                false
            );
        } catch (\Exception $exception) {

            return 0.0;
        }
    }

    public function getItemSku(RequisitionListItemInterface $item)
    {
        $options = $item->getOptions();
        if (!isset($options['simple_product'])) {
            return $item->getSku();
        }
        $simpleProductId = $options['simple_product'];
        try {
            $product = $this->productRepository->getById($simpleProductId);
            return $product->getSku();
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return $item->getSku();
        }
    }

    public function getItemComment(RequisitionListItemInterface $item)
    {
        $extensionAttributes = $item->getExtensionAttributes() ?? $this->itemExtensionFactory->create();
        $comment = $extensionAttributes->getCommentsLineItem();
        if (!$comment) {
            $comment = $item->getCommentsLineItem();
        }
        return $comment;
    }

    public function getItemSidemark(RequisitionListItemInterface $item)
    {
        $extensionAttributes = $item->getExtensionAttributes() ?? $this->itemExtensionFactory->create();
        $sidemark = $extensionAttributes->getSidemark();
        if (!$sidemark) {
            $sidemark = $item->getSidemark();
        }
        return $sidemark;
    }

    public function getItemSalesDocNum(RequisitionListItemInterface $item)
    {
        $extensionAttributes = $item->getExtensionAttributes() ?? $this->itemExtensionFactory->create();
        $salesDocNum = $extensionAttributes->getSalespadSalesDocNum();
        if (!$salesDocNum) {
            $salesDocNum = $item->getSalespadSalesDocNum();
        }
        return $salesDocNum;
    }

    public function getItemLineNum(RequisitionListItemInterface $item)
    {
        $extensionAttributes = $item->getExtensionAttributes() ?? $this->itemExtensionFactory->create();
        $lineNum = $extensionAttributes->getSalespadLineNum();
        if (!$lineNum) {
            $lineNum = $item->getSalespadLineNum();
        }
        return $lineNum;
    }

    public function getItemComponentSeqNum(RequisitionListItemInterface $item)
    {
        $extensionAttributes = $item->getExtensionAttributes() ?? $this->itemExtensionFactory->create();
        $seqNum = $extensionAttributes->getSalespadComponentSeqNum();
        if (!$seqNum) {
            $seqNum = $item->getSalespadComponentSeqNum();
        }
        return $seqNum;
    }

    /**
     * Return true if item is in Salespad.  This will check if the item has a Sales_Doc_Num and Line_Num
     * and Component_Seq_Num associated with it.
     * @param RequisitionListItemInterface $item
     * @return bool
     */
    public function isItemInSalesPad(RequisitionListItemInterface $item)
    {
        if ($item->getSalespadLineNum() === null) {
            return false;
        }
        if ($item->getSalespadComponentSeqNum() === null) {
            return false;
        }
        return true;
    }

    public function getItemList(RequisitionListItemInterface $item)
    {
        return $this->requisitionListRepository->get($item->getRequisitionListId());
    }

    public function getCustomer(RequisitionListInterface $list)
    {
        try {
            return $this->customerRepository->getById($list->getCustomerId());
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    public function getCustomerGroupId(RequisitionListInterface $list)
    {
        $customer = $this->getCustomer($list);
        if ($customer === null) {
            return null;
        }

        return $customer->getGroupId();
    }

    protected function _getList($id)
    {
        if (!isset($this->_requisitionListById[$id])) {
            try {
                $list = $this->requisitionListRepository->get($id);
            } catch (NoSuchEntityException $e) {
                $list = null;
            }
            $this->_requisitionListById[$id] = $list;
        }
        return $this->_requisitionListById[$id];
    }

    public function getListBySalesDocNum($salesDocNum, $salesPadCustomerNum = false)
    {
        /** @var \Magento\RequisitionList\Model\ResourceModel\RequisitionList\Collection $collection */
        $collection = $this->requisitionListCollectionFactory->create();

        if ($salesPadCustomerNum) {
            $collection->addFieldToFilter('sales_pad_customer_num', $salesPadCustomerNum);
        }
        $collection->addFieldToFilter('salespad_sales_doc_num', $salesDocNum);
        $list = $collection->getFirstItem();
        return $list;
    }

    /**
     * @param $customerId
     * @param $customerNum
     * @return \Magento\Framework\Api\SearchCriteria
     */
    public function getSearchCriteria($customerId, $customerNum, $salespadSalesDocNum = null)
    {
        $sortOrder = $this->sortOrderBuilder
            ->setField('updated_at')
            ->setDirection('DESC')
            ->create();
        $idFilter = $this->filterBuilder
            ->setField('customer_id')
            ->setValue($customerId)
            ->setConditionType('eq')
            ->create();
        $numFilter = $this->filterBuilder
            ->setField('sales_pad_customer_num')
            ->setValue($customerNum)
            ->setConditionType('eq')
            ->create();
        if ($salespadSalesDocNum != null) {
            $salespadSalesDocNumFilter = $this->filterBuilder
                ->setField('salespad_sales_doc_num')
                ->setValue($salespadSalesDocNum)
                ->setConditionType('in')
                ->create();
            $filterGroup = $this->filterGroupBuilder
                ->addFilter($salespadSalesDocNumFilter)
                ->create();
        } else {
            $filterGroup = $this->filterGroupBuilder
                ->addFilter($idFilter)
                ->addFilter($numFilter)
                ->create();
        }
        return $this->searchCriteriaBuilder
            ->setFilterGroups([$filterGroup])
            ->addSortOrder($sortOrder)
            ->create();
    }

    public function getPoNumber(RequisitionListInterface $list)
    {
        $extAttrs = $list->getExtensionAttributes();
        if ($extAttrs && $extAttrs->getPoNumber()) {
            return $extAttrs->getPoNumber();
        }
        return $list->getPoNumber();
    }

    public function setPoNumber(RequisitionListInterface $list, $poNumber)
    {
        $extAttrs = $list->getExtensionAttributes();
        if (!$extAttrs) {
            $extAttrs = $this->extensionFactory->create();
        }
        $extAttrs->setPoNumber($poNumber);
        $list->setPoNumber($poNumber);
        $list->setExtensionAttributes($extAttrs);
    }

    /**
     * @param RequisitionListInterface $list
     * @param string $storeId
     * @return RequisitionListInterface
     */
    public function setStoreId(RequisitionListInterface $list, string $storeId)
    {
        $extensionAttributes = $list->getExtensionAttributes() ?? $this->extensionFactory->create();
        $extensionAttributes->setStoreId($storeId);
        $list->setExtensionAttributes($extensionAttributes);
        $list->setStoreId($storeId);
        return $list;
    }
}
