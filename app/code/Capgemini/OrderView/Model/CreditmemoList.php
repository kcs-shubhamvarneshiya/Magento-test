<?php

namespace Capgemini\OrderView\Model;

use Capgemini\CompanyType\Model\Config;
use Capgemini\OrderView\Helper\Data;
use Capgemini\PartnersInsight\Model\Api\Creditmemo as PiCreditmemoApi;
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

class CreditmemoList extends Collection
{
    /**
     * @var string
     */
    protected $customerNumber;

    /**
     * @var PiCreditmemoApi
     */
    protected $piCreditmemoApi;

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
     * @param PiCreditmemoApi $piCreditmemoApi
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
        PiCreditmemoApi                                                $piCreditmemoApi,
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
        $this->piCreditmemoApi = $piCreditmemoApi;
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
        $creditmemoData = $this->_fetchCreditmemoData();
        // call salespad api here
        if (is_array($creditmemoData)) {
            foreach ($creditmemoData as $row) {
                    $creditmemo = $this->getNewEmptyItem();
                    $creditmemo->addData($row);
                    $this->addItem($creditmemo);
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
    public function getCreditMemoSummary($customerNumber)
    {
        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);

        if ($customerType != Config::WHOLESALE) {
            if (!$customerNumber) {
                return [];
            }
        }

        $data = $this->_getCreditMemoSummary();

        if (!is_array($data)) {
            $rawData = [];
        } else {
            $rawData = $this->_processCreditmemos($data ?: []);
        }

       return $rawData;

    }

    /**
     * @return array|null
     */
    protected function _fetchCreditmemoData()
    {
        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);

        if ($customerType != Config::WHOLESALE) {
            if (!$this->getCustomerNumber()) {
                return [];
            }
        }

        if ($this->rawData === null) {
            $data = $this->_getCreditmemos();
            if ($customerType != Config::WHOLESALE) {
                $this->size = $data['count'];
            }
            if (!is_array($data)) {
                $this->rawData = [];
            } else {
                $this->rawData = $this->_processCreditmemos($data ?: []);
            }
        }

        return $this->rawData;
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

    protected function _getCreditmemos()
    {
        // Getting customer type for determining which API to call.

        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);
        $allCreditmemos = [];
        $customerId = null;

        $pageSize = $this->getPageSize();
        $curPages = $this->request->getParam('p') ?? 1;
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
                if($customerId !=null) {
                    if ($this->request->getParam('search')) {
                        $creditmemos = $this->piCreditmemoApi->getSearchCreditmemos($customerId, $this->urlDecoder->decode($this->request->getParam('search')), $curPages, $pageSize);
                    } else if ($this->request->getParam('sort_by')){
                        $sortby = $this->request->getParam('sort_by');
                          $creditmemos = $this->piCreditmemoApi->getSortCreditmemos($customerId, $curPages, $pageSize, $sortby);
                       } else {
                        $creditmemos = $this->piCreditmemoApi->getAllCreditmemos($customerId, $curPages, $pageSize);
                    }
                    
                    if (!is_null($creditmemos['data'])) {
                        foreach ($creditmemos['data'] as $creditmemo) {
                           
                            $allCreditmemos[] = $creditmemo;
                        }
                        
                    }
                    if(isset($creditmemos['totalRecords']))
                    {
                        $this->setSize($creditmemos['totalRecords']);
                    }

                    // $keys = array_column($allCreditmemos, 'creditMemoDate');
                }
                // print_r($allCreditmemos);
                // exit;
                return $allCreditmemos;
                
            } catch (\Exception $exception) {
                return $allCreditmemos;
            }
        }
    }
    protected function _getCreditMemoSummary()
    {
        // Getting customer type for determining which API to call.

        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);
        $allCreditmemos = [];
        $customerId = null;
        if ($customerType == Config::WHOLESALE) {
            try {
                $customerTypes = [];
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
                        $searchTermString = ($this->request->getParam('search')) ? $this->urlDecoder->decode($this->request->getParam('search')): NULL;
                        $creditmemos = $this->piCreditmemoApi->getCreditMemoSummarySearch($customerId, $searchTermString);
                    }
                    else{
                        $creditmemos = $this->piCreditmemoApi->getCreditMemoSummary($customerId);
                    }
                    if (!is_null($creditmemos['data'])) {
                        foreach ($creditmemos['data'] as $creditmemo) {
                            $allCreditmemos[] = $creditmemo;
                        }
                    }

                    $keys = array_column($allCreditmemos, 'creditMemoDate');
                    array_multisort($keys, SORT_DESC, $allCreditmemos);
                }
                return $allCreditmemos;
            } catch (\Exception $exception) {
                return $allCreditmemos;
            }
        }
    }

    protected function _processCreditmemos(array $creditmemos)
    {
        // Getting customer type for determining which API to call.
        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);

        if ($customerType == Config::WHOLESALE) {
            return $this->_processPiCreditmemos($creditmemos);
        }
    }

    /**
     * @param array $creditmemos
     * @return array
     */
    protected function _processSalesPadCreditmemos(array $creditmemos)
    {
        $processed = [];
        $dotted = [];
        foreach ($creditmemos as $creditmemo) {
            $salesDocNum = trim($creditmemo['Sales_Doc_Num']);

            if (substr($salesDocNum, -1, 1) === '.') {
                if ($creditmemo['Source'] !== 'History' && $creditmemo['Source'] !== 'Void') {
                    $dotted[] = $salesDocNum;
                }

                continue;
            }

            $processed[$salesDocNum] = $this->_processSalesPadCreditmemo($creditmemo);
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
     * @param array $creditmemos
     * @return array
     */
    protected function _processPiCreditmemos(array $creditmemos)
    {
        $processed = [];
        $dotted = [];
        foreach ($creditmemos as $creditmemo) {
            $processed[$creditmemo['creditMemoId']] = $this->_processPiWorkingCreditmemo($creditmemo);
        }

        return array_values($processed);
    }

    /**
     * @param array $creditmemoData
     * @return array
     */
    protected function _processPiWorkingCreditmemo(array $creditmemoData)
    {
        return [
            'job_name' => '',
            'id' => trim($creditmemoData['creditMemoId']),
            'purchase_order' => trim($creditmemoData['purchaseOrder']),
            'increment_id' => $creditmemoData['creditMemoId'],
            'created_at' => $creditmemoData['creditMemoDate'],
            'amount' => $creditmemoData['amount']['value'], // format Total
            'division' => $creditmemoData['division'],
            'status' => $creditmemoData['status'],
            'view_link' => $this->_getViewlink($creditmemoData['creditMemoId']),
            'recreditmemo_link' => 'click here'
        ];
    }

    /**
     * @param $salesDocNum
     * @return string
     */
    protected function _getViewlink($salesDocNum)
    {
        return $this->urlBuilder->getUrl('*/*/view', ['id' => $salesDocNum]);
    }

    protected function _sortCreditMemos()
    {
        if ($this->getSize() <= 1) {
            return;
        }
        /** @var $creditmemoa \Magento\Sales\Model\Creditmemo */
        /** @var $creditmemob \Magento\Sales\Model\Creditmemo */
        uasort($this->_items, function ($creditmemoa, $creditmemob) {
            $adate = $creditmemoa->getData('created_at');
            $bdate = $creditmemob->getData('created_at');
            $sortResult = $bdate <=> $adate;
            if ($sortResult === 0) {
                return $creditmemob->getData('id') <=> $creditmemoa->getData('id');
            }
            return $sortResult;
        });
    }
}
