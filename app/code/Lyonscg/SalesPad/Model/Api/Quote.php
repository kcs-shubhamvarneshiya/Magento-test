<?php

namespace Lyonscg\SalesPad\Model\Api;

use Lyonscg\SalesPad\Api\CustomerLinkRepositoryInterface;
use Lyonscg\SalesPad\Helper\Customer as CustomerHelper;
use Lyonscg\SalesPad\Helper\Quote as QuoteHelper;
use Lyonscg\SalesPad\Model\Api;
use Lyonscg\SalesPad\Model\Api\Customer as CustomerApi;
use Lyonscg\SalesPad\Model\Api\CustomerAddr as CustomerAddrApi;
use Lyonscg\SalesPad\Model\Api\SalesPad\SalesDocument;
use Lyonscg\SalesPad\Model\Api\SalesPad\SalesLineItem;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\OrderRepositoryInterfaceFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\RequisitionList\Api\Data\RequisitionListInterface;
use Magento\RequisitionList\Api\Data\RequisitionListItemInterface;
use Lyonscg\SalesPad\Model\Config as ConfigModel;
use Magento\Customer\Api\AddressRepositoryInterface;

class Quote
{
    const TYPE = 'QUOTE';

    const SALESPAD_ADDRESS_CODE = 'salespad_address_code';
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
     * @var QuoteHelper
     */
    protected $quoteHelper;

    /**
     * @var CustomerHelper
     */
    protected $customerHelper;

    /**
     * @var CustomerApi
     */
    protected $customerApi;

    /**
     * @var CustomerAddr
     */
    protected $customerAddrApi;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var ConfigModel
     */
    protected $configModel;

    /**
     * @var AddressRepositoryInterface
     */
    protected $addressRepository;

    /**
     * @var CustomerLinkRepositoryInterface
     */
    private $customerLinkRepository;

    /**
     * Quote constructor.
     * @param Api $api
     * @param SalesDocument $salesDocument
     * @param SalesLineItem $salesLineItem
     * @param QuoteHelper $quoteHelper
     * @param CustomerHelper $customerHelper
     * @param Customer $customerApi
     * @param CustomerAddr $customerAddrApi
     * @param CustomerRepositoryInterface $customerRepository
     * @param ConfigModel $configModel
     * @param Logger $logger
     */
    public function __construct(
        Api $api,
        SalesDocument $salesDocument,
        SalesLineItem $salesLineItem,
        QuoteHelper $quoteHelper,
        CustomerHelper $customerHelper,
        CustomerApi $customerApi,
        CustomerAddrApi $customerAddrApi,
        CustomerRepositoryInterface $customerRepository,
        ConfigModel $configModel,
        AddressRepositoryInterface $addressRepository,
        Logger $logger,
        CustomerLinkRepositoryInterface $customerLinkRepository
    ) {
        $this->api = $api;
        $this->salesDocument = $salesDocument;
        $this->salesLineItem = $salesLineItem;
        $this->quoteHelper = $quoteHelper;
        $this->customerHelper = $customerHelper;
        $this->customerApi = $customerApi;
        $this->customerAddrApi = $customerAddrApi;
        $this->customerRepository = $customerRepository;
        $this->configModel = $configModel;
        $this->addressRepository = $addressRepository;
        $this->logger = $logger;
        $this->customerLinkRepository = $customerLinkRepository;
    }

    /**
     * @param RequisitionListInterface $list
     * @return bool|RequisitionListInterface
     * @throws \Exception
     */
    public function createOrUpdate(RequisitionListInterface $list)
    {
        $this->_clearFailures();
        $data = $this->_convert($list);
        $salesDocNum = $this->quoteHelper->getSalesDocNum($list);
        if (!$salesDocNum) {
            // no type specified here because it is specified in the data array
            // creating a quote, so set the Sales_Batch to 'REVIEW'
            $data['Sales_Batch'] = 'REVIEW';
            $response = $this->salesDocument->create($data);
            if (!$response) {
                return false;
            }
            $salesDocNum = $response['Sales_Doc_Num'];
            $this->quoteHelper->setSalesDocNum($list, $salesDocNum);
            // items will be synced separately
            // save without using the repository so we do not loop
            $list->save();
            return true;
        } else {
            if (!$this->salesDocument->update(self::TYPE, $salesDocNum, $data)) {
                $this->logger->debug("Failed to update req list: " . $list->getId());
                return false;
            }
            $list->save();
            return true;
            // items are synced separately
        }
    }

    public function delete($salesDocNum)
    {
        if (!$salesDocNum) {
            $this->logger->debug('requisition list does not have a sales doc num, skipping delete call');
            return true;
        }
        return $this->salesDocument->delete($salesDocNum, self::TYPE);
    }

    public function createOrUpdateItem(RequisitionListItemInterface $item, $salesDocNum = null)
    {
        $this->_clearFailures();
        $itemData = $this->_convertItem($item, $salesDocNum);
        if (!$this->quoteHelper->isItemInSalesPad($item)) {
            $itemResult = $this->salesLineItem->create($itemData);
        } else {
            // update item, delete it first then re-create
            // doing this because we can't change the Item_Number in the line item update, it returns from api
            // saying the item doesn't exist
            $lineNum = $itemData['Line_Num'] ?? false;
            $componentSeqNum = $itemData['Component_Seq_Num'] ?? false;
            // compare to false, Component_Seq_Num can be '0' which is falsey
            if ($lineNum === false || $componentSeqNum === false) {
                // should not be able to get here
                $this->logger->debug('Trying to update item that has no line num or component seq num? ' . print_r($itemData, true));
                return false;
            }
            if ($this->_deleteItem($salesDocNum, $lineNum, $componentSeqNum)) {
                $itemResult = $this->salesLineItem->create($itemData);
            } else {
                $this->logger->debug('Unable to delete item ' . $item->getId() . ' before create for update');
                return false;
            }
        }

        if (!$itemResult) {
            return false;
        }
        $lineNum = $itemResult['Line_Num'];
        $componentSeqNum = $itemResult['Component_Seq_Num'];
        $this->quoteHelper->setItemData($item, $salesDocNum, $lineNum, $componentSeqNum);
        // avoid repository to prevent loops
        $item->save();
        return true;
    }

    /**
     * Wrap actual delete call so we can clear failures without affecting things
     * that delete items as part of a larger set of operations.
     * @param $salesDocNum
     * @param $lineNum
     * @param $componentSeqNum
     * @return mixed|array|bool|int|float|string|null
     */
    public function deleteItem($salesDocNum, $lineNum, $componentSeqNum)
    {
        $this->_clearFailures();
        return $this->_deleteItem($salesDocNum, $lineNum, $componentSeqNum);
    }

    protected function _deleteItem($salesDocNum, $lineNum, $componentSeqNum)
    {
        return $this->salesLineItem->delete(self::TYPE, $salesDocNum, $lineNum, $componentSeqNum);
    }

    protected function _convert(RequisitionListInterface $list)
    {
        $customer = $this->_getCustomer($list);
        $customerName = $customer->getFirstname() . ' ' . $customer->getLastname();
        $customerNum = $this->_getSalesPadCustomerNumber($customer, $list);
        $this->quoteHelper->setCustomerNum($list, $customerNum);

        $customerEmail = $customer->getEmail();

        $subTotal = $this->quoteHelper->getSubtotal($list);
        $tax = $this->quoteHelper->getTax($list);
        $grandTotal = $this->quoteHelper->getGrandTotal($list);

        $projectName = $list->getName();
        $poNumber = $this->quoteHelper->getPoNumber($list);

        $billingCode = $this->_getSalesPadAddressCode($customer, $list, $customerNum, 'billing');
        $shippingCode = $this->_getSalesPadAddressCode($customer, $list, $customerNum, 'shipping');

        $description = $list->getDescription();

        return [
            'Sales_Doc_Type'       => self::TYPE,
            'Sales_Doc_Id'         => 'STANDARDQUOTE',
            'Master_Num'           => -1,
            'Customer_Num'         => $customerNum,
            'Customer_Name'        => $customerName,
            'Email'                => $customerEmail,
            'Currency_ID'          => $this->configModel->getCurrencyId(),
            'Currency_Dec'         => $this->configModel->getCurrencyDec(),
            'Source'               => 'Magento',
            'Bill_To_Address_Code' => $shippingCode,
            'Ship_To_Address_Code' => $billingCode,
            'Subtotal'             => $subTotal,
            'Tax'                  => $tax,
            'Misc_Charge'          => 0,
            'Total'                => $grandTotal,
            'Is_Dropship'          => true,
            'Quote_Num'            => $list->getId(),
            'Customer_PO_Num'      => $poNumber,
            'UserFieldData'        => [$projectName, '', '', $description],
            'UserFieldNames'       => ['xJob_Name', 'xMarketingCode', 'xWeb_Order_Num', 'xWebOrderNotes'],
        ];
    }

    protected function _convertItem(RequisitionListItemInterface $item, $salesDocNum = false)
    {
        $price = $this->quoteHelper->getItemPrice($item, false);
        $origPrice = $this->quoteHelper->getItemOrigPrice($item, false);
        $comment = $this->quoteHelper->getItemComment($item);
        $sidemark = $this->quoteHelper->getItemSidemark($item);
        $lineNum = $this->quoteHelper->getItemLineNum($item);
        $componentSeqNum = $this->quoteHelper->getItemComponentSeqNum($item);
        if (!$salesDocNum) {
            $salesDocNum = $this->_getItemSalesDocNum($item);
            if (!$salesDocNum) {
                throw new \Exception(sprintf('Sales Doc Num for item (%s) not found', strval($item->getId())));
            }
        }

        $data = [
            'Sales_Doc_Type'    => self::TYPE,
            'Sales_Doc_Num'     => $salesDocNum,
            'Item_Number'       => $this->quoteHelper->getItemSku($item),
            'Unit_Of_Measure'   => 'EACH',
            'Quantity'          => $item->getQty(),
            'Unit_Price'        => $origPrice,
            'Markdown_Amount'   => $origPrice - $price,
            'Price_Level'       => 'RETAIL',
            'Item_Curr_Dec'     => '2',
            'Currency_Dec'      => $this->configModel->getCurrencyDec(),
            'Item_Tax_Schedule' => 'AVATAX',
            'Tax_Schedule'      => 'AVATAX',
            'Is_Dropship'       => true,
            'Comment'           => $comment,
            'UserFieldData'     => [$sidemark, $origPrice],
            'UserFieldNames'    => ['xSidemark', 'xOriginalPrice'],
        ];
        if ($lineNum !== null) {
            $data['Line_Num'] = $lineNum;
        }
        if ($componentSeqNum !== null) {
            $data['Component_Seq_Num'] = $componentSeqNum;
        }
        return $data;
    }

    /**
     * @param CustomerInterface $customer
     * @param RequisitionListInterface $list
     * @return string
     */
    protected function _getSalesPadCustomerNumber(CustomerInterface $customer, RequisitionListInterface $list)
    {
        if ($customer !== null) {
            // CRC-638 Moved sales_pad_customer_num customer attribute from a custom to an extension one.
            $customerNum = $customer->getExtensionAttributes()->getSalesPadCustomerNum() ?? false;
        } else {
            $customerNum = false;
        }

        if (!empty($customerNum)) {
            return $customerNum;
        }

        // try order
        $extensionAttributes = $list->getExtensionAttributes();
        if ($extensionAttributes && $extensionAttributes->getSalesPadCustomerNum()) {
            return $extensionAttributes->getSalesPadCustomerNum();
        }

        // customer does exist, but somehow wasn't sent to salespad yet
        $this->logger->debug("creating salespad customer for " . $customer->getId());
        $customerNum = $this->customerApi->create($customer);

        if ($customerNum) {
            $addResult = $this->customerLinkRepository->add(
                $customer->getId(),
                $customer->getEmail(),
                $customer->getWebsiteId(),
                $customerNum
            );

            if (!$addResult) {
                $this->logger->debug(
                    sprintf(
                        'Unable to add customer link for customer_id and sales_pad_customer_num of %s and %s respectively.',
                        $customer->getId(),
                        $customerNum
                    )
                );
            }

            $this->logger->debug("created salespad customer for " . $customer->getId() . ": $customerNum");
        }

        return $customerNum;
    }

    protected function _getCustomer(RequisitionListInterface $list)
    {
        return $this->customerRepository->getById($list->getCustomerId());
    }

    protected function _getSalesPadAddressCode(
        CustomerInterface $customer,
        RequisitionListInterface $list,
        $customerNum,
        $addressType
    ) {
        // address to use
        $address = null;

        $addressCode = $addressType === 'billing' ? $customer->getDefaultBilling() : $customer->getDefaultShipping();
        if ($addressCode) {
            foreach ($customer->getAddresses() as $customerAddress) {
                if ($customerAddress->getId() == $addressCode) {
                    $address = $customerAddress;
                    break;
                }
            }
        } else {
            $addresses = $customer->getAddresses();
            if (!empty($addresses)) {
                $address = $addresses[0];
            }
        }

        if (!empty($address)) {
            if (!empty($salespadAddressCodeAttribute = $address->getCustomAttribute('salespad_address_code'))) {
                $salespadAddressCode = $salespadAddressCodeAttribute->getValue();
            }
            if (!empty($salespadAddressCode)) {
                return $salespadAddressCode;
            }
        }

        if ($address === null) {
            // no address yet, create a blank address
            $customerName = $customer->getFirstname() . ' ' . $customer->getLastname();
            $email = $customer->getEmail();
            $salespadAddressCode = $this->customerAddrApi->createBlankAddress($customerName, $email, $customerNum);
            $this->quoteHelper->setAddressCode($list, $salespadAddressCode, $addressType);
            return $salespadAddressCode;
        }

        // we have an address that hasn't been seen to salespad yet, do that now
        $realCustomer = $this->customerHelper->getCustomerModel($customer);

        $salespadAddressCode = $this->customerAddrApi->create($realCustomer, $address, $customerNum);
        if ($salespadAddressCode) {
            $this->quoteHelper->setAddressCode($list, $salespadAddressCode, $addressType);
            $this->logger->debug("created new address for quote with code $salespadAddressCode");
        }
        if (!empty($address)) {
            $address->setCustomAttribute('salespad_address_code', $salespadAddressCode);
            try {
                $this->addressRepository->save($address);
            } catch (\Exception $e) {
                $this->logger->debug($e->getMessage(). $e->getTraceAsString());
            }
        }
        return $salespadAddressCode;
    }

    protected function _getItemSalesDocNum(RequisitionListItemInterface $item)
    {
        $salesDocNum = $this->quoteHelper->getItemSalesDocNum($item);
        if ($salesDocNum) {
            return $salesDocNum;
        }

        if ($item->getSalespadSalesDocNum()) {
            return $item->getSalespadSalesDocNum();
        }

        // get the list
        $list = $this->quoteHelper->getItemList($item);
        return $this->quoteHelper->getSalesDocNum($list);
    }

    /**
     * @param OrderInterface $order
     * @param string $salespadQuoteNum
     * @param array $orderData
     * @return false|mixed
     * @throws \Exception
     */
    public function transfer(OrderInterface $order, $salespadQuoteNum, $orderData)
    {
        $this->_clearFailures();
        // delete all items for the quote so we can update with what we used in the order
        // this is the same logic that was used in the M1 module
        if (!$this->deleteAllItems($salespadQuoteNum)) {
            $this->logger->debug('Failed to delete items for quote: ' . $salespadQuoteNum);
            return false;
        }

        $this->_convertOrderDataToQuote($orderData, $salespadQuoteNum);

        // sending separately from the bulk order data
        $lineItems = $orderData['LineItems'];
        unset($orderData['LineItems']);

        // update quote from order data
        if (!$this->_updateQuoteFromOrder($orderData, $salespadQuoteNum)) {
            $this->logger->debug('Failed to update address for quote "'. $salespadQuoteNum . '" with order: "' . $order->getIncrementId() . '"');
            return false;
        }
        // copy order items to quote
        $result = $this->_orderItemsToQuote($lineItems, $salespadQuoteNum);
        if (!$result) {
            $this->logger->debug('Failed to repopulate quote "'. $salespadQuoteNum . '" with items from order: "' . $order->getIncrementId() . '"');
            return false;
        }
        // do the transfer
        $response = $this->salesDocument->transfer($salespadQuoteNum, $this->salesDocument::TYPE_QUOTE);
        if (!$response) {
            $this->logger->debug('Failed to transfer quote "' . $salespadQuoteNum . '" for order: "' . $order->getIncrementId() . '"');
            return false;
        } else {
            $salesDocNum = $response['Sales_Doc_Num'];
            return $salesDocNum;
        }
    }

    public function deleteAllItems($salespadQuoteNum)
    {
        return $this->salesDocument->deleteAllItems($salespadQuoteNum, $this->salesDocument::TYPE_QUOTE);
    }

    /**
     * @param array $orderItems
     * @param $salespadQuoteNum
     */
    protected function _orderItemsToQuote($orderItems, $salespadQuoteNum)
    {
        if (empty($orderItems)) {
            $this->logger->debug('No items passed for quote "' . $salespadQuoteNum . '"');
            return false;
        }
        foreach ($orderItems as $orderItemData) {
            if (!$this->salesLineItem->create($orderItemData)) {
                $this->logger->debug('Failed to transfer order item to quote: ' . print_r($orderItemData, true));
                return false;
            }
        }
        return true;
    }

    protected function _updateQuoteFromOrder($orderData, $salespadQuoteNum)
    {
        return $this->salesDocument->update($this->salesDocument::TYPE_QUOTE, $salespadQuoteNum, $orderData);
    }

    protected function _convertOrderDataToQuote(&$orderData, $quoteDocNum)
    {
        $orderData['Sales_Doc_Type'] = 'QUOTE';
        $orderData['Sales_Doc_Num'] = $quoteDocNum;
        $orderData['Sales_Doc_Id'] = 'STANDARDQUOTE';

        for ($i = 0; $i < count($orderData['LineItems']); $i++) {
            $orderData['LineItems'][$i]['Sales_Doc_Num'] = $quoteDocNum;
            $orderData['LineItems'][$i]['Sales_Doc_Type'] = 'QUOTE';
        }
    }

    /**
     * @return array
     */
    public function getFailures()
    {
        $failures = $this->salesDocument->getFailures();
        if (!is_array($failures)) {
            $failures = empty($failures) ? [] : [$failures];
        }
        $itemFailures = $this->salesLineItem->getFailures();
        if (!is_array($itemFailures)) {
            $itemFailures = empty($itemFailures) ? [] : [$itemFailures];
        }
        return array_merge($failures, $itemFailures);
    }

    protected function _clearFailures()
    {
        $this->salesDocument->clearFailures();
        $this->salesLineItem->clearFailures();
    }
}
