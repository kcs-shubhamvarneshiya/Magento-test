<?php
/**
 * Capgemini_Payfabric
 *
 * @category   Capgemini
 * @author    Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2020 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\Payfabric\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Capgemini\Payfabric\Logger\Logger
     */
    protected $lyonscgLogger;

    /**
     * @var \Lyonscg\SalesPad\Helper\Sales
     */
    protected $salesHelper;

    /**
     * @var \Magento\Webapi\Controller\Rest\InputParamsResolver
     */
    protected $inputParamsResolver;

    /**
     * @var bool
     */
    protected $saveCard = false;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Capgemini\Payfabric\Logger\Logger $lyonscgLogger
     * @param \Lyonscg\SalesPad\Helper\Sales $salesHelper
     * @param \Magento\Webapi\Controller\Rest\InputParamsResolver $inputParamsResolver
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Capgemini\Payfabric\Logger\Logger $lyonscgLogger,
        \Lyonscg\SalesPad\Helper\Sales $salesHelper,
        \Magento\Webapi\Controller\Rest\InputParamsResolver $inputParamsResolver,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->lyonscgLogger = $lyonscgLogger;
        $this->salesHelper = $salesHelper;
        $this->inputParamsResolver = $inputParamsResolver;
        $this->storeManager = $storeManager;
    }

    /**
     * @param $payment
     * @param $amount
     * @param $setupId
     * @param $transactionType
     * @return array
     * @throws \Magento\Framework\Webapi\Exception
     * @throws \Exception
     */
    public function getRequestFields($payment, $amount, $setupId, $transactionType)
    {
        $inputParams = IgnoreValidatedRecaptcha::wrap($this->inputParamsResolver, 'resolve');
        foreach ($inputParams as $inputParam) {
            if ($inputParam instanceof \Magento\Quote\Model\Quote\Payment) {
                $paymentData = $inputParam->getData('additional_data');
                $payment->setCcOwner(trim($paymentData['name_on_card']));
            }
        }
        if (isset($paymentData['save_credit_card'])) {
            $this->saveCard = (boolean)$paymentData['save_credit_card'];
        }
        $order = $payment->getOrder();
        $billingaddress = $order->getBillingAddress();
        $billingaddressStreets=explode(PHP_EOL,$billingaddress->getData('street'));
        $billingaddressStreet1 = $billingaddressStreets[0];
        $billingaddressStreet2 = "";
        if (isset($billingaddressStreets[1])) {
            $billingaddressStreet2 = $billingaddressStreets[1];
        }

        $customerNumOrName = false;
        $email = $order->getCustomerEmail();
        if ($email) {
            $customerEntityId = $order->getCustomerId();
            try {
                $websiteId = $this->storeManager->getStore($order->getStoreId())->getWebsiteId();
            } catch (\Exception $exception) {
                $websiteId = null;
            }
            $customerNumOrName = $this->salesHelper->getSalespadCustomerNumberByIdentifiers($customerEntityId, $email, $websiteId);
        }

        if (!$customerNumOrName) {
            // fall back to customer name for now
            $customerNumOrName = $billingaddress->getData('firstname')." ".$billingaddress->getData('lastname');
        }


        $shippingaddress = $order->getShippingAddress();
        $shippingaddressStreets=explode(PHP_EOL,$shippingaddress->getData('street'));
        $shippingaddressStreet1 = $shippingaddressStreets[0];
        $shippingaddressStreet2 = "";
        if (isset($shippingaddressStreets[1])) {
            $shippingaddressStreet2 = $shippingaddressStreets[1];
        }
        $totals = number_format($amount, 2, '.', '');
        $currencyDesc = $order->getBaseCurrencyCode();
        $cardOwnerName = explode(" ",$payment->getCcOwner());
        $cardholderFirstname = $cardOwnerName[0];
        $cardholderLastname = "";
        if (isset($cardOwnerName[1])) {
            $cardholderLastname = $cardOwnerName[1];
        }
        $year = substr($payment->getCcExpYear(), -2);
        $month = $payment->getCcExpMonth();
        if(strlen($payment->getCcExpMonth())==1) {
            $month = "0".$month;
        }
        if (!empty($payment->getCcCardId())) {
            $card = [
                'ID' => $payment->getCcCardId(),
                'IsSaveCard' => true
            ];
        } else {
            $card = [
                'Account'=> $payment->getCcNumber(),
                'Billto' =>
                    [
                        'City'=> $billingaddress->getData('city'),
                        'Country'=> $billingaddress->getData('country_id'),
                        'Email'=> $billingaddress->getData('email'),
                        'Line1'=> $billingaddressStreet1,
                        'Line2'=> $billingaddressStreet2,
                        'Line3'=> "",
                        'Phone'=> (int) filter_var($billingaddress->getData('telephone'), FILTER_SANITIZE_NUMBER_INT),
                        'State'=> $billingaddress->getData('region'),
                        'Zip'=> $billingaddress->getData('postcode')
                    ],
                'CardHolder' =>
                    [
                        'FirstName'=> $cardholderFirstname,
                        'LastName'=> $cardholderLastname,
                    ],
                "Customer" => $customerNumOrName,
                "ExpDate" => $month.$year,
                "IsDefaultCard" => false,
                'IsSaveCard' => true
            ];
        }
        $headerValues = [];
        $newer = [
            'Name'=> 'InvoiceNumber',
            'Value' => $order->getIncrementId()
        ];
        array_push($headerValues, $newer);
        $fields = [
            'Amount'=> $totals,
            'Card' => $card,
            "Currency" => $currencyDesc,
            "Customer" => $customerNumOrName,
            "ReferenceKey" => null,
            "SetupId" => $setupId,
            "Shipto" =>
                [
                    "City" => $shippingaddress->getData('city'),
                    "Country" => $shippingaddress->getData('country_id'),
                    // CLMI-952 - leaving this as is, since it is shipping name which may be different than the cusomter name
                    // may need to change this to use $customerNameOrNum if Circa tells us to
                    "Customer" => $shippingaddress->getData('firstname')." ".$shippingaddress->getData('lastname'),
                    "Email" => $shippingaddress->getData('email'),
                    "Line1" => $shippingaddressStreet1,
                    "Line2" => $shippingaddressStreet2,
                    "Phone" => (int) filter_var($shippingaddress->getData('telephone'), FILTER_SANITIZE_NUMBER_INT),
                    "State" => $shippingaddress->getData('region'),
                    "Zip" => $shippingaddress->getData('postcode')
                ],
            "Type" => $transactionType,
            "Document" => [
                "Head" => $headerValues
            ]
        ];
        return $fields;
    }

    /**
     * @return bool
     */
    public function getSaveCard()
    {
        return $this->saveCard;
    }

    /**
     * Masking Credit Card Number
     *
     * @param int $number
     * @param string $maskingCharacter
     * @return string
     *
     */
    public function ccMasking($number, $maskingCharacter = 'X')
    {
        return str_repeat($maskingCharacter, strlen($number) - 4) . substr($number, -4);
    }


    /**
     * Get Credit Card Expiry Date MMYY format
     *
     * @param Varien_Object $payment
     * @return string
     *
     */
    public function getCardExpDate($payment)
    {
        if ($payment instanceof \Magento\Payment\Model\InfoInterface) {
            $year = substr($payment->getCcExpYear(), -2);
            $month = $payment->getCcExpMonth();
            if (strlen($payment->getCcExpMonth()) == 1) {
                $month = "0" . $month;
            }
        } else {
            $year = substr($payment['cc_expiration_year'], -2);
            $month = $payment['cc_expiration_month'];
            if (strlen($payment['cc_expiration_month']) == 1) {
                $month = "0" . $month;
            }
        }
        $cardExpDate = $month . $year;
        return $cardExpDate;
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
     * Get Full Credit Card Name
     *
     * @param Varien_Object $payment
     * @return string
     */
    public function getFullCardName($payment)
    {
        if (is_string($payment)) {
            $ccType = $payment;
        } else {
            $ccType = $payment->getCcType();
        }
        if (isset($ccType) && $ccType == 'AE') {
            $cardName = 'AmericanExpress';
        } else if (isset($ccType) && $ccType == 'VI') {
            $cardName = 'Visa';
        } else if (isset($ccType) && $ccType == 'MC') {
            $cardName = 'MasterCard';
        } else if (isset($ccType) && $ccType == 'DI') {
            $cardName = 'Discover';
        } else {
            $cardName = 'Other';
        }
        return $cardName;
    }


    /**
     * Get Short Credit Card Name
     *
     * @param Varien_Object $payment
     * @return string
     */
    public function getShortCardName($ccType)
    {
        if (isset($ccType) && $ccType == 'AmericanExpress') {
            $cardName = 'AE';
        } else if (isset($ccType) && $ccType == 'Visa') {
            $cardName = 'VI';
        } else if (isset($ccType) && $ccType == 'MasterCard') {
            $cardName = 'MC';
        } else if (isset($ccType) && $ccType == 'Discover') {
            $cardName = 'DI';
        } else {
            $cardName = 'Other';
        }
        return $cardName;
    }
}
