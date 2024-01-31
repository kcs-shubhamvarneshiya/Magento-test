<?php

namespace Lyonscg\SalesPad\Model\Api;

use Astound\Affirm\Model\Ui\ConfigProvider;
use Capgemini\AffirmVcn\Model\AffirmVcn;
use Capgemini\MyWallet\Model\WalletApi;
use Capgemini\PaymentTerms\Model\Termsnet30;
use Lyonscg\SalesPad\Helper\Order as OrderHelper;
use Lyonscg\SalesPad\Helper\Sales as SalesHelper;
use Lyonscg\SalesPad\Model\Config as ConfigModel;
use Lyonscg\SalesPad\Model\Api;
use Lyonscg\SalesPad\Model\Api\Customer as CustomerApi;
use Lyonscg\SalesPad\Model\Api\CustomerAddr as CustomerAddrApi;
use Lyonscg\SalesPad\Model\Api\Quote as QuoteApi;
use Lyonscg\SalesPad\Model\Api\SalesPad\SalesDocument;
use Lyonscg\SalesPad\Model\Api\SalesPad\SalesLineItem;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\OrderRepositoryInterfaceFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;

class Order
{
    const ACTION_DOC          = 'api/SalesDocument';
    const ACTION_DOC_LINES    = self::ACTION_DOC . '/WithLines';
    const ACTION_SEARCH       = 'api/SalesDocumentSearch';
    const ACTION_GET_LINES    = 'api/SalesLineItem';
    const ACTION_ITEM_SEARCH  = 'api/SalesLineItemSearch';
    const ACTION_HISTORY      = 'api/SalesDocumentHistory';
    const ACTION_ITEM_HISTORY = 'api/SalesLineItemHistory';

    const ACTION_GET          = self::ACTION_DOC . '/%s/%s';

    // constants used in GET parameters or ODATA queries
    const DOC_ORDER      = 'ORDER';
    const DOC_QUOTE      = 'QUOTE';
    const ASC            = 'ASC';
    const DESC           = 'DESC';
    const SALES_DOC_NUM  = 'Sales_Doc_Num';
    const SALES_DOC_TYPE = 'Sales_Doc_Type';
    const DOC_DATE       = 'Doc_Date';
    const DEX_ROW_TS     = 'DEX_ROW_TS';

    // keys for returned data
    const CUSTOMER_NUM   = 'Customer_Num';
    const BILLING_ADDR   = 'Bill_To_Address_Code';
    const SHIPPING_ADDR  = 'Ship_To_Address_Code';

    const ADDR_TYPE_BILLING = 'billing';
    const ADDR_TYPE_SHIPPING = 'shipping';

    // order attribute code
    const SALESPAD_SALES_DOC_NUM = 'salespad_sales_doc_num';

    const ORDER_LIMIT = 20;

    /**
     * @var Api
     */
    protected $api;

    /**
     * @var SalesDocument
     */
    protected $salesDocument;

    /**
     * @var SalesLineItem
     */
    protected $salesLineItem;

    /**
     * @var Customer
     */
    protected $customerApi;

    /**
     * @var CustomerAddr
     */
    protected $customerAddrApi;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var OrderHelper
     */
    protected $orderHelper;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var OrderExtensionFactory
     */
    protected $orderExtensionFactory;
    /**
     * @var ConfigModel
     */
    protected $configModel;

    /**
     * @var Quote
     */
    protected $quoteApi;

    /**
     * @var SalesHelper
     */
    protected $salesHelper;

    /**
     * @var WalletApi
     */
    protected $walletApi;

    /**
     * Order constructor.
     * @param Api $api
     * @param SalesDocument $salesDocument
     * @param SalesLineItem $salesLineItem
     * @param Customer $customerApi
     * @param CustomerAddr $customerAddrApi
     * @param Logger $logger
     * @param OrderRepositoryInterfaceFactory $orderRepositoryFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param OrderHelper $orderHelper
     * @param StoreManagerInterface $storeManager
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param ConfigModel $configModel
     * @param Quote $quoteApi
     * @param SalesHelper $salesHelper
     * @param WalletApi $walletApi
     */
    public function __construct(
        Api $api,
        SalesDocument $salesDocument,
        SalesLineItem $salesLineItem,
        CustomerApi $customerApi,
        CustomerAddrApi $customerAddrApi,
        Logger $logger,
        OrderRepositoryInterfaceFactory $orderRepositoryFactory,
        CustomerRepositoryInterface $customerRepository,
        OrderHelper $orderHelper,
        StoreManagerinterface $storeManager,
        OrderExtensionFactory $orderExtensionFactory,
        ConfigModel $configModel,
        QuoteApi $quoteApi,
        SalesHelper $salesHelper,
        WalletApi $walletApi
    ) {
        $this->api = $api;
        $this->salesDocument = $salesDocument;
        $this->salesLineItem = $salesLineItem;
        $this->customerApi = $customerApi;
        $this->customerAddrApi = $customerAddrApi;
        $this->logger = $logger;
        $this->orderRepository = $orderRepositoryFactory->create();
        $this->customerRepository = $customerRepository;
        $this->orderHelper = $orderHelper;
        $this->storeManager = $storeManager;
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->configModel = $configModel;
        $this->quoteApi = $quoteApi;
        $this->salesHelper = $salesHelper;
        $this->walletApi = $walletApi;
    }

    public function get($orderNumber, $customerNum = false)
    {
        return $this->salesDocument->getBySalesDocId(SalesDocument::TYPE_ORDER, $orderNumber, $customerNum);
    }

    public function getAll($orderNumber, $customerNum = false)
    {
        if (!$orderData = $this->get($orderNumber, $customerNum)) {
            throw new LocalizedException(__('The Order does not exist or it doesn\'t belong to the current customer'));
        }

        return $this->getAllOrderData($orderData);
    }

    public function getItems($orderNumber)
    {
        return $this->salesLineItem->search(SalesDocument::TYPE_ORDER, $orderNumber);
    }

    public function getOrdersForCustomer($customerNum, $page = 1, $limit = 0)
    {
        //return $this->salesDocument->getByCustomerNum(SalesDocument::TYPE_ORDER, $customerNum, self::ORDER_LIMIT, $page);
        return $this->salesDocument->getByCustomerNum(SalesDocument::TYPE_ORDER, $customerNum, $limit, $page);
    }

    public function getInvoicesForOrder($salesDocNum, $customerNum = false)
    {
        return $this->salesDocument->getByPrevSalesDocId(SalesDocument::TYPE_INVOICE, $salesDocNum, $customerNum);
    }

    public function getShippingItemsForOrder($invoiceNum)
    {
        return $this->salesLineItem->search(SalesLineItem::TYPE_INVOICE, $invoiceNum);
    }

    /**
     * @param $orderNumber
     * @param null $zipCode
     * @return array|bool
     */
    public function search($orderNumber, $zipCode = null)
    {
        $orderData = $this->salesDocument->getBySalesDocId(SalesDocument::TYPE_ORDER, $orderNumber);
        if (!$orderData) {
            return false;
        }
        return $this->getAllOrderData($orderData, $zipCode);
    }

    /**
     * @param OrderInterface $order
     * @return bool
     */
    public function create(OrderInterface $order)
    {
        $orderId = $order->getId();
        $data = $this->_convertOrder($order);

        $this->salesDocument->clearFailures();
        $data = $this->salesDocument->createWithLines($data);
        if (!$data) {
            return false;
        }

        $orderNum = $data[self::SALES_DOC_NUM] ?? false;
        if ($orderNum) {
            $extensionAttributes = $order->getExtensionAttributes();
            $extensionAttributes->setSalespadSalesDocNum($orderNum);
            $order->setSalespadSalesDocNum($orderNum);
            $this->orderRepository->save($order);
            $this->logger->debug(
                "Order $orderId created successfully ($orderNum)"
            );
            return $orderNum;
        } else {
            // this probably should not happen?
            $this->logger->debug(
                "Order $orderId created successfully but got no salespad order number"
            );
            return true;
        }
    }

    /**
     * @param OrderInterface $order
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function createOrUpdate(OrderInterface $order)
    {
        $order->setData('_salespad_no_sync', true);
        if ($order->getExtensionAttributes()->getSalespadSalesDocNum()) {
            return true;
        } else {
            $shouldTransfer = false;
            $quoteNum = $order->getExtensionAttributes()->getSalespadQuoteNum();
            if ($quoteNum) {
                // check if the quote is in salespad before trying to transfer
                $quote = $this->salesDocument->get($this->salesDocument::TYPE_QUOTE, $quoteNum);
                if ($quote !== false) {
                    $shouldTransfer = true;
                }
            }

            // - Adding additional guard on SalesPad order sync
            // - Check magento order status before make any action into SalesPad
            if ($order->getStatus() !== "vc_picked_up") {
                if ($shouldTransfer) {
                    return $this->transfer($order);
                } else {
                    return $this->create($order);
                }
            } else {
                return false;
            }
        }
    }

    public function updatePayfabric(OrderInterface $order, $salesDocNum = null)
    {
        if ($order->getPayFabricApiStatus()) {
            // payfabric already updated
            return true;
        }
        // if payment is not Payfabric, then just return true
        $payment = $order->getPayment();
        if ($payment->getMethod() !== \Capgemini\Payfabric\Model\Payfabric::METHOD_CODE &&
            $payment->getMethod() !== \Capgemini\AffirmVcn\Model\AffirmVcn::METHOD_CODE) {
            return true;
        }

        if ($salesDocNum === null) {
            $salesDocNum = $order->getExtensionAttributes()->getSalespadSalesDocNum();
        }

        $orderDoc = $this->get($salesDocNum);
        $masterNum = $orderDoc['Master_Num'];

        $cardNum = 'XXXX-XXXX-XXXX-' . $payment->getCcLast4();
        $grandTotal = $order->getGrandTotal();
        $transactionId = $payment->getCcTransId();
        $cardType = $payment->getCcType();
        switch ($cardType) {
            case 'VI':
                $cardType = 'Visa';
                break;
            case 'DI':
                $cardType = 'Discover';
                break;
            case 'MC':
                $cardType = 'MasterCard';
                break;
            case 'AE':
                $cardType = 'AMEX';
                break;
        }

        $data = [
            'Sales_Doc_Type'        => 'ORDER',
            'Sales_Doc_Num'         => $salesDocNum,
            'Master_Number'         => $masterNum,
            'Seq_Number'            => 1,
            'Payment_Type'          => 3,
            'Credit_Card_Name'      => $cardType,
            'Credit_Card_Num'       => $cardNum,
            'Transaction_Time'      => '',
            'Transaction_Amount'    => floatval($grandTotal),
            'Transaction_ID'        => $transactionId,
            'Transaction_Type'      => 'Book',
            'Source_Software'       => 'SalesPad',
            'Connector'             => 'AuthorizeNet',
            'UserFieldData'         => [],
            'UserFieldNames'        => [],
            'Notifications'         => null
        ];

        $result = $this->salesDocument->callPayfabric($data);
        if ($result !== false) {
            if (!empty($result['ErrorCode']) &&  $result['ErrorCode'] == 5001) {
                $this->logger->debug(sprintf(
                    "Send order to PayFabric status 409. orderId: %s, SalesDocNum: %s, requestData: %s, response: %s",
                    $order->getId(), $salesDocNum, json_encode($data), json_encode($result)
                ));
            }
            $order->setPayFabricApiStatus(1);
            $order->setPayFabricApiRequest(json_encode($data));
            $this->orderRepository->save($order);
        }

        return $result;
    }

    protected function _convertOrder(OrderInterface $order)
    {
        /** @var CustomerInterface $customer */
        $customer = null;
        $customerNum = false;

        try {
            $customer = $this->getCustomerFromOrder($order);
        } catch (NoSuchEntityException $e) {
            $this->logger->debug('No customer for order with customer id: ' . $e);
            return false;
        }
        if ($customer === null) {
            $customerNum = $this->_getSalesPadCustomerNumber(null, $order, true);
        } else {
            $customerNum = $this->_getSalesPadCustomerNumber($customer, $order, false);
        }

        $billingCode = $this->_getSalesPadAddressCode($customer, $order, self::ADDR_TYPE_BILLING);
        $shippingCode = $this->_getSalesPadAddressCode($customer, $order, self::ADDR_TYPE_SHIPPING);

        $storeId = $order->getStoreId();
        $store = $this->storeManager->getStore($storeId);
        $storeCode = $store->getCode();
        // figure out the $from_quote stuff, and the salespad number for the quote if applicable
        $customerEmail = $order->getCustomerEmail();

        if ($order->getDiscountAmount() != 0) {
            $orderDiscount = abs($order->getDiscountAmount());
        } else {
            $orderDiscount = 0.0;
        }

        $shippingMethod = $this->_convertShippingMethodToSalesPad($order->getShippingMethod());
        $shippingAmount = $order->getShippingAmount() ? $order->getShippingAmount() : '0';
        $subTotal = $order->getSubtotal();
        $tax = $order->getTaxAmount();
        $grandTotal = $order->getGrandTotal();

        $amastyAttributes = $this->orderHelper->getAmastyOrderAttributeData($order);
        $comments = $amastyAttributes['comments'] ?? '';
        $poNumber = $amastyAttributes['po_number'] ?? '';
        $projectName = $amastyAttributes['project_name'] ?? 'WEBORDER';

        $incrementId = $order->getIncrementId();

        /**
         * @var $shipAddr \Magento\Sales\Model\Order\Address
         */
        $shipAddr = $order->getShippingAddress();
        $shipName = $shipAddr->getName();
        $shipPhone = $shipAddr->getTelephone();
        $shipAddress = $shipAddr->getStreet();

        $shipAddressLine1 = $shipAddress[0] ?? '';
        $shipAddressLine2 = $shipAddress[1] ?? '';

        $shipCity = $shipAddr->getCity();
        $shipState = $shipAddr->getRegionCode();
        $shipZip = $shipAddr->getPostcode();

        $shipCountry = $shipAddr->getCountryId();

        if ($storeCode == 'clarkson') {
            $xMarketingCode = '98WEB';
        } else {
            $xMarketingCode = '99WEB';
        }

        $xPaymentType = $this->getOrderPaymentMethod($order);
        $salesPersonId = $this->configModel->getSalesPersonId();
        $warehouseCode = $this->configModel->getWarehouseCode();

        // TODO - this will need to be populated when we add quote functionality in a future ticket
        $mageQuoteNum = '';

        $lineItems = [];
        $lineNumber = 0;
        foreach ($order->getAllVisibleItems() as $orderItem) {
            $lineItems[$lineNumber++] = $this->_convertOrderItem($orderItem, [
                'Ship_To_Address_Code' => $shippingCode,
                'Warehouse_Code'       => $warehouseCode,
                'Ship_To_Name'         => $shipName,
                'Address_Line_1'       => $shipAddressLine1,
                'Address_Line_2'       => $shipAddressLine2,
                'City'                 => $shipCity,
                'State'                => $shipState,
                'Zip'                  => $shipZip,
                'Country'              => $shipCountry,
                'Country_Code'         => $shipCountry,
                'Phone_1'              => $shipPhone,
                'Sales_Person_Id'      => $salesPersonId,
                'Shipping_Method'      => $shippingMethod
            ]);
        }

        // TODO - determine if we need to take carton_x_dimensional_weight into account
        // there is code in the M1 version that checks if carton 1 or carton 2 are over 150
        // and if so, the $shippingMethod should be set to 'FREIGHT'

        // Taken from M1 version
        if (strtolower($shipCountry) != 'us') {
            $shippingMethod = 'BESTWAY';
        }

        // CLMI-963 - send our own Sales_Doc_Num for Magento Orders
        $salesDocNum = strval($order->getIncrementId());
        $payment = $order->getPayment();
        $xCardLast4Text = $payment->getCcLast4();
        $xCardType = $payment->getCcType();
        $xExp_Month = $payment->getCcExpMonth();
        $xExpYear = $payment->getCcExpYear();
        switch ($xCardType) {
            case 'VI':
                $xCardType = 'Visa';
                break;
            case 'DI':
                $xCardType = 'Discover';
                break;
            case 'MC':
                $xCardType = 'MasterCard';
                break;
            case 'AE':
                $xCardType = 'AMEX';
                break;
        }

        $orderData =  [
            'Sales_Doc_Type'       => 'ORDER',
            'Sales_Doc_Id'         => 'STANDARDORDER',
            'Sales_Doc_Num'        => $salesDocNum,
            'Master_Num'           => -1,
            'Customer_Num'         => $customerNum,
            'Email'                => $customerEmail,
            'Currency_ID'          => $this->configModel->getCurrencyId(),
            'Bill_To_Address_Code' => $billingCode,
            'Ship_To_Address_Code' => $shippingCode,
            'Req_Ship_Date'        => date('c'),
            'Currency_Dec'         => $this->configModel->getCurrencyDec(),
            'Source'               => 'Magento',
            'Subtotal'             => floatval($subTotal),
            'Freight'              => floatval($shippingAmount),
            'Tax'                  => floatval($tax),
            'Misc_Charge'          => 0,
            'Discount'             => floatval($orderDiscount),
            'Total'                => floatval($grandTotal),
            'Shipping_Method'      => $shippingMethod,
            'Sales_Person_ID'      => $salesPersonId,
            'Tax_Schedule'         => 'AVATAX',
            'Sales_Batch'          => 'REVIEW',
            'Warehouse_Code'       => $warehouseCode,
            'Quote_Num'            => $mageQuoteNum,
            'Ship_To_Name'         => $shipName,
            'Address_Line_1'       => $shipAddressLine1,
            'Address_Line_2'       => $shipAddressLine2,
            'City'                 => $shipCity,
            'State'                => $shipState,
            'Zip'                  => $shipZip,
            'Country'              => $shipCountry,
            'Country_Code'         => $shipCountry,
            'Phone_1'              => $shipPhone,
            'Customer_PO_Num'      => $poNumber,
            'UserFieldData'        => [
                $projectName,
                $xMarketingCode,
                $incrementId,
                $comments,
                $xCardLast4Text,
                $xCardType,
                $xExp_Month,
                $xExpYear
            ],
            'UserFieldNames'       => [
                'xJob_Name',
                'xMarketingCode',
                'xWeb_Order_Num',
                'xWebOrderNotes',
                'xCardLast4Text',
                'xCardType',
                'xExp_Month',
                'xExpYear'
            ],
            'LineItems'            => $lineItems,
        ];
        if (!empty($xPaymentType)) {
            $orderData['UserFieldData'][] = $xPaymentType;
            $orderData['UserFieldNames'][] = 'xPaymentType';
        }

        foreach ($lineItems as $lineItem){
            if (in_array('xVendorNote', $lineItem['UserFieldNames'])){
                $orderData['UserFieldData'][] = "Custom/Vendor Pricing";
                $orderData['UserFieldNames'][] = 'xPriceMatchCode';
                break;
            }
        }

        return $orderData;
    }

    /**
     * @param OrderInterface $order
     */
    protected function getOrderPaymentMethod(OrderInterface $order)
    {
        $paymentMethod = $order->getPayment()->getMethod();
        if ($paymentMethod == ConfigProvider::CODE || $paymentMethod == AffirmVcn::METHOD_CODE){
            return "Affirm";
        } elseif ($paymentMethod == Termsnet30::PAYMENT_METHOD_TERMSNET30_CODE) {
            return "Net 30";
        } else {
            return "Web Order";
        }
    }

    /**
     * @param string $shippingMethod
     * @return string
     */
    protected function _convertShippingMethodToSalesPad($shippingMethod)
    {
        foreach ($this->configModel->getShippingMap() as $key => $value) {
            if (strtolower($value['magento']) == strtolower($shippingMethod)) {
                return $value['salespad'];
            }
        }
        return 'BESTWAY';
    }

    /**
     * @param OrderItemInterface $orderItem
     * @param array $extraData
     * @return array
     */
    protected function _convertOrderItem(OrderItemInterface $orderItem, array $extraData)
    {
        $extensionAttributes = $orderItem->getExtensionAttributes();
        if ($extensionAttributes !== null) {
            $lineItemComments = $extensionAttributes->getCommentsLineItem();
            $sidemark = $extensionAttributes->getSidemark();
            $customHeightValue = $extensionAttributes->getCustomHeightValue();
            $customHeightCost = $extensionAttributes->getCustomHeightCost();
        } else {
            $lineItemComments = '';
            $sidemark = '';
            $customHeightValue = false;
        }

        $originalPrice = floatval($orderItem->getOriginalPrice());
        $maxPrice = $originalPrice + $orderItem->getBuyRequest()->getData('custom_height_price');
        $finalPrice = floatval($orderItem->getPrice());
        $orderItemData = array_merge([
            'Sales_Doc_Type'    => 'ORDER',
            'Item_Number'       => $orderItem->getSku(),
            'Unit_Of_Measure'   => 'EACH',
            // TODO - M1 version uses getQtyToInvoice here, is there an M2 equivalent?
            'Quantity'          => $orderItem->getQtyOrdered(),
            'Unit_Price'        => $maxPrice,
            'Markdown_Amount'   => $maxPrice - $finalPrice,
            'Price_Level'       => 'RETAIL',
            'Item_Curr_Dec'     => '2',
            'Currency_Dec'      => $this->configModel->getCurrencyDec(),
            'Item_Tax_Schedule' => 'AVATAX',
            'Tax_Schedule'      => 'AVATAX',
            'Is_Dropship'       => true,
            'Comment'           => $lineItemComments,
            'UserFieldData'     => [$sidemark, $maxPrice],
            'UserFieldNames'    => ['xSidemark', 'xOriginalPrice'],
        ], $extraData);

        if ($customHeightValue) {
            $orderItemData['Item_Description'] = "CUSTOM Overall Height " .  $customHeightValue ."” | " . $orderItem->getName();
            $xAdditionalCost = $customHeightCost;
            $xVendorNote = "CUSTOM OAH " .  $customHeightValue . '”';
            $orderItemData['UserFieldNames'] = array_merge($orderItemData['UserFieldNames'], ['xAdditional_Cost','xVendorNote']);
            $orderItemData['UserFieldData'] = array_merge($orderItemData['UserFieldData'], [$xAdditionalCost, $xVendorNote]);
        }

        return $orderItemData;
    }

    /**
     * @param OrderInterface $order
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCustomerFromOrder(OrderInterface $order)
    {
        $customerId = $order->getCustomerId();
        if (!$customerId) {
            return null;
        }
        return $this->customerRepository->getById($customerId);
    }

    /**
     * @param CustomerInterface $customer
     * @param OrderInterface $order
     * @param bool $isGuest
     * @return string
     * @throws \Exception
     */
    protected function _getSalesPadCustomerNumber(?CustomerInterface $customer, OrderInterface $order, $isGuest = false)
    {
        $customerEntityId = $order->getCustomerId();
        $email = $order->getCustomerEmail();
        try {
            $websiteId = $this->storeManager->getStore($order->getStoreId())->getWebsiteId();
        } catch (\Exception $exception) {
            $websiteId = null;
        }
        $customerNum = $this->salesHelper->getSalespadCustomerNumberByIdentifiers($customerEntityId, $email, $websiteId);

        if (!$customerNum) {
            if ($customer !== null) {
                // CRC-638 Moved sales_pad_customer_num customer attribute from a custom to an extension one.
                $customerNum = $customer->getExtensionAttributes()->getSalesPadCustomerNum() ?? false;
            } else {
                $customerNum = false;
            }
        }

        if (!empty($customerNum)) {
            return $this->_addSalesPadCustomerNumToOrder($order, $customerNum);
        }

        // try order
        $extensionAttributes = $order->getExtensionAttributes();
        if ($extensionAttributes && $extensionAttributes->getSalesPadCustomerNum()) {
            return $extensionAttributes->getSalesPadCustomerNum();
        }

        // customer does not exist in sales pad
        if ($isGuest) {
            // guest customer, so we need to create a new one
            $name = $this->_getCustomerName($order);
            $this->logger->debug("creating guest customer for salespad for order " . $order->getId());
            $guestNumber = $this->customerApi->createGuestCustomer($name, $email);
            $this->logger->debug("created guest customer for salespad for order " . $order->getId() . ": $guestNumber");
            $customerNum = $this->_addSalesPadCustomerNumToOrder($order, $guestNumber);
        } else {
            // customer does exist, but somehow wasn't sent to salespad yet
            $this->logger->debug("creating salespad customer for " . $customer->getId());
            $customerNum = $this->customerApi->create($customer);
            $this->logger->debug("created salespad customer for " . $customer->getId() . ": $customerNum");
            $customerNum = $this->_addSalesPadCustomerNumToOrder($order, $customerNum);
        }

        if ($customerNum) {
            $this->_maybeUpdateWallet($order, $customerNum);
        }

        return $customerNum;
    }

    /**
     * @param CustomerInterface $customer
     * @param OrderInterface $order
     * @param string $addressType
     * @return string
     */
    protected function _getSalesPadAddressCode(?CustomerInterface $customer, OrderInterface $order, $addressType)
    {
        $address = $addressType === 'billing' ? $order->getBillingAddress() : $order->getShippingAddress();

        // We used to use the address code saved to the order address in case the address was already created in
        // SalesPad.  However, it is possible that the address is created and the order fails to sync.  Subsequent
        // sync attempts would try and use the saved address code without checking it, so if that address no longer
        // existed in SalesPad, it would fail to sync forever.
        // The call to createFromOrderAddress will check SalesPad to see if an address matching the data exists, and
        // if so will use that.

        $customerNum = false;
        $orderExtensionAttributes = $order->getExtensionAttributes();
        if ($orderExtensionAttributes) {
            $customerNum = $orderExtensionAttributes->getSalesPadCustomerNum();
        }

        if (!$customerNum) {
            throw new \Exception(__('Missing sales_pad_customer_num for order %1', $order->getId()));
        }

        // create the address
        $addressCode = $this->customerAddrApi->createFromOrderAddress($order, $address, $customerNum);
        if ($addressCode) {
            $this->orderHelper->saveAddressCodeToOrderAddress($address, $addressCode);
            $this->logger->debug("created new address for order with code $addressCode");
        }
        return $addressCode;
    }

    protected function _addSalesPadCustomerNumToOrder(OrderInterface $order, $customerNum)
    {
        $extensionAttributes = $order->getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        }
        $extensionAttributes->setSalesPadCustomerNum($customerNum);
        $order->setExtensionAttributes($extensionAttributes);
        $order->setSalesPadCustomerNum($customerNum);
        $this->orderRepository->save($order);
        return $customerNum;
    }

    public function getAllOrderData(array $orderData, $zipCode = null)
    {
        // add addresses
        $orderNumber = $orderData[self::SALES_DOC_NUM] ?? false;
        if (!$orderNumber) {
            throw new \Exception('No sales doc num for order data');
        }
        $customerNum = $orderData[self::CUSTOMER_NUM] ?? false;
        $this->adjustAddressData($orderNumber, $customerNum, $orderData);
        $billingAddrCode = $orderData[self::BILLING_ADDR];
        $shippingAddrCode = $orderData[self::SHIPPING_ADDR];

        $data = ['order' => $orderData];

        // if we don't pass in a $zipCode, then return the search result regardless
        $matched = ($zipCode === null);
        if ($zipCode) {
            $zipCode = strtolower(trim($zipCode));
        }

        if ($customerNum) {
            if ($billingAddrCode) {
                $billingAddr = $this->customerAddrApi->get($customerNum, $billingAddrCode);
                if ($billingAddr !== false) {
                    $data['billing'] = $billingAddr;
                    if ($zipCode !== null) {
                        $zip = $billingAddr['Zip'] ?? false;
                        if (strtolower(trim($zip)) == $zipCode) {
                            // zip from SalesPad matches zip we passed in
                            $matched = true;
                        }
                    }
                }
            } else {
                if ($zipCode !== null) {
                    // no billing address found, so it can't match the zipCode
                    $this->logger->debug('No billing address code when checking zipcode');
                }
            }

            if ($shippingAddrCode) {
                $shippingAddr = $this->customerAddrApi->get($customerNum, $shippingAddrCode);
                if ($shippingAddr !== false) {
                    $data['shipping'] = $shippingAddr;
                    if (!$matched) {
                        $zip = $shippingAddr['Zip'] ?? false;
                        if (strtolower(trim($zip)) == $zipCode) {
                            // zip from SalesPad matches zip we passed in
                            $matched = true;
                        }
                    }
                }
            }

            if (!$matched) {
                return false;
            }

            $customerData = $this->customerApi->get($customerNum);
            if ($customerData) {
                $data['customer'] = $customerData;
            }

            $items = $this->getItems($orderNumber);
            if ($items) {
                $data['items'] = $items;
            }

            $invoices = $this->getInvoicesForOrder($orderNumber, $customerNum);
            if ($invoices) {
                for ($i = 0; $i < count($invoices); $i++) {
                    $invoiceNum = trim($invoices[$i]['Sales_Doc_Num']);
                    $invoiceItems = $this->getShippingItemsForOrder($invoiceNum);
                    if ($invoiceItems && isset($invoiceItems['Items'])) {
                        $invoices[$i]['items'] = $invoiceItems['Items'];
                    }
                }
                $data['invoices'] = $invoices;
            } else {
                $data['invoices'] = [];
            }
        }

        return $data;
    }

    /**
     * Only called when the customer is a guest
     * @param OrderInterface $order
     * @return string
     */
    protected function _getCustomerName(OrderInterface $order)
    {
        $firstName = trim($order->getCustomerFirstname());
        $lastName = trim($order->getCustomerLastname());

        // try to pull first name from order addresses if it is not on the order
        if (!$firstName) {
            $billing = $order->getBillingAddress();
            if ($billing) {
                $firstName = trim($billing->getFirstname());
            }
            if (!$firstName) {
                $shipping = $order->getShippingAddress();
                if ($shipping) {
                    $firstName = trim($shipping->getFirstname());
                }
            }
        }
        if (!$firstName) {
            // no name found, make it 'Guest' so SalesPad won't complain about an empty customer name
            $firstName = 'Guest';
        }

        // try to pull last name from order addresses if it is not on the order
        if (!$lastName) {
            $billing = $order->getBillingAddress();
            if ($billing) {
                $lastName = trim($billing->getLastname());
            }
            if (!$lastName) {
                $shipping = $order->getShippingAddress();
                if ($shipping) {
                    $lastName = trim($shipping->getLastname());
                }
            }
        }
        if (!$lastName) {
            // no name found, make it 'Guest' so SalesPad won't complain about an empty customer name
            $lastName = 'Guest';
        }

        return $firstName . ' ' . $lastName;
    }

    /**
     * @return array
     */
    public function getFailures()
    {
        return $this->salesDocument->getFailures();
    }

    /**
     * @param OrderInterface $order
     * @return bool
     */
    public function transfer(OrderInterface $order)
    {
        $quoteDocNum = $order->getExtensionAttributes()->getSalespadQuoteNum();
        $orderData = $this->_convertOrder($order);
        $orderNum = $this->quoteApi->transfer($order, $quoteDocNum, $orderData);
        if ($orderNum) {
            $extensionAttributes = $order->getExtensionAttributes();
            $extensionAttributes->setSalespadSalesDocNum($orderNum);
            $order->setSalespadSalesDocNum($orderNum);
            $this->orderRepository->save($order);
            $this->logger->debug(
                "Order " . $order->getId() . " transfered successfully ($orderNum)"
            );
            return $orderNum;
        } else {
            return false;
        }
    }

    protected function _maybeUpdateWallet(OrderInterface $order, $customerNum)
    {
        $payment = $order->getPayment();
        if ($payment->getMethod() !== \Capgemini\Payfabric\Model\Payfabric::METHOD_CODE) {
            return true;
        }

        $walletId = $payment->getPayfabricWalletId();
        if (!$walletId) {
            // no wallet id, can't update the wallet
            return true;
        }

        $result = $this->walletApi->updateWalletCustomerNumber($walletId, $customerNum);
        if (is_array($result) && $result['Result'] === 'True') {
            $this->logger->debug('Updated customer number in wallet ' . $walletId . ' to ' . $customerNum);
            return true;
        } else {
            $this->logger->debug('Failed to update customer number in wallet ' . $walletId . ' to ' . $customerNum);
            return false;
        }
    }

    protected function adjustAddressData($orderNumber, $customerNum, &$orderData)
    {
        $orderNumber = trim($orderNumber);

        if (substr($orderNumber, -1, 1) !== '.') {
            $orderNumber .= '.';

            if ($dotted = $this->get($orderNumber, $customerNum)) {
                $orderData[self::BILLING_ADDR] = $dotted[self::BILLING_ADDR] ?? false;
                $orderData[self::SHIPPING_ADDR] = $dotted[self::SHIPPING_ADDR] ?? false;

                return;
            }
        }


        $orderData[self::BILLING_ADDR] = $orderData[self::BILLING_ADDR] ?? false;
        $orderData[self::SHIPPING_ADDR] = $orderData[self::SHIPPING_ADDR] ?? false;
    }
}
