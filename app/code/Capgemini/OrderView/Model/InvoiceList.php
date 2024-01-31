<?php

namespace Capgemini\OrderView\Model;

use Capgemini\CompanyType\Model\Config;
use Capgemini\OrderView\Helper\Data;
use Capgemini\PartnersInsight\Model\Api\Invoice as PiInvoiceApi;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Collection;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Url\DecoderInterface;

class InvoiceList extends Collection
{
    /**
     * Custom Filters configuration
     *
     * @var array
     */
    protected $_customFilters = [];

    /**
     * @var array
     */
    protected $filtersParams;
    /**
     * @var string
     */
    protected $customerNumber;

    /**
     * @var PiInvoiceApi
     */
    protected $piInvoiceApi;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var TimezoneInterface
     */
    protected $localeDate;

    /**
     * @var CurrencyFactory
     */
    protected $currencyFactory;

    /** @var \Magento\Directory\Model\Currency */
    protected $currency = null;

    protected $rawData = null;

    protected $sortedIds = [];

    protected $size = null;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /** @var string|null */
    private $currencyCode = null;

    protected Config $companyTypeConfig;

    /**
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * @var Json
     */
    protected $jsonSerializer;
    private EntityFactoryInterface $entityFactory;
    /**
     * @var DecoderInterface
     */
    protected DecoderInterface $urlDecoder;

    /**
     * InvoiceList constructor.
     * @param EntityFactoryInterface $entityFactory
     * @param PiInvoiceApi $piInvoiceApi
     * @param UrlInterface $urlBuilder
     * @param TimezoneInterface $localeDate
     * @param CurrencyFactory $currencyFactory
     * @param StoreManagerInterface $storeManager
     * @param Data $helper
     * @param LoggerInterface $logger
     * @param Config $companyTypeConfig
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        PiInvoiceApi                                                $piInvoiceApi,
        UrlInterface                                              $urlBuilder,
        TimezoneInterface                                         $localeDate,
        CurrencyFactory                                           $currencyFactory,
        StoreManagerInterface                                     $storeManager,
        Data                                                      $helper,
        LoggerInterface                                           $logger,
        Config                                                    $companyTypeConfig,
        Json                                                      $jsonSerializer,
        RequestInterface                                          $request,
        DecoderInterface $urlDecoder
    ) {
        parent::__construct($entityFactory);
        $this->piInvoiceApi = $piInvoiceApi;
        $this->urlBuilder = $urlBuilder;
        $this->localeDate = $localeDate;
        $this->currencyFactory = $currencyFactory;
        $this->storeManager = $storeManager;
        $this->helper = $helper;
        $this->logger = $logger;
        $this->jsonSerializer = $jsonSerializer;
        $this->companyTypeConfig = $companyTypeConfig;
        $this->entityFactory = $entityFactory;
        $this->request = $request;
        $this->urlDecoder = $urlDecoder;
    }

    /**
     * @return string|null
     */
    public function getCurrencyCode()
    {
        if (empty($this->currencyCode)) {
            try {
                $this->currencyCode = $this->storeManager->getStore()->getCurrentCurrencyCode();
            } catch (\Exception $e) {
                return $this->currencyCode;
            }
        }
        return $this->currencyCode;
    }

    /**
     * Load data
     *
     * @param bool $printQuery
     * @param bool $logQuery
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function loadData($printQuery = false, $logQuery = true)
    {
        if ($this->isLoaded()) {
            return $this;
        }
        $invoiceData = $this->_fetchInvoiceData();
        // call salespad api here
        if (is_array($invoiceData)) {
            foreach ($invoiceData as $row) {
                    $invoice = $this->getNewEmptyItem();
                    $invoice->addData($row);
                    $this->addItem($invoice);
            }
        }
        $this->_setIsLoaded(true);
        return $this;
    }

    /**
     * @param $customerNum
     */
    public function setCustomerNumber($customerNum)
    {
        $this->customerNumber = $customerNum;
    }

    /**
     * @return mixed
     */
    public function getCustomerNumber()
    {
        return $this->customerNumber;
    }

    /**
     * @return int|void
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return array|null
     */
    protected function _fetchInvoiceData()
    {
        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);

        if ($customerType != Config::WHOLESALE) {
            if (!$this->getCustomerNumber()) {
                return [];
            }
        }

        if ($this->rawData === null) {
            $data = $this->_getInvoices();
            if ($customerType != Config::WHOLESALE) {
                $this->size = $data['count'];
            }
            if (!is_array($data)) {
                $this->rawData = [];
            } else {
                $this->rawData = $this->_processInvoices($data ?: []);
            }
        }

        return $this->rawData;
    }
    protected function _getInvoiceSummary()
    {
        // Getting customer type for determining which API to call.

        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);
        $allInvoices = [];
        $customerId = null;
        if ($customerType == Config::WHOLESALE) {
            try {
                if($this->request->getParam('account_id'))
                {
                    $customerId = $this->request->getParam('account_id');
                }else {
                    if ($customer->getCustomAttribute('customer_number_vc') && $customer->getCustomAttribute('customer_number_vc')->getValue() != null) {
                        $customerVcId = $customer->getCustomAttribute('customer_number_vc')->getValue();
                        if (!is_null($customerVcId)) {
                            $customerId = trim($customerVcId," ");
                        }
                    }else if ($customer->getCustomAttribute('customer_number_gl') && $customer->getCustomAttribute('customer_number_gl')->getValue() != null) {
                        $customerGlId = $customer->getCustomAttribute('customer_number_gl')->getValue();
                        if (!is_null($customerGlId)) {
                            $customerId = trim($customerGlId, " ");
                        }
                    }else if ($customer->getCustomAttribute('customer_number_tech') && $customer->getCustomAttribute('customer_number_tech')->getValue() != null) {
                        $customerTlId = $customer->getCustomAttribute('customer_number_tech')->getValue();

                        if (!is_null($customerTlId)) {
                            $customerId = trim($customerTlId, " ");
                        }
                    }
                }
                if($customerId !== null)
                {
                    if ($this->request->getParam('search')){
                        $searchTermStrings = ($this->request->getParam('search')) ? $this->urlDecoder->decode($this->request->getParam('search')): NULL;
                        $invoices = $this->piInvoiceApi->getInvoiceSummarySearch($customerId, $searchTermStrings);
                    }
                    else{
                        $invoices = $this->piInvoiceApi->getInvoiceSummary($customerId);
                    }

                    if (!is_null($invoices['data'])) {
                        foreach ($invoices['data'] as $invoice) {
                            $allInvoices[] = $invoice;
                        }
                    }

                    $keys = array_column($allInvoices, 'invoiceDate');
                }
                return $allInvoices;
            } catch (\Exception $exception) {
                return $allInvoices;
            }
        }
    }

    public function getInvoiceSummary($customerNumber)
    {
        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);

        if ($customerType != Config::WHOLESALE) {
            if (!$customerNumber) {
                return [];
            }
        }

        $data = $this->_getInvoiceSummary();
        if (!is_array($data)) {
            $this->rawData = [];
        } else {
            $rawData = $this->_processInvoices($data ?: []);
        }

            return $rawData;

    }
    /**
     * Set collection size
     *
     * @param int $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }
    protected function _getInvoices()
    {
        // Getting customer type for determining which API to call.

        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);
        $allInvoices = [];
        $customerId = null;
        $curPage = $this->request->getParam('p') ?? 1;
        $pageSize = $this->getPageSize();
        if(($this->request->getParam('sort_by'))){
            $sortby = $this->request->getParam('sort_by');
        }

        if ($customerType == Config::WHOLESALE) {
            try {
                if($this->request->getParam('account_id'))
                {
                    $customerId = $this->request->getParam('account_id');
                }else {
                    if ($customer->getCustomAttribute('customer_number_vc') && $customer->getCustomAttribute('customer_number_vc')->getValue() != null) {
                        $customerVcId = $customer->getCustomAttribute('customer_number_vc')->getValue();
                        if (!is_null($customerVcId)) {
                            $customerId = trim($customerVcId," ");
                        }
                    }else if ($customer->getCustomAttribute('customer_number_gl') && $customer->getCustomAttribute('customer_number_gl')->getValue() != null) {
                        $customerGlId = $customer->getCustomAttribute('customer_number_gl')->getValue();
                        if (!is_null($customerGlId)) {
                            $customerId = trim($customerGlId, " ");
                        }
                    }else if ($customer->getCustomAttribute('customer_number_tech') && $customer->getCustomAttribute('customer_number_tech')->getValue() != null) {
                        $customerTlId = $customer->getCustomAttribute('customer_number_tech')->getValue();

                        if (!is_null($customerTlId)) {
                            $customerId = trim($customerTlId, " ");
                        }
                    }
                }
                if($customerId !=null)
                {
                    $apiFilters = $this->getApiFilters();
                    if ($this->request->getParam('search')) {
                        $invoices = $this->piInvoiceApi->getSearchInvoices($customerId, $this->urlDecoder->decode($this->request->getParam('search')), $curPage, $pageSize);
                    } else if ($this->request->getParam('sort_by')){

                         if($this->request->getParam('status')){

                            $status = $this->request->getParam('status');
                            }else{
                                $status = 'Open,Closed';
                            }
                        
                            $invoices = $this->piInvoiceApi->getInvoiceSort($customerId, $status, $curPage, $pageSize, $sortby);
                      
                    } else {
                        $invoices = $this->piInvoiceApi->getAllInvoices($customerId,$apiFilters, $curPage, $pageSize);
                    }
                    if (!is_null($invoices['data'])) {
                        foreach ($invoices['data'] as $invoice) {
                            $allInvoices[] = $invoice;
                        }
                    }
                    if(isset($invoices['totalRecords']))
                    {
                        $this->setSize($invoices['totalRecords']);
                    }
                    if (isset($invoices['filters'])) {
                        foreach ($invoices['filters'] as $field => $values) {
                            $this->addCustomFilter($field, $values);
                        }
                    }

                    $keys = array_column($allInvoices, 'invoiceDate');
                }
                return $allInvoices;
            } catch (\Exception $exception) {
                return $allInvoices;
            }
        }
    }

    /**
     * @param array $filtersParams
     * @return void
     */
    public function addApiFilters(array $filtersParams)
    {
        $this->filtersParams = $filtersParams;
        return $this;
    }

    /**
     * @return array
     */
    public function getApiFilters()
    {
        return $this->filtersParams;
    }
    protected function _processInvoices(array $invoices)
    {
        // Getting customer type for determining which API to call.
        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);

        if ($customerType == Config::WHOLESALE) {
            return $this->_processPiInvoices($invoices);
        }
    }

    /**
     * @param array $invoices
     * @return array
     */
    protected function _processSalesPadInvoices(array $invoices)
    {
        $processed = [];
        $dotted = [];
        foreach ($invoices as $invoice) {
            $salesDocNum = trim($invoice['Sales_Doc_Num']);

            if (substr($salesDocNum, -1, 1) === '.') {
                if ($invoice['Source'] !== 'History' && $invoice['Source'] !== 'Void') {
                    $dotted[] = $salesDocNum;
                }

                continue;
            }

            $processed[$salesDocNum] = $this->_processSalesPadInvoice($invoice);
        }
        foreach ($dotted as $value) {
            $salesDocNum = substr($value, 0, -1);
            if (isset($processed[$salesDocNum])) {
                $processed[$salesDocNum]['status'] = __('Pending');
            }
        }

        return array_values($processed);
    }

    /**
     * @param array $invoiceData
     * @return array
     */
    protected function _processSalesPadInvoice(array $invoiceData)
    {
        return [
            'job_name' => $this->_getJobName($invoiceData),
            'id' => trim($invoiceData['Sales_Doc_Num']),
            'purchase_invoice' => '',
            'increment_id' => $this->_getIncrementId($invoiceData),
            'real_invoice' => $this->_getRealInvoice($invoiceData),
            'created_at' => $this->_formatDate(trim($invoiceData['Created_On'])),
            'created_at_timestamp' => strtotime(trim($invoiceData['Created_On'])),
            'ship_to_name' => trim($invoiceData['Ship_To_Name']),
            'total' => $this->_formatTotal(trim($invoiceData['Total'])), // format Total
            'status' => $this->_getStatus($invoiceData['Source']),
            'view_link' => $this->_getViewLink(trim($invoiceData['Sales_Doc_Num'])),
            'reinvoice_link' => $this->_getReinvoiceLink($invoiceData)
        ];
    }

    /**
     * @param array $invoices
     * @return array
     */
    protected function _processPiInvoices(array $invoices)
    {
        $processed = [];
        $dotted = [];
        foreach ($invoices as $invoice) {
            $processed[$invoice['invoiceId']] = $this->_processPiWorkingInvoice($invoice);
        }

        return array_values($processed);
    }

    /**
     * @param array $invoiceData
     * @return array
     */
    protected function _processPiWorkingInvoice(array $invoiceData)
    {
        return [
            'job_name' => '',
            'id' => trim($invoiceData['invoiceId']),
            'purchase_invoice' => trim($invoiceData['purchaseOrder']),
            'increment_id' => $invoiceData['invoiceId'],
            'real_invoice' => $invoiceData['invoiceId'],
            'created_at' => $invoiceData['invoiceDate'],
            'due_date' => trim($invoiceData['dueDate']),
            'amount' => $invoiceData['amount']['value'], // format Total
            'paid' => $invoiceData['paidToDate']['value'], // format Total
            'division' => $invoiceData['division'],
            'status' => $invoiceData['status'],
            'view_link' => $this->_getViewlink($invoiceData['invoiceId']),
            'reinvoice_link' => 'click here'
        ];
    }

//    /**
//     * @param array $invoiceData
//     * @return array
//     */
//    protected function _processPiInvoice(array $invoiceData)
//    {
//        return [
//            'job_name' => $this->_getJobName($invoiceData),
//            'id' => trim($invoiceData['Sales_Doc_Num']),
//            'increment_id' => $this->_getIncrementId($invoiceData),
//            'real_invoice' => $this->_getRealInvoice($invoiceData),
//            'created_at' => $this->_formatDate(trim($invoiceData['Created_On'])),
//            'created_at_timestamp' => strtotime(trim($invoiceData['Created_On'])),
//            'ship_to_name' => trim($invoiceData['Ship_To_Name']),
//            'total' => $this->_formatTotal(trim($invoiceData['Total'])), // format Total
//            'status' => $this->_getStatus($invoiceData['Source']),
//            'view_link' => $this->_getViewLink(trim($invoiceData['Sales_Doc_Num'])),
//            'reinvoice_link' => $this->_getReinvoiceLink($invoiceData)
//        ];
//    }

    /**
     * @param $salesDocNum
     * @return string
     */
    protected function _getViewlink($salesDocNum)
    {
        return $this->urlBuilder->getUrl('*/*/view', ['id' => $salesDocNum]);
    }

    protected function _sortInvoices()
    {
        if ($this->getSize() <= 1) {
            return;
        }
        /** @var $invoicea \Magento\Sales\Model\Invoice */
        /** @var $invoiceb \Magento\Sales\Model\Invoice */
        uasort($this->_items, function ($invoicea, $invoiceb) {
            $adate = $invoicea->getData('created_at');
            $bdate = $invoiceb->getData('created_at');
            $sortResult = $bdate <=> $adate;
            if ($sortResult === 0) {
                return $invoiceb->getData('id') <=> $invoicea->getData('id');
            }
            return $sortResult;
        });
    }

    /**
     * @param string $field
     * @param string | array $values
     * @return $this
     */
    public function addCustomFilter($field, $values)
    {
        $this->_customFilters[strtolower($field)] = $values;
        return $this;
    }

    /**
     * @param string|string[] $field
     * @return array|DataObject|void
     */
    public function getCustomFilter($field)
    {
        $customFilters = $this->getCustomFilters();
        if (is_array($field)) {
            // empty array: get all filters
            if (empty($field)) {
                return $customFilters;
            }
            // non-empty array: collect all filters that match specified field names
            $result = [];
            foreach ($customFilters as $filterField => $filter) {
                if ($filterField === $field) {
                    $result[] = $customFilters[$field];
                }
            }
            return $result;
        }
        if(isset($customFilters[$field]))
        {
            return $customFilters[$field];
        }
    }
    public function getCustomFilters()
    {
        return $this->_customFilters;
    }
}
