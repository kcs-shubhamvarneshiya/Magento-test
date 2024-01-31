<?php

namespace Capgemini\OrderView\Model;

use Capgemini\CompanyType\Model\Config;
use Capgemini\OrderView\Helper\Data;
use Capgemini\PartnersInsight\Model\Api\Payment as PiPaymentApi;
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

class PaymentList extends Collection
{
    /**
     * @var string
     */
    protected $customerNumber;

    /**
     * @var PiPaymentApi
     */
    protected $piPaymentApi;

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
     * @param PiPaymentApi $piPaymentApi
     * @param UrlInterface $urlBuilder
     * @param TimezoneInterface $localeDate
     * @param CurrencyFactory $currencyFactory
     * @param StoreManagerInterface $storeManager
     * @param Data $helper
     * @param LoggerInterface $logger
     * @param Config $companyTypeConfig
     * @param Json $jsonSerializer
     * @param RequestInterface $request
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        PiPaymentApi                                                $piPaymentApi,
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
        $this->piPaymentApi = $piPaymentApi;
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
        $paymentData = $this->_fetchPaymentData();
        // call salespad api here
        if (is_array($paymentData)) {
            foreach ($paymentData as $row) {
                    $payment = $this->getNewEmptyItem();
                    $payment->addData($row);
                    $this->addItem($payment);
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
    public function getPaymentSummary($customerNumber)
    {
        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);

        if ($customerType != Config::WHOLESALE) {
            if (!$customerNumber) {
                return [];
            }
        }

        $data = $this->_getPaymentSummary();

        if (!is_array($data)) {
            $rawData = [];
        } else {
            $rawData = $this->_processpayment($data ?: []);
        }
        return $rawData;
    }

    /**
     * @return array|null
     */
    protected function _fetchPaymentData()
    {
        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);

        if ($customerType != Config::WHOLESALE) {
            if (!$this->getCustomerNumber()) {
                return [];
            }
        }

        if ($this->rawData === null) {
            $data = $this->_getPayment();
            if ($customerType != Config::WHOLESALE) {
                $this->size = $data['count'];
            }

            if (!is_array($data)) {
                $this->rawData = [];
            } else {
                $this->rawData = $this->_processPayment($data ?: []);
            }
           return($this->rawData);

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

    protected function _getPayment()
    {
        // Getting customer type for determining which API to call.

        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);
        $allPayments = [];
        $customerId = null;
        $curPage = $this->request->getParam('p') ?? 1;
        $pageSize = $this->getPageSize();
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
                if($customerId != null)
                {
                    if ($this->request->getParam('search')) {
                        $payments = $this->piPaymentApi->getSearchPayment($customerId, $this->urlDecoder->decode($this->request->getParam('search')), $curPage, $pageSize);
                    } else if ($this->request->getParam('sort_by')) {
                        $sortby = $this->request->getParam('sort_by');
                        $payments = $this->piPaymentApi->getSortPayment($customerId, $curPage, $pageSize, $sortby);
                    } else {
                      $payments = $this->piPaymentApi->getPaymentList($customerId, $curPage, $pageSize);
                    }
                    if (!is_null($payments['data'])) {
                        foreach ($payments['data'] as $payment) {
                            $allPayments[] = $payment;
                        }
                    }
                    if(isset($payments['totalRecords']))
                    {
                        $this->setSize($payments['totalRecords']);
                    }
                    $keys = array_column($allPayments, 'paymentDate');
                }
                return $allPayments;
            } catch (\Exception $exception) {
                return $allPayments;
            }
        }
    }
    protected function _getPaymentSummary()
    {
        // Getting customer type for determining which API to call.

        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);
        $allPayments = [];
        $customerId = null;
        if ($customerType == Config::WHOLESALE) {
            try {
                $customerTypes = [];
                if($this->request->getParam('account_id'))
                {
                    $customerId = $this->request->getParam('account_id');
                }else {

                    if ($customer->getCustomAttribute('customer_number_vc')) {
                        $customerVcId = $customer->getCustomAttribute('customer_number_vc')->getValue();
                        if (!is_null($customerVcId)) {
                            $customerId = trim($customerVcId," ");
                        }
                    }

                    if ($customer->getCustomAttribute('customer_number_gl')) {
                        $customerGlId = $customer->getCustomAttribute('customer_number_gl')->getValue();
                        if (!is_null($customerGlId)) {
                            $customerId = trim($customerGlId, " ");
                        }
                    }

                    if ($customer->getCustomAttribute('customer_number_tech')) {
                        $customerTlId = $customer->getCustomAttribute('customer_number_tech')->getValue();

                        if (!is_null($customerTlId)) {
                            $customerId = trim($customerTlId, " ");
                        }
                    }
                }
                if($customerId !== null)
                {
                  $payments = $this->piPaymentApi->getPaymentSummary($customerId);
                    if (!is_null($payments['data'])) {
                        foreach ($payments['data'] as $payment) {
                            $allpayments[] = $payments;
                        }
                    }

                    $keys = array_column($allpayments, 'paymentDate');
                }
                return $allpayments;
            } catch (\Exception $exception) {
                return $allpayments;
            }
        }
    }

    protected function _processPayment(array $payments)
    {
        // Getting customer type for determining which API to call.
        $customer = $this->helper->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);

        if ($customerType == Config::WHOLESALE) {
            return $this->_processPiPayments($payments);
        }
    }

    /**
     * @param array $payments
     * @return array
     */
    protected function _processSalesPadPayments(array $payments)
    {
        $processed = [];
        $dotted = [];
        foreach ($payments as $payment) {
            $salesDocNum = trim($payment['Sales_Doc_Num']);

            if (substr($salesDocNum, -1, 1) === '.') {
                if ($payment['Source'] !== 'History' && $payments['Source'] !== 'Void') {
                    $dotted[] = $salesDocNum;
                }

                continue;
            }

            $processed[$salesDocNum] = $this->_processSalesPadPayment($payment);
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
     * @param array $payments
     * @return array
     */
    protected function _processPiPayments(array $payments)
    {
        $processed = [];
        $dotted = [];
        foreach ($payments as $payment) {
            $processed[$payment['paymentId']] = $this->_processPiWorkingPayment($payment);
        }

        return array_values($processed);
    }

    /**
     * @param array $paymentData
     * @return array
     */
    protected function _processPiWorkingPayment(array $paymentData)
    {
        return [
            'id' => trim($paymentData['paymentId']),
            'paymentId' => $paymentData['paymentId'],
            'created_at' => $paymentData['paymentDate'],
            'amount' => $paymentData['amount']['value'], // format Total
            'paymentMethod' => $paymentData['paymentMethod'],
            'referenceId' => $paymentData['referenceId'],
            //'view_link' => $this->_getViewlink($paymentData['paymentId']),
            //'recreditmemo_link' => 'click here'
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

    protected function _sortPayment()
    {
        if ($this->getSize() <= 1) {
            return;
        }
        /** @var $paymenta \Magento\Sales\Model\Payment */
        /** @var $paymentb \Magento\Sales\Model\Payment */
        uasort($this->_items, function ($paymenta, $paymentb) {
            $adate = date('Ymd', $paymenta->getData('created_at_timestamp'));
            $bdate = date('Ymd', $paymentb->getData('created_at_timestamp'));
            $sortResult = $bdate <=> $adate;
            if ($sortResult === 0) {
                return $paymentb->getData('id') <=> $paymenta->getData('id');
            }
            return $sortResult;
        });
    }
}
