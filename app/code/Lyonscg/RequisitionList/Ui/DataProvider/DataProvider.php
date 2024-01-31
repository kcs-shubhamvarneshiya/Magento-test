<?php

namespace Lyonscg\RequisitionList\Ui\DataProvider;

use Lyonscg\SalesPad\Helper\Quote as QuoteHelper;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;
use Magento\RequisitionList\Api\RequisitionListRepositoryInterface;
use Magento\RequisitionList\Ui\DataProvider\DataProvider as CoreDataProvider;
use Lyonscg\SalesPad\Model\Api\SalesPad\SalesDocument;
use Lyonscg\SalesPad\Model\Sync\Quote\Pull as SyncQuotePull;
use Lyonscg\SalesPad\Model\Api\SalesPad\SalesLineItem;
use Magento\RequisitionList\Api\Data\RequisitionListInterfaceFactory;
use Magento\RequisitionList\Api\RequisitionListManagementInterface;

class DataProvider extends CoreDataProvider
{
    /**
     * @var UserContextInterface
     */
    private $customerContext;

    /**
     * @var RequisitionListRepositoryInterface
     */
    private $requisitionListRepository;

    /**
     * @var QuoteHelper
     */
    private $quoteHelper;

    private $customerRepository;

    /**
     * @var SalesDocument
     */
    private $salesDocument;

    /**
     * @var SalesLineItem
     */
    private $salesLineItem;

    /**
     * @var SyncQuotePull
     */
    private $syncQuotePull;

    /**
     * @var RequisitionListInterfaceFactory
     */
    private $requisitionListFactory;

    /**
     * @var RequisitionListManagementInterface
     */
    private $requisitionListManagement;

    /**
     * @var null|int
     */
    private $customerId;

    /**
     * @var bool|string
     */
    private $customerNum;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Reporting $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param UserContextInterface $customerContext
     * @param RequisitionListRepositoryInterface $requisitionListRepository
     * @param QuoteHelper $quoteHelper
     * @param CustomerRepositoryInterface $customerRepository
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Reporting $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        UserContextInterface $customerContext,
        RequisitionListRepositoryInterface $requisitionListRepository,
        QuoteHelper $quoteHelper,
        CustomerRepositoryInterface $customerRepository,
        SalesDocument $salesDocument,
        SalesLineItem $salesLineItem,
        SyncQuotePull $syncQuotePull,
        RequisitionListInterfaceFactory $requisitionListFactory,
        RequisitionListManagementInterface $requisitionListManagement,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $customerContext,
            $requisitionListRepository,
            $meta,
            $data
        );
        $this->requisitionListRepository = $requisitionListRepository;
        $this->customerContext = $customerContext;
        $this->quoteHelper = $quoteHelper;
        $this->customerRepository = $customerRepository;
        $this->salesDocument = $salesDocument;
        $this->salesLineItem = $salesLineItem;
        $this->syncQuotePull = $syncQuotePull;
        $this->requisitionListFactory = $requisitionListFactory;
        $this->requisitionListManagement = $requisitionListManagement;
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchResult()
    {
        $customerId = $this->customerContext->getUserId();
        $this->customerId = $customerId;
        $customerNum = false;
        try {
            $customer = $this->customerRepository->getById($customerId);
            // CRC-638 Moved sales_pad_customer_num customer attribute from a custom to an extension one.
            $customerNum = $customer->getExtensionAttributes()->getSalesPadCustomerNum() ?? false;
        } catch (\Exception $e) {
        }
        $this->customerNum = $customerNum;
        if ($customerNum) {
            try {
                $paging = $this->request->getParams();
                $spQuotesResult = $this->getSalesPadQuotes($paging['paging']['current'] ?? 1, $paging['paging']['pageSize'] ?? 10, $customerNum);
                $spQuotes = (!empty($spQuotesResult['items'])) ? $spQuotesResult['items'] : [];
                $salespadSalesDocNums = $this->getSalespadSalesDocNums($spQuotes);
                $searchCriteria = $this->quoteHelper->getSearchCriteria($customerId, $customerNum, $salespadSalesDocNums);
                $quotes = $this->requisitionListRepository->getList($searchCriteria);
                $quoteItems = $quotes->getItems();
                if (count($quoteItems) != count($spQuotes)) {
                    $this->pullQuotesFromSp($spQuotes, $quoteItems);
                }
                $quotes = $this->requisitionListRepository->getList($searchCriteria);
                $quotes->setTotalCount(($spQuotesResult['count'] ?? 0));
                if (!empty($quotes->getItems()) && !empty($spQuotes)) {
                    $updatedQuotes = $this->populateQuoteTotal($spQuotes, $quotes->getItems());
                    $quotes->setItems($updatedQuotes);
                }
                return $quotes;
            } catch (\Exception $e) {

            }
        } else {
            $this->addOrder('updated_at', 'DESC');
            $filter = $this->filterBuilder
                ->setField('main_table.customer_id')
                ->setConditionType('eq')
                ->setValue($customerId)
                ->create();
            $this->searchCriteriaBuilder->addFilter($filter);
            $this->searchCriteria = $this->searchCriteriaBuilder->create();
            $this->searchCriteria->setRequestName($this->name);

            return $this->requisitionListRepository->getList($this->getSearchCriteria());
        }
    }

    protected function getSalesPadQuotes($page = 1, $limit = 0, $spCustomerNum=null)
    {
        $quotes = $this->salesDocument->getByCustomerNum(
            $this->salesDocument::TYPE_QUOTE,
            $spCustomerNum, $limit, $page,
            SyncQuotePull::DELETED_FILTER
        );
        return $quotes;
    }

    /**
     * @param $spQuotes
     * @return array
     */
    protected function getSalespadSalesDocNums($spQuotes)
    {
        $spDocNums = [];
        if (!empty($spQuotes)) {
            foreach ($spQuotes as $spQuote) {
                $spDocNums[] = trim($spQuote["Sales_Doc_Num"]);
            }
        }
        return implode(',', $spDocNums);
    }

    protected function pullQuotesFromSp($spQuotes, $magentoQuotes)
    {
        $magentoSalesPadDocNums = [];
        if (!empty($magentoQuotes)) {
            foreach ($magentoQuotes as $magentoQuote) {
                $magentoSalesPadDocNums[] = $magentoQuote->getSalespadSalesDocNum();
            }
        }
        if (!empty($spQuotes)) {
            foreach ($spQuotes as $spQuote){
                if (!in_array(trim($spQuote['Sales_Doc_Num']), $magentoSalesPadDocNums)) {
                    $spQuoteItems = $this->getSalesPadQuoteItems($spQuote['Sales_Doc_Num']);
                    $this->createQuoteInMagento($spQuote, $spQuoteItems);
                }
            }
        }
    }

    protected function getSalesPadQuoteItems($salesDocNum)
    {
        $items = $this->salesLineItem->search($this->salesLineItem::TYPE_QUOTE, $salesDocNum);
        return $items['Items'] ?? [];
    }

    protected function createQuoteInMagento(array $quote, array $items)
    {
        $salesDocNum = trim($quote[SyncQuotePull::SALES_DOC_NUM]);
        // create requisition list
        /** @var \Magento\RequisitionList\Api\Data\RequisitionListInterface $list */
        $list = $this->requisitionListFactory->create();
        $list->setName(trim($this->syncQuotePull->_getUserFieldData($quote, SyncQuotePull::PROJECT_NAME)));
        $list->setDescription(trim($this->syncQuotePull->_getUserFieldData($quote, 'xWebOrderNotes')));
        $list->setCustomerId($this->customerId);
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
            try {
                $listItems[] = $this->syncQuotePull->_createRequisitionListItem($itemData);
            } catch (\Exception $e) {}
        }
        $this->requisitionListManagement->setItemsToList($list, $listItems);
        $this->requisitionListRepository->save($list);
        return $list;
    }

    protected function populateQuoteTotal($spQuotes, $magentoQuotes)
    {
        $updatedMagentoQuotes = [];
        foreach ($magentoQuotes as $quoteId => $magentoQuote) {
            foreach ($spQuotes as $key => $spQuote) {
                if (trim($spQuote['Sales_Doc_Num']) == $magentoQuote->getSalespadSalesDocNum()) {
                    $magentoQuote->setQuoteTotal($spQuote['Total']);
                    unset($spQuotes[$key]);
                }
            }
            $updatedMagentoQuotes[$quoteId] = $magentoQuote;
        }
        return $updatedMagentoQuotes;
    }
}
