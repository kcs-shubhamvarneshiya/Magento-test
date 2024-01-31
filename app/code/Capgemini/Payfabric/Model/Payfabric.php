<?php
/**
 * Capgemini_Payfabric
 *
 * @category   Capgemini
 * @author    Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2020 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\Payfabric\Model;

use Capgemini\MyWallet\Api\WalletRepositoryInterface;
use Capgemini\MyWallet\Helper\Wallet;
use Capgemini\Payfabric\Helper\IgnoreValidatedRecaptcha;
use Exception;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Json\Helper\Data;

/**
 * Class Payfabric
 * @package Capgemini\Payfabric\Model
 */
class Payfabric extends \Magento\Payment\Model\Method\Cc
{
    const METHOD_CODE                   = 'payfabric';

    protected $_code                    = self::METHOD_CODE;

    protected $_isGateway               = true;

    protected $_canAuthorize            = true;

    protected $_canCapture              = true;

    protected $_canCapturePartial       = false;

    protected $_canRefund               = false;

    protected $_canVoid                 = false;

    protected $_canUseInternal          = true;

    protected $_canUseCheckout          = true;

    protected $_canSaveCc               = false;

    const STATUS_APPROVED = 'Approved';

    const PAYMENT_ACTION_AUTH_CAPTURE = 'Sale';

    const PAYMENT_ACTION_AUTH = 'Authorization';

    const LIVE_API_URL = 'https://www.payfabric.com/';

    const SANDBOX_API_URL = 'https://sandbox.payfabric.com/';



    /**
     * @var \Capgemini\Payfabric\Helper\Data
     */
    protected $payfabricHelper;

    /**
     * @var \Capgemini\Payfabric\Logger\Logger
     */
    protected $capgeminiLogger;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $encryptor;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @var array
     */
    protected $httpHeaders = array();

    /**
     * @var Data
     */
    protected $jsonHelper;

    /**
     * @var \Capgemini\MyWallet\Model\WalletApi
     */
    protected $walletApi;

    /**
     * @var \Capgemini\MyWallet\Api\Data\WalletInterface
     */
    protected $wallet;

    /**
     * @var WalletRepositoryInterface
     */
    protected $walletRepository;

    /**
     * @var Wallet
     */
    protected $walletHelper;

    /**
     * @var \Magento\Webapi\Controller\Rest\InputParamsResolver
     */
    protected $inputParamsResolver;

    /**
     * @var bool
     */
    protected $walletId = false;

    /**
     * Payfabric constructor.
     * @param \Capgemini\MyWallet\Api\Data\WalletInterface $wallet
     * @param WalletRepositoryInterface $walletRepository
     * @param \Capgemini\MyWallet\Model\WalletApi $walletApi
     * @param Wallet $walletHelper
     * @param \Capgemini\Payfabric\Helper\Data $payfabricHelper
     * @param \Capgemini\Payfabric\Logger\Logger $capgeminiLogger
     * @param \Magento\Webapi\Controller\Rest\InputParamsResolver $inputParamsResolver
     * @param Data $jsonHelper
     * @param Curl $curl
     * @param \Magento\Framework\Encryption\EncryptorInterface $encryptor
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Payment\Model\Method\Logger $logger
     * @param \Magento\Framework\Module\ModuleListInterface $moduleList
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Capgemini\MyWallet\Api\Data\WalletInterface $wallet,
        \Capgemini\MyWallet\Api\WalletRepositoryInterface $walletRepository,
        \Capgemini\MyWallet\Model\WalletApi $walletApi,
        \Capgemini\MyWallet\Helper\Wallet $walletHelper,
        \Capgemini\Payfabric\Helper\Data $payfabricHelper,
        \Capgemini\Payfabric\Logger\Logger $capgeminiLogger,
        \Magento\Webapi\Controller\Rest\InputParamsResolver $inputParamsResolver,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Capgemini\Payfabric\Model\Curl $curl,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $moduleList,
            $localeDate,
            $resource,
            $resourceCollection,
            $data
        );
        $this->wallet = $wallet;
        $this->walletRepository  = $walletRepository;
        $this->walletApi = $walletApi;
        $this->walletHelper =$walletHelper;
        $this->payfabricHelper = $payfabricHelper;
        $this->capgeminiLogger = $capgeminiLogger;
        $this->jsonHelper = $jsonHelper;
        $this->encryptor = $encryptor;
        $this->urlBuilder = $urlBuilder;
        $this->request = $request;
        $this->curl = $curl;
        $this->_storeManager = $storeManager;
        $this->inputParamsResolver = $inputParamsResolver;
    }

    /**
     * @return $this|\Magento\Payment\Model\Method\Cc
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function validate()
    {
        $inputParams = IgnoreValidatedRecaptcha::wrap($this->inputParamsResolver, 'resolve');
        foreach ($inputParams as $inputParam) {
            if ($inputParam instanceof \Magento\Quote\Model\Quote\Payment) {
                $paymentData = $inputParam->getData('additional_data');
            }
        }
        if (!isset($paymentData['wallet_id'])){
            parent::validate();
        } else {
            $this->walletId = $paymentData['wallet_id'];
        }
        $paymentInfo = $this->getInfoInstance();
        if ($paymentInfo instanceof \Magento\Sales\Model\Order\Payment) {
            $paymentInfo->getOrder()->getBaseCurrencyCode();
        } else {
            $paymentInfo->getQuote()->getBaseCurrencyCode();
        }
        return $this;
    }

    /**
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param $amount
     * @param $transactionType
     * @return array
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Webapi\Exception
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    protected function processRequest(\Magento\Payment\Model\InfoInterface $payment, $amount, $transactionType)
    {
        $returnResponse = [];
        $order = $payment->getOrder();

        if ($order->getIncrementId()) {
            $this->capgeminiLogger->notice("Order # ". $order->getIncrementId());
        }

        $setupId = $this->getConfigData('setup_id');

        $apiUrl = $this->getApiUrl();

        $apiUrl = $apiUrl . "payment/api/transaction/process?cvc=" . $payment->getCcCid();
        if ($this->walletId) {
            $payment = $this->preparePaymentInfoFromWallet($this->walletId, $payment);
        }
        $fields = $this->payfabricHelper->getRequestFields($payment, $amount, $setupId, $transactionType);
        if (empty($fields['Card']['ID'])) {
            $cardId = $this->processCustomerCards($fields['Card']);
            if (!empty($cardId)) {
                $card = [
                    "ID" => $cardId,
                    "IsSaveCard" => true
                ];
                $fields['Card'] = $card;
            }
        }
        $requestParams = $this->jsonHelper->jsonEncode($fields);

        $this->capgeminiLogger->notice("Calling PayFabric API");

        $this->curl->setConfig(['timeout'=>$this->getConfigData('request_timeout')])
            ->write(\Laminas\Http\Request::METHOD_POST, $apiUrl, '1.1', $this->httpHeaders, $requestParams);
        $responseString = $this->curl->read();

        if ($this->curl->getErrno() !== 0) {
            $this->capgeminiLogger->error(
                "Curl Error[" . $this->curl->getErrno() . "]: " . $this->curl->getError(),
                ['method' => __METHOD__]
            );
            throw new \Magento\Payment\Gateway\Command\CommandException(__($this->getConfigData('failed_request_error')));
        }

        $responseArray = $this->jsonHelper->jsonDecode($responseString);
        if ($responseArray == null) {
            $returnResponse['result'] = false;
            $returnResponse['message'] = "Could not connect with Payment Gateway. Please contact with administrator.";

            $this->capgeminiLogger->error("Error: ". $returnResponse['message']);
            return $returnResponse;
        }
        if ($responseArray['Status'] == "Approved") {
            $returnResponse['result'] = true;
            $returnResponse['status'] = $responseArray['Status'];
            $returnResponse['message'] = $responseArray['Message'];
            $returnResponse['trxkey'] = $responseArray['TrxKey'];
            $order->setTransactionId($responseArray['TrxKey']);
            $this->capgeminiLogger->notice("Success: ". $responseArray['Message']);
            $this->capgeminiLogger->notice("Transaction ID: ". $responseArray['TrxKey']);

            $paymentFabricWalletId = $this->callWalletApi($payment, $order);

            if ($paymentFabricWalletId) {
                $returnResponse['payfabric_wallet_id'] = $paymentFabricWalletId;
            }

        } else {
            $returnResponse['result'] = false;
            $returnResponse['status'] = $responseArray['Status'];
            $returnResponse['message'] = $responseArray['Message'];
            $returnResponse['error_code'] = $responseArray['PayFabricErrorCode'];
            $this->capgeminiLogger->notice("Error code: " . $responseArray['PayFabricErrorCode']);
            $this->capgeminiLogger->error("Declined: " . $responseArray['Message']);
        }
        $this->capgeminiLogger->notice("--------------------------------------------");

        return $returnResponse;

    }

    /**
     * @param array $card
     * @return string|false
     */
    protected function processCustomerCards($card) {
        try {
            if (!isset($card['Customer'])) {
                return false;
            }

            $apiUrl = $this->getApiUrl()
                . "payment/api/wallet/getByCustomer?customer=" .
                $card['Customer'] .
                "&tender=CreditCard";
            $this->capgeminiLogger->notice("Calling PayFabric API");
            $this->capgeminiLogger->notice("Url: " . $apiUrl);
            $this->curl->setConfig(['timeout' => $this->getConfigData('request_timeout')])
                ->write(\Laminas\Http\Request::METHOD_GET, $apiUrl, '1.1', $this->httpHeaders);
            $responseString = $this->curl->read();

            if ($this->curl->getErrno() !== 0) {
                $this->capgeminiLogger->error(
                    "Curl Error[" . $this->curl->getErrno() . "]: " . $this->curl->getError(),
                    ['method' => __METHOD__]
                );

                return false;
            }

            $customerCards = $this->jsonHelper->jsonDecode($responseString);
            foreach ($customerCards as $customerCard) {
                $account = str_pad(
                    substr($card['Account'], -4),
                    strlen($card['Account']), 'X',
                    STR_PAD_LEFT);
                if ($customerCard['Account'] == $account && $customerCard['ExpDate'] == $card['ExpDate']) {
                    return $customerCard['ID'];
                }
            }
        } catch (Exception $e) {
            $this->capgeminiLogger->error($e->getMessage());
        }

        return false;
    }

    /**
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param float $amount
     * @return $this|\Magento\Payment\Model\Method\Cc
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function capture(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        if ($this->getConfigData('debug')) {
            $this->capgeminiLogger->debug("start capture()");
        }
        $this->setAmount($amount)->setPayment($payment);

        $this->_prepareRequestHeaders();
        $transactionType = self::PAYMENT_ACTION_AUTH_CAPTURE;
        $result = $this->processRequest($payment, $amount, $transactionType);

        $errorMsg = '';
        if ($result['result'] === false) {
            $errorMsg = $this->getErrorMessageByCode($result['error_code']);
        }  else {
            $payment->setStatus(self::STATUS_APPROVED)
                ->setLastTransId($result['trxkey'])
                ->setCcTransId($result['trxkey'])
                ->setTransactionId($result['trxkey'])
                ->setIsTransactionClosed(1);
            if (isset($result['payfabric_wallet_id'])) {
                $payment->setData('payfabric_wallet_id', $result['payfabric_wallet_id']);
            }
        }
        if (!empty($errorMsg)) {
            $errorMsg = __('Error %1', $errorMsg);
            throw new \Magento\Framework\Exception\LocalizedException($errorMsg);
        }

        return $this;
    }

    /**
     * @param null|string|int $errorCode
     * @return \Magento\Framework\Phrase
     */
    public function getErrorMessageByCode($errorCode)
    {
        try {
            if ($this->getConfigData('enable_detailed_errors')) {
                $errors = $this->getConfigData('error_code');
                if (!empty($errors) && !empty($errorCode)) {
                    $errorsCodeMessages = $this->jsonHelper->jsonDecode($errors);
                    foreach ($errorsCodeMessages as $key => $value) {
                        foreach (explode(',', trim($value['code'])) as $code) {
                            if ($errorCode == $code) {
                                return $value['error'];
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->capgeminiLogger->error("Something went wrong while retrieve detailed error message. " . $e->getMessage());
        }
        return $this->getConfigData('default_payment_error');
    }

    /**
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param float $amount
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function authorize(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        if ($this->getConfigData('debug')) {
            $this->capgeminiLogger->debug("start authorize()");
        }
        $this->setAmount($amount)->setPayment($payment);

        $this->_prepareRequestHeaders();
        $transactionType = self::PAYMENT_ACTION_AUTH;
        $result = $this->processRequest($payment, $amount, $transactionType);

        $errorMsg = '';
        if ($result['result'] === false) {
            $errorMsg = $this->getErrorMessageByCode($result['error_code']);
        }  else {
            $payment->setStatus(self::STATUS_APPROVED)
                ->setLastTransId($result['trxkey'])
                ->setCcTransId($result['trxkey'])
                ->setTransactionId($result['trxkey'])
                ->setIsTransactionClosed(1);

            if (isset($result['payfabric_wallet_id'])) {
                $payment->setPayfabricWalletId($result['payfabric_wallet_id']);
            }
        }
        if (!empty($errorMsg)) {
            $errorMsg = __('Error %1', $errorMsg);
            throw new \Magento\Framework\Exception\LocalizedException($errorMsg);
        }

        return $this;
    }

    /**
     *
     */
    protected function _prepareRequestHeaders()
    {
        $deviceId = $this->getConfigData('device_id');
        $password = $this->encryptor->decrypt(trim($this->getConfigData('device_password')));
        $this->httpHeaders = array(
            "Content-Type: application/json",
            "authorization: " . $deviceId . "|" . $password);
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
     * @param Varien_Object $payment
     * @param $order
     * @param $amount
     * @return bool|void
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    private function callWalletApi($payment, $order)
    {
        $this->capgeminiLogger->notice("Calling PayFabric Wallet API. Order id " . $order->getIncrementId());

        if ($order->getCustomerIsGuest()) {
            //$customerId = $order->getIncrementId();
            try {
                $websiteId = $this->_storeManager->getStore($order->getStoreId())->getWebsiteId();
            } catch (Exception $exception) {
                $websiteId = null;
            }
            $customerId = $this->walletHelper->getCustomerIdFromIdentifiers(
                null,
                $order->getCustomerEmail(),
                $websiteId
            );
        } else {
            $customerId = $this->walletHelper->getCustomerId();
        }
        //Get existing Credit Cards from Wallet By Salespad Customer Number
        $customerCards = $this->walletApi->retrieveCardsByCustomerId($customerId);

        $cardNum        = $this->payfabricHelper->ccMasking($payment->getCcNumber(), 'X');
        $cardName       = $this->payfabricHelper->getFullCardName($payment);
        $cardExpDate    = $this->payfabricHelper->getCardExpDate($payment);

        //Checking if Credit Card exist at PayFabric or not. If exist then do not create new Wallet entry
        $isWalletExist = $this->payfabricHelper->isCustomerWalletExist($customerCards, $cardNum, $cardName, $cardExpDate);

        if ($isWalletExist['status'] === true) {
            $this->capgeminiLogger->notice("Wallet already exist hence not creating again ");

            if ($this->payfabricHelper->getSaveCard()) {
                $this->capgeminiLogger->notice("Customer selected to save card - card already exists in PayFabric");
                $data = array('customer_id'     => $customerId,
                    'cc_last4'      => $cardNum,
                    'card_name'     => $cardName,
                    'card_exp_date' => $cardExpDate,
                    'created_at'    => date('Y-m-d H:i:s'),
                    'payfabric_wallet_id' => $isWalletExist['data']['ID']
                );

                $this->wallet->setData($data);
                $this->walletRepository->save($customerId, $this->wallet);
                $this->capgeminiLogger->notice("Customer card already existing in PayFabric saved in magento");
            }
            $order->setPayfabricWalletId($isWalletExist['data']['ID']);

            return $isWalletExist['data']['ID'];
        } else {
            $this->capgeminiLogger->notice("Wallet not exist. Creating new Wallet entry... Order id " . $order->getIncrementId());

            //Create Wallet Entry at PayFabric
            $walletApiResponse = $this->walletApi->createWallet($payment);

            if (isset($walletApiResponse['Result']) && !empty($walletApiResponse['Result'])) {
                $this->capgeminiLogger->notice("Success: ". $walletApiResponse['Result']);

                //If customer selected to save card, then saving in db table
                if ($this->payfabricHelper->getSaveCard()) {
                    $this->capgeminiLogger->notice("Customer selected to save card");
                    $data = array('customer_id'     => $customerId,
                        'cc_last4'      => $cardNum,
                        'card_name'     => $cardName,
                        'card_exp_date' => $cardExpDate,
                        'created_at'    => date('Y-m-d H:i:s'),
                        'payfabric_wallet_id' => $walletApiResponse['Result']
                    );

                    $this->wallet->setData($data);
                    $this->walletRepository->save($customerId, $this->wallet);
                    $this->capgeminiLogger->notice("Customer card saved in magento");
                }
                $order->setPayfabricWalletId($walletApiResponse['Result']);
                return $walletApiResponse['Result'];
            } else {
                $this->capgeminiLogger->error("Wallet api error: " . print_r($walletApiResponse, true));
            }
        }
    }

    /**
     * @param $walletId
     * @param $payment
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function preparePaymentInfoFromWallet($walletId, $payment)
    {
        $wallet  = $this->walletHelper->loadWalletById($walletId);
        if ($wallet->getWalletId()) {
            $ccLast4 = substr($wallet->getCcLast4(),strlen($wallet->getCcLast4())-4, 4);
            $expDate = $wallet->getCardExpDate();
            $payment->setCcNumber($wallet->getCcLast4());
            $payment->setCcCardId($wallet->getPayfabricWalletId());
            $payment->setCcOwner($wallet->getCcOwnername());
            $payment->setCcExpMonth(substr($expDate,0,2));
            $payment->setCcExpYear('20'. substr($expDate,2,2));
            $payment->setCcType($this->payfabricHelper->getShortCardName($wallet->getCardName()));
            $payment->setCcLast4($ccLast4);
        }

        return $payment;
    }
}
