<?php

namespace Capgemini\OrderView\Model;

use Capgemini\CompanyType\Model\Config;
use Capgemini\OrderView\Helper\Data;
use Capgemini\PartnersInsight\Model\Api\Order as PiOrderApi;
use Lyonscg\SalesPad\Helper\Order as OrderHelper;
use Lyonscg\SalesPad\Model\Api\Order as OrderApi;
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

class OrderList extends Collection
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
     * @var OrderApi
     */
    protected $orderApi;

    /**
     * @var PiOrderApi
     */
    protected $piOrderApi;

    /**
     * @var OrderHelper
     */
    protected $orderHelper;

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
     * @var DecoderInterface
     */
    protected DecoderInterface $urlDecoder;

    /**
     * @var Json
     */
    protected $jsonSerializer;
    private EntityFactoryInterface $entityFactory;

    /**
     * @param EntityFactoryInterface $entityFactory
     * @param OrderApi $orderApi
     * @param PiOrderApi $piOrderApi
     * @param OrderHelper $orderHelper
     * @param UrlInterface $urlBuilder
     * @param TimezoneInterface $localeDate
     * @param CurrencyFactory $currencyFactory
     * @param StoreManagerInterface $storeManager
     * @param Data $helper
     * @param LoggerInterface $logger
     * @param Config $companyTypeConfig
     * @param Json $jsonSerializer
     * @param RequestInterface $request
     * @param DecoderInterface $urlDecoder
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        OrderApi                                                  $orderApi,
        PiOrderApi                                                $piOrderApi,
        OrderHelper                                               $orderHelper,
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
        $this->orderApi = $orderApi;
        $this->piOrderApi = $piOrderApi;
        $this->orderHelper = $orderHelper;
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
        $orderData = $this->_fetchOrderData();
        // call salespad api here
        if (is_array($orderData)) {
            foreach ($orderData as $row) {
                    $order = $this->getNewEmptyItem();
                    $order->addData($row);
                    $this->addItem($order);
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
        $customerNum = ($customerNum !=null)?trim($customerNum," "):$customerNum;
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
     * @param $customerNumber
     * @return array
     */
    public function getOrderSummary($customerNumber)
    {
        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);
        $rawData = [];
        if ($customerType != Config::WHOLESALE) {
            if (!$customerNumber) {
                return [];
            }
        }

            $data = $this->_getOrderSummary();
            if (!is_array($data)) {
                $rawData = [];
            } else {
                $rawData = $this->_processOrders($data ?: []);
            }

          return $rawData;

    }
    /**
     * @return array|null
     */
    protected function _fetchOrderData()
    {
        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);

        if ($customerType != Config::WHOLESALE) {
            if (!$this->getCustomerNumber()) {
                return [];
            }
        }

        if ($this->rawData === null) {
            $data = $this->_getOrders();
            if ($customerType != Config::WHOLESALE) {
                $this->size = $data['count'];
            }
            if (!is_array($data)) {
                $this->rawData = [];
            } else {
                $this->rawData = $this->_processOrders($data ?: []);
            }
        }

        return $this->rawData;
    }
     public function _getFilters() {
        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);
        $accounts = [];
        if ($customerType == Config::WHOLESALE) {
            try {
                $customerTypes = [];
                if ($customer->getCustomAttribute('customer_number_vc')) {
                    $customerVcId = $customer->getCustomAttribute('customer_number_vc')->getValue();
                    if (!is_null($customerVcId)) {
                        $customerTypes[] = trim($customerVcId, " ");
                    }
                }
                if ($customer->getCustomAttribute('customer_number_gl')) {
                    $customerGlId = $customer->getCustomAttribute('customer_number_gl')->getValue();
                    if (!is_null($customerGlId)) {
                        $customerTypes[] = trim($customerGlId, " ");
                    }
                }
                if ($customer->getCustomAttribute('customer_number_tech')) {
                    $customerTlId = $customer->getCustomAttribute('customer_number_tech')->getValue();
                    if (!is_null($customerTlId)) {
                        $customerTypes[] = trim($customerTlId, " ");
                    }
                }
                $customerId = implode(",", $customerTypes);
                $customer_sales_rep = $customer->getCustomAttribute('customer_sales_rep')?->getValue();
                $customer_sales_subrep = $customer->getCustomAttribute('customer_sales_subrep')?->getValue();
                $accounts = $this->piOrderApi->getCurrentCustomerAccounts($customerId,$customer_sales_rep,$customer_sales_subrep);
                if (!is_null($accounts['data'])) {
                    return $accounts['data'];
                }else{
                    return [];
                }
            } catch (\Exception $exception) {
                return $accounts;
            }
        }
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

    protected function _getOrders()
    {
        // Getting customer type for determining which API to call.

        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);
        $allOrders = [];

        if ($customerType == Config::WHOLESALE) {
            try {
                if($this->request->getParam('account_id'))
                {
                    $customerId = $this->request->getParam('account_id');
                }else{
                    if ($customer->getCustomAttribute('customer_number_vc') && $customer->getCustomAttribute('customer_number_vc')->getValue()!= null) {
                        $customerVcId = $customer->getCustomAttribute('customer_number_vc')->getValue();
                        if (!is_null($customerVcId)) {
                            $customerId = trim($customerVcId," ");
                        }
                    }else if ($customer->getCustomAttribute('customer_number_gl') && $customer->getCustomAttribute('customer_number_gl')->getValue()!= null) {
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
                    $curPage = $this->request->getParam('p') ?? 1;
                    $pageSize = $this->getPageSize();
                    $apiFilters = $this->getApiFilters();
                    if ($this->request->getParam('search')) {
                        $orders = $this->piOrderApi->getSearchOrders($customerId, $this->urlDecoder->decode($this->request->getParam('search')), $curPage, $pageSize);
                    } else if ($this->request->getParam('sort_by')){
                        if($this->request->getParam('orderstatus')){

                            $status = $this->request->getParam('orderstatus');
                            }else{
                                $status = 'Open,Closed,Partial';
                            }
                     
                            $sortby = $this->request->getParam('sort_by');
                      
                          $orders = $this->piOrderApi->getSortOrders($customerId, $status, $curPage, $pageSize, $sortby);
                     
                      }  else {
                        $orders = $this->piOrderApi->getAllOrders($customerId, $apiFilters, $curPage, $pageSize);
                    }

                    if (!is_null($orders['data'])) {
                        foreach ($orders['data'] as $order) {
                            $allOrders[] = $order;
                        }
                    }
                    if(isset($orders['totalRecords'])){
                        $this->setSize($orders['totalRecords']);
                    }
                    if (isset($orders['filters'])) {
                        foreach ($orders['filters'] as $field => $values) {
                            $this->addCustomFilter($field, $values);
                        }
                    }

                    $keys = array_column($allOrders, 'orderDate');
                }
                return $allOrders;
            } catch (\Exception $exception) {
                return $allOrders;
            }
        }

        $orders = $this->orderApi->getOrdersForCustomer($this->getCustomerNumber(), $this->_curPage, $this->_pageSize);
        return $orders;
    }

    /**
     * @return array|int[]
     */
    protected function _getOrderSummary()
    {
        // Getting customer type for determining which API to call.

        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);
        $allOrders = [];
        $customerId = null;
        if ($customerType == Config::WHOLESALE) {
            try {
                if($this->request->getParam('account_id'))
                {
                    $customerId = $this->request->getParam('account_id');
                }else{

                    $customerTypes = [];
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
                        $searchTermString = ($this->request->getParam('search')) ? $this->urlDecoder->decode($this->request->getParam('search')): NULL;
                        $orders = $this->piOrderApi->getOrderSummarySearch($customerId, $searchTermString);
                    }
                    else{
                        $orders = $this->piOrderApi->getOrderSummary($customerId);
                    }

                    if (!is_null($orders['data'])) {
                        foreach ($orders['data'] as $order) {
                            $allOrders[] = $order;
                        }
                    }
                    $keys = array_column($allOrders, 'orderDate');
                    array_multisort($keys, SORT_DESC, $allOrders);
                }
                return $allOrders;
            } catch (\Exception $exception) {
                return $allOrders;
            }
        }

        $orders = $this->orderApi->getOrdersForCustomer($this->getCustomerNumber(), 1, 4);
        return $orders;
    }

    protected function _processOrders(array $orders)
    {
        // Getting customer type for determining which API to call.
        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);

        if ($customerType == Config::WHOLESALE) {
            return $this->_processPiOrders($orders);
        }
        $orders = $orders['items'] ?? [];
        $orders = is_array($orders) ? $orders : [];
        return $this->_processSalesPadOrders($orders);
    }

    /**
     * @param array $orders
     * @return array
     */
    protected function _processSalesPadOrders(array $orders)
    {
        $processed = [];
        $dotted = [];
        foreach ($orders as $order) {
            $salesDocNum = trim($order['Sales_Doc_Num']);

            if (substr($salesDocNum, -1, 1) === '.') {
                if ($order['Source'] !== 'History' && $order['Source'] !== 'Void') {
                    $dotted[] = $salesDocNum;
                }

                continue;
            }

            $processed[$salesDocNum] = $this->_processSalesPadOrder($order);
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
     * @param array $orderData
     * @return array
     */
    protected function _processSalesPadOrder(array $orderData)
    {
        return [
            'job_name' => $this->_getJobName($orderData),
            'id' => trim($orderData['Sales_Doc_Num']),
            'purchase_order' => '',
            'increment_id' => $this->_getIncrementId($orderData),
            'real_order' => $this->_getRealOrder($orderData),
            'created_at' => $this->_formatDate(trim($orderData['Created_On'])),
            'created_at_timestamp' => strtotime(trim($orderData['Created_On'])),
            'ship_to_name' => trim($orderData['Ship_To_Name']),
            'total' => $this->_formatTotal(trim($orderData['Total'])), // format Total
            'status' => $this->_getStatus($orderData['Source']),
            'view_link' => $this->_getViewLink(trim($orderData['Sales_Doc_Num'])),
            'reorder_link' => $this->_getReorderLink($orderData)
        ];
    }

    /**
     * @param array $orders
     * @return array
     */
    protected function _processPiOrders(array $orders)
    {
        $processed = [];
        $dotted = [];
        foreach ($orders as $order) {
            $processed[$order['orderId']] = $this->_processPiWorkingOrder($order);
        }

        return array_values($processed);
    }

    /**
     * @param array $orderData
     * @return array
     */
    protected function _processPiWorkingOrder(array $orderData)
    {
        $orderQty = '';
        $billToAddressAccountId = '';
        // $orderId = trim($orderData['orderId']);

        // $orderDatabyId = $this->piOrderApi->getOrder($orderId);

        // if(isset($orderDatabyId['data']['orderLines'][0]['shipping']) && !empty($orderDatabyId['data']['orderLines'][0]['shipping'])){

        //     $orderQty = $orderDatabyId['data']['orderLines'][0]['shipping']['quantityOrdered'];

        // }

        if(isset($orderData['billToAddress']['id']) && !empty($orderData['billToAddress']['id'])){

            $billToAddressAccountId = $orderData['billToAddress']['id'];

        }



        return [
            'job_name' => '',
            'id' => trim($orderData['orderId']),
            'purchase_order' => trim($orderData['purchaseOrder']),
            'increment_id' => $orderData['orderId'],
            'real_order' => $orderData['orderId'],
            'created_at' => $orderData['orderDate'],
            'created_at_timestamp' => strtotime(trim($orderData['orderDate'])),
            'ship_to_name' => trim($orderData['shipToAddress']['name']),
            'bill_to_name' =>trim($orderData['billToAddress']['name']),
            'total' => '', // format Total
            'status' => $orderData['orderStatus'],
            'view_link' => $this->_getViewlink($orderData['orderId']),
            'reorder_link' => 'click here',
            'account_id' => $billToAddressAccountId
        ];
    }

//    /**
//     * @param array $orderData
//     * @return array
//     */
//    protected function _processPiOrder(array $orderData)
//    {
//        return [
//            'job_name' => $this->_getJobName($orderData),
//            'id' => trim($orderData['Sales_Doc_Num']),
//            'increment_id' => $this->_getIncrementId($orderData),
//            'real_order' => $this->_getRealOrder($orderData),
//            'created_at' => $this->_formatDate(trim($orderData['Created_On'])),
//            'created_at_timestamp' => strtotime(trim($orderData['Created_On'])),
//            'ship_to_name' => trim($orderData['Ship_To_Name']),
//            'total' => $this->_formatTotal(trim($orderData['Total'])), // format Total
//            'status' => $this->_getStatus($orderData['Source']),
//            'view_link' => $this->_getViewLink(trim($orderData['Sales_Doc_Num'])),
//            'reorder_link' => $this->_getReorderLink($orderData)
//        ];
//    }

    /**
     * @param $orderData
     * @return mixed|string
     */
    protected function _getIncrementId($orderData)
    {
        return $this->orderHelper->getIncrementId($orderData);
    }

    /**
     * @param $orderData
     * @return string
     */
    protected function _getJobName($orderData)
    {
        return trim($this->orderHelper->getJobName($orderData));
    }

    /**
     * @param $orderData
     * @return \Magento\Sales\Model\Order
     */
    protected function _getRealOrder($orderData)
    {
        $incrementId = $this->orderHelper->getIncrementId($orderData, false);
        return $this->orderHelper->getRealOrder($incrementId);
    }

    /**
     * @param $salesDocNum
     * @return string
     */
    protected function _getViewlink($salesDocNum)
    {
        return $this->urlBuilder->getUrl('*/*/view', ['id' => $salesDocNum]);
    }

    /**
     * @param $dateString
     * @return string
     */
    protected function _formatDate($dateString)
    {
        return $this->localeDate->formatDate($dateString, \IntlDateFormatter::SHORT);
    }

    /**
     * @param $total
     * @return mixed
     */
    protected function _formatTotal($total)
    {
        return $this->_getCurrency()
            ->setCurrencyCode($this->getCurrencyCode())
            ->formatPrecision($total, 2, [], true, false);
    }

    /**
     * @param array $orderData
     * @return bool
     */
    protected function _getReorderLink(array $orderData)
    {
        // TODO - if we support reorder links, get them here
        return false;
    }

    /**
     * @param $source
     * @return \Magento\Framework\Phrase
     */
    protected function _getStatus($source)
    {
        $source = trim(strtolower($source));
        switch ($source) {
            case 'open':
                return __('Pending');
            case 'history':
                return __('Complete');
            case 'void':
                return __('Canceled');
            default:
                return __('Pending');
        }
    }

    /**
     * @return \Magento\Directory\Model\Currency
     */
    protected function _getCurrency()
    {
        if ($this->currency === null) {
            $this->currency = $this->currencyFactory->create();
        }
        return $this->currency;
    }

    protected function _sortOrders()
    {
        if ($this->getSize() <= 1) {
            return;
        }
        /** @var $ordera \Magento\Sales\Model\Order */
        /** @var $orderb \Magento\Sales\Model\Order */
        uasort($this->_items, function ($ordera, $orderb) {
            $adate = $ordera->getData('created_at');
            $bdate = $ordera->getData('created_at');
            $sortResult = $bdate <=> $adate;
            if ($sortResult === 0) {
                return $orderb->getData('id') <=> $ordera->getData('id');
            }
            return $sortResult;
        });
    }

    /**
     * @param array $filtersParams
     * @return $this
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
     * @return array|void
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
