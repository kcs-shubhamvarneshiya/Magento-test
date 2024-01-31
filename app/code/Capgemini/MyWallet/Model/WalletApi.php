<?php
/**
 * Wallet model
 *
 * @category  Capgemini
 * @package   Capgemini_MyWallet
 * @copyright 2020 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\MyWallet\Model;

/**
 * Class WalletApi
 * @package Capgemini\MyWallet\Model
 */
class WalletApi
{
    /**
     * @var
     */
    protected $requestHeaders;
    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $encryptor;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var Curl
     */
    protected $curl;
    /**
     * @var \Capgemini\Payfabric\Logger\Logger
     */
    protected $capgeminiLogger;
    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @var \Capgemini\MyWallet\Helper\Wallet
     */
    protected $walletHelper;

    /**
     * @var \Lyonscg\SalesPad\Helper\Sales
     */
    protected $salesHelper;

    /**
     * @var \Magento\Webapi\Controller\Rest\InputParamsResolver
     */
    protected $inputParamsResolver;

    const LIVE_API_URL = 'https://www.payfabric.com/';

    const SANDBOX_API_URL = 'https://sandbox.payfabric.com/';

    /**
     * WalletApi constructor.
     * @param \Magento\Framework\Encryption\EncryptorInterface $encryptor
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Capgemini\Payfabric\Model\Curl $curl
     * @param \Capgemini\Payfabric\Logger\Logger $capgeminiLogger
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Webapi\Controller\Rest\InputParamsResolver $inputParamsResolver
     * @param \Capgemini\MyWallet\Helper\Wallet $walletHelper
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function __construct(\Magento\Framework\Encryption\EncryptorInterface $encryptor,
                                \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
                                \Magento\Store\Model\StoreManagerInterface $storeManager,
                                \Capgemini\Payfabric\Model\Curl $curl,
                                \Capgemini\Payfabric\Logger\Logger $capgeminiLogger,
                                \Magento\Framework\Json\Helper\Data $jsonHelper,
                                \Magento\Webapi\Controller\Rest\InputParamsResolver $inputParamsResolver,
                                \Capgemini\MyWallet\Helper\Wallet $walletHelper,
                                \Lyonscg\SalesPad\Helper\Sales $salesHelper)
    {
        $this->inputParamsResolver = $inputParamsResolver;
        $this->jsonHelper = $jsonHelper;
        $this->capgeminiLogger = $capgeminiLogger;
        $this->curl = $curl;
        $this->encryptor = $encryptor;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->walletHelper = $walletHelper;
        $this->salesHelper = $salesHelper;

        $deviceId = $this->getConfigData('device_id');
        $password = $this->encryptor->decrypt(trim($this->getConfigData('device_password')));
        $this->httpHeaders = array(
            "Content-Type: application/json",
            "authorization: " . $deviceId . "|" . $password);
    }

    /**
     * @param $field
     * @param null $storeId
     * @return mixed
     */
    protected function getConfigData($field, $storeId = null)
    {
        $path = 'payment/payfabric/' . $field;

        return $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @return string
     */
    protected function getApiUrl()
    {
        if ($this->getConfigData('transaction_mode') == 'sandbox') {
            $apiUrl = self::SANDBOX_API_URL;
        } else {
            $apiUrl = self::LIVE_API_URL;
        }

        return $apiUrl;
    }

     /**
      * Retrieve Cards By CustomerId from PayFabric
      *
      * @param string $url
      * @param array $httpHeaders
      * @param string $customerId
      * @return array
      *
      */
    public function retrieveCardsByCustomerId($customerId)
    {
        $apiUrl = $this->getApiUrl() . "payment/api/wallet/get/";
        $apiUrl = $apiUrl . $customerId . "?tender=CreditCard";

        $this->capgeminiLogger->notice("Retrieve Cards By Customer API: ". $apiUrl);
        $this->curl->setConfig(['timeout'=>$this->getConfigData('request_timeout')])
            ->write(\Laminas\Http\Request::METHOD_GET, $apiUrl, '1.1', $this->httpHeaders);

        $responseBody = $this->curl->read();

        if ($this->curl->getErrno() !== 0) {
            $this->capgeminiLogger->error(
                "Curl Error[" . $this->curl->getErrno() . "]: " . $this->curl->getError(),
                ['method' => __METHOD__]
            );
        }

        if (!empty($responseBody)) {
            try {
                $responseArray = $this->jsonHelper->jsonDecode($responseBody);
            } catch (\Exception $e) {
                $this->capgeminiLogger->error(
                    'Response body decoding error' . $e->getMessage(),
                    ['method' => __METHOD__]
                );
                $responseArray = false;
            }
        } else {
            $responseArray = false;
        }

        return $responseArray;

    }

    /**
     * Check if Customer Wallet exist at PayFabric
     *
     * @param array $customerCards
     * @param string $cardNum
     * @param string $cardName
     * @param string $cardExpDate
     * @return array
     *
     */
    public function isCustomerWalletExist($customerCards, $cardNum, $cardName, $cardExpDate)
    {
        if (!empty($customerCards)) {
            foreach ($customerCards as $customerCard) {
                $customerLastDigits = $customerCard['Account'];
                $customerCardName   = $customerCard['CardName'];
                $customerExpDate    = $customerCard['ExpDate'];

                if ($customerLastDigits == $cardNum && $customerCardName == $cardName && $customerExpDate == $cardExpDate) {
                    $walletInfo = array('status' => true, 'data' => $customerCard);
                    return $walletInfo;
                }
            }
        }
        $walletInfo = array('status' => false, 'data' => '');
        return $walletInfo;
    }

    /**
     * @param $payment
     * @return mixed
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function createWallet($payment)
    {
        $walletUrl = $this->getApiUrl(). "payment/api/wallet/create";
        if ($payment instanceof \Magento\Payment\Model\InfoInterface) {
            $walletFields = $this->getWalletPostParams($payment);
        } else {
            $walletFields = $this->getWalletPostParamsFromFormData($payment);
        }

        $requestParams = $this->jsonHelper->jsonEncode($walletFields);

        $this->capgeminiLogger->notice("Wallet API Url: ". $walletUrl);
        $this->curl->setConfig(['timeout'=>$this->getConfigData('request_timeout')])
            ->write(\Laminas\Http\Request::METHOD_POST, $walletUrl, '1.1', $this->httpHeaders, $requestParams);
        $response  = $this->curl->read();

        if ($this->curl->getErrno() !== 0) {
            $this->capgeminiLogger->error(
                "Curl Error[" . $this->curl->getErrno() . "]: " . $this->curl->getError(),
                ['method' => __METHOD__]
            );
        }

        try {
            $walletApiResponse = $this->jsonHelper->jsonDecode($response);
        } catch (\Exception $e) {
            $walletApiResponse = $response;
        }

        return $walletApiResponse;
    }

    /**
     * @param $payfabricWalletId
     * @return mixed|string
     */
    public function deleteWallet($payfabricWalletId)
    {
        $deleteWalletUrl = $this->getApiUrl(). "payment/api/wallet/delete/" . $payfabricWalletId;

        $this->capgeminiLogger->notice("Delete Wallet API Url: ". $deleteWalletUrl);
        $this->curl->setConfig(['timeout'=>$this->getConfigData('request_timeout')])
            ->write(\Laminas\Http\Request::METHOD_GET, $deleteWalletUrl, '1.1', $this->httpHeaders);

        $response  = $this->curl->read();

        if ($this->curl->getErrno() !== 0) {
            $this->capgeminiLogger->error(
                "Curl Error[" . $this->curl->getErrno() . "]: " . $this->curl->getError(),
                ['method' => __METHOD__]
            );
        }

        try {
            $walletApiResponse = $this->jsonHelper->jsonDecode($response);
        } catch (\Exception $e) {
            $walletApiResponse = $response;
        }

        return $walletApiResponse;
    }

    public function updateWalletCustomerNumber($payfabricWalletId, $salespadCustomerNum)
    {
        $updateWalletUrl = $this->getApiUrl() . 'payment/api/wallet/update';
        $requestParams = $this->jsonHelper->jsonEncode([
            'ID' => $payfabricWalletId,
            'NewCustomerNumber' => $salespadCustomerNum
        ]);
        $this->capgeminiLogger->notice("Update Wallet: $payfabricWalletId : Customer_Num: $salespadCustomerNum");
        $this->curl->setConfig(['timeout' => $this->getConfigData('request_timeout')])
            ->write(\Laminas\Http\Request::METHOD_POST, $updateWalletUrl, '1.1', $this->httpHeaders, $requestParams);

        $response = $this->curl->read();

        if ($this->curl->getErrno() !== 0) {
            $this->capgeminiLogger->error(
                "Curl Error[" . $this->curl->getErrno() . "]: " . $this->curl->getError(),
                ['method' => __METHOD__]
            );
        }

        try {
            $walletApiResponse = $this->jsonHelper->jsonDecode($response);
        } catch (\Exception $e) {
            $walletApiResponse = $response;
        }

        return $walletApiResponse;
    }

    /**
     * @param $payment
     * @return array
     * @throws \Magento\Framework\Webapi\Exception
     * @throws \Exception
     */
    protected function getWalletPostParams($payment)
    {
        $inputParams = $this->inputParamsResolver->resolve();
        foreach ($inputParams as $inputParam) {
            if ($inputParam instanceof \Magento\Quote\Model\Quote\Payment) {
                $paymentData = $inputParam->getData('additional_data');
                $payment->setCcOwner(trim($paymentData['name_on_card']));
            }
        }

        $order = $payment->getOrder();
        $billingAddress = $order->getBillingAddress();
        $cardOwnerName = explode(" ",$payment->getCcOwner());

        $cardholderFirstname = $cardOwnerName[0];
        $cardholderLastname = "";
        if (isset($cardOwnerName[1])) {
            $cardholderLastname = $cardOwnerName[1];
        }
        $year = substr($payment->getCcExpYear(), -2);
        $month = $payment->getCcExpMonth();
        if (strlen($payment->getCcExpMonth()) == 1) {
            $month = "0" . $month;
        }

        // CLMI-952 - use SalesPad Customer_Num for the customer
        $customerEntityId = $order->getCustomerId();
        $email = $order->getCustomerEmail();
        try {
            $websiteId = $this->storeManager->getStore($order->getStoreId())->getWebsiteId();
        } catch (\Exception $exception) {
            $this->capgeminiLogger->error($exception->getMessage(), ['Class' => get_class($this), 'Line' => $exception->getLine()]);
            throw new \Exception($exception->getMessage());
        }
        $customerId = $this->salesHelper->getSalespadCustomerNumberByIdentifiers($customerEntityId, $email, $websiteId);

        if (!$customerId) {
            // no customer number yet, so put something here
            if ($order->getCustomerIsGuest()) {
                $customerId = $order->getIncrementId();
            } else {
                $customerId = $order->getCustomerId();
            }
        }

        $addressArray = Array(
            "Customer" => $customerId,
            "Line1" => $billingAddress->getData('street'),
            "City" => $billingAddress->getData('city'),
            "State" => $billingAddress->getData('region'),
            "Country" => $billingAddress->getData('country_id'),
            "Zip" => $billingAddress->getData('postcode'));

        $cardHolderArray = Array(
            "FirstName" => $cardholderFirstname,
            "LastName" => $cardholderLastname);

        $cardArray = Array(
            "Tender" => "CreditCard",
            "Customer" => $customerId,
            "Account" => $payment->getCcNumber(),
            "ExpDate" => $month . $year,
            "CardHolder" => $cardHolderArray,
            "Billto" => $addressArray);
        return $cardArray;
    }

    /**
     * @param $formData
     * @return array
     */
    public function getWalletPostParamsFromFormData($formData)
    {
        $billingAddress = $this->walletHelper->getCustomerAddressById($formData['billing_address_id']);
        $cardOwnerName = explode(" ",$formData['cc_holder_name']);
        $cardholderFirstname = $cardOwnerName[0];
        $cardholderLastname = "";
        if (isset($cardOwnerName[1])) {
            $cardholderLastname = $cardOwnerName[1];
        }
        $year = substr($formData['cc_expiration_year'], -2);
        $month = $formData['cc_expiration_month'];
        if (strlen($formData['cc_expiration_month']) == 1) {
            $month = "0" . $month;
        }

        $customerId = false;
        $customerEntity = $billingAddress->getCustomer();
        $email = $customerEntity ? $customerEntity->getEmail() : null;
        if ($email) {
            $customerEntityId = $customerEntity ? $customerEntity->getId() : null;
            $websiteId = $customerEntity ? $customerEntity->getId() : null;
            $customerId = $this->salesHelper->getSalespadCustomerNumberByIdentifiers($customerEntityId, $email, $websiteId);
        }
        if (!$customerId) {
            $customerId = $this->walletHelper->getCustomerId();
        }

        $addressArray = Array(
            "Customer" => $customerId,
            "Line1" => $billingAddress->getData('street'),
            "City" => $billingAddress->getData('city'),
            "State" => $billingAddress->getData('region'),
            "Country" => $billingAddress->getData('country_id'),
            "Zip" => $billingAddress->getData('postcode'));

        $cardHolderArray = Array(
            "FirstName" => $cardholderFirstname,
            "LastName" => $cardholderLastname);

        $cardArray = Array(
            "Tender" => "CreditCard",
            "Customer" => $customerId,
            "Account" => $formData['cc_number'],
            "ExpDate" => $month . $year,
            "CardHolder" => $cardHolderArray,
            "Billto" => $addressArray);

        return $cardArray;
    }
}
