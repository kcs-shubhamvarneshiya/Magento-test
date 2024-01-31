<?php

namespace Capgemini\PartnersInsight\Helper;

use Amasty\Orderattr\Model\Entity\EntityResolver;
use Amasty\Orderattr\Model\Value\Metadata\FormFactory;
use Amasty\Orderattr\Model\Value\Metadata\Form;
use Capgemini\PartnersInsight\Model\Config;
use Lyonscg\SalesPad\Model\FakeOrder;
use Lyonscg\SalesPad\Model\FakeOrderFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory;
use Magento\Fedex\Model\Carrier as FedexCarrier;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Sales\Api\Data\OrderAddressExtensionFactory;
use Magento\Sales\Api\Data\OrderAddressInterface;
use Magento\Sales\Api\Data\OrderItemExtensionFactory;
use Magento\Sales\Api\OrderAddressRepositoryInterface;
use Magento\Sales\Model\Order as OrderModel;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Order\AddressFactory;
use Magento\Sales\Model\Order\ItemFactory;
use Magento\Sales\Model\Order\PaymentFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Order
 * @package Capgemino\PartnersInsight\Helper
 * @see \Amasty\OrderAttr\Block\Order\Attributes::getOrderAttributesData()
 */
class Creditmemo extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var FormFactory
     */
    protected $metadataFormFactory;

    /**
     * @var EntityResolver
     */
    protected $entityResolver;

    /**
     * @var OrderAddressExtensionFactory
     */
    protected $orderAddressExtensionFactory;

    /**
     * @var OrderAddressRepositoryInterface
     */
    protected $orderAddressRepository;

    /**
     * @var FakeOrderFactory
     */
    protected $fakeOrderFactory;

    /**
     * @var ItemFactory
     */
    protected $orderItemFactory;

    /**
     * @var AddressFactory
     */
    protected $orderAddressFactory;

    /**
     * @var PaymentFactory
     */
    protected $paymentFactory;

    /**
     * @var FedexCarrier
     */
    protected $fedexCarrier;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    protected $productFactory;

    /**
     * @var OrderItemExtensionFactory
     */
    protected $orderItemExtensionFactory;

    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    /**
     * @var array
     */
    protected $addressCodes = [];

    protected $regionCollectionFactory;

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var Json
     */
    protected $jsonSerializer;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    protected $orderData;

    /**
     * Order constructor.
     * @param Context $context
     * @param FormFactory $metadataFormFactory
     * @param EntityResolver $entityResolver
     * @param OrderAddressExtensionFactory $orderAddressExtensionFactory
     * @param OrderAddressRepositoryInterface $orderAddressRepository
     * @param FakeOrderFactory $fakeOrderFactory
     * @param ItemFactory $orderItemFactory
     * @param AddressFactory $orderAddressFactory
     * @param PaymentFactory $paymentFactory
     * @param FedexCarrier $fedexCarrier
     * @param ProductRepositoryInterface $productRepository
     * @param ProductFactory $productFactory
     * @param OrderItemExtensionFactory $orderItemExtensionFactory
     * @param OrderFactory $orderFactory
     * @param CollectionFactory $regionCollectionFactory
     * @param OrderRepositoryInterface $orderRepository
     * @param Json $jsonSerializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        FormFactory $metadataFormFactory,
        EntityResolver $entityResolver,
        OrderAddressExtensionFactory $orderAddressExtensionFactory,
        OrderAddressRepositoryInterface $orderAddressRepository,
        FakeOrderFactory $fakeOrderFactory,
        ItemFactory $orderItemFactory,
        AddressFactory $orderAddressFactory,
        PaymentFactory $paymentFactory,
        FedexCarrier $fedexCarrier,
        ProductRepositoryInterface $productRepository,
        ProductFactory $productFactory,
        OrderItemExtensionFactory $orderItemExtensionFactory,
        OrderFactory $orderFactory,
        \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory,
        OrderRepositoryInterface $orderRepository,
        Json $jsonSerializer,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->metadataFormFactory = $metadataFormFactory;
        $this->entityResolver = $entityResolver;
        $this->orderAddressExtensionFactory = $orderAddressExtensionFactory;
        $this->orderAddressRepository = $orderAddressRepository;
        $this->fakeOrderFactory = $fakeOrderFactory;
        $this->orderItemFactory = $orderItemFactory;
        $this->orderAddressFactory = $orderAddressFactory;
        $this->paymentFactory = $paymentFactory;
        $this->fedexCarrier = $fedexCarrier;
        $this->productRepository = $productRepository;
        $this->productFactory = $productFactory;
        $this->orderItemExtensionFactory = $orderItemExtensionFactory;
        $this->orderFactory = $orderFactory;
        $this->regionCollectionFactory = $regionCollectionFactory;
        $this->orderRepository = $orderRepository;
        $this->logger = $logger;
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * @param OrderModel $order
     * @return array
     */
    public function getAmastyOrderAttributeData(OrderModel $order)
    {
        if (!$order || !$order->getId()) {
            return [];
        }

        $entity = $this->entityResolver->getEntityByOrder($order);
        if ($entity->isObjectNew()) {
            return [];
        }

        /** @var Form $form */
        $form = $this->metadataFormFactory->create();
        $form->setFormCode('frontend_order_view')
            ->setEntity($entity)
            ->setStore($order->getStore());

        //return $form->getAllowedAttributes();
        // may need to adjust what output format is used?
        $outputData = $form->outputData(\Magento\Eav\Model\AttributeDataFactory::OUTPUT_FORMAT_HTML);

        $amastyAttributes = [];

        foreach ($outputData as $attributeCode => $data) {
            if (!empty($data)) {
                $amastyAttributes[$attributeCode] = $data;
            }
        }

        return $amastyAttributes;
    }

    /**
     * @param OrderAddressInterface $orderAddress
     * @param $addressCode
     */
    public function saveAddressCodeToOrderAddress(OrderAddressInterface $orderAddress, $addressCode)
    {
        $extensionAttributes = $orderAddress->getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes->setSalespadAddressCode($addressCode);
        }
        $orderAddress->setExtensionAttributes($extensionAttributes);
        $orderAddress->setSalespadAddressCode($addressCode);
        $this->orderAddressRepository->save($orderAddress);
    }

    /**
     * Create a list of possible Address Codes to use
     * Generates the same results as the M1 code
     */
    protected function _populateAddressCodeList()
    {
        if (!empty($this->addressCodes)) {
            return;
        }

        $this->addressCodes[0] = 'PRIMARY';
        $this->addressCodes[1] = 'SECONDARY';
        $this->addressCodes[2] = 'THIRD';
        $this->addressCodes[3] = 'FOURTH';
        $this->addressCodes[4] = 'FIFTH';
        $this->addressCodes[5] = 'SIXTH';
        $this->addressCodes[6] = 'SEVENTH';
        $this->addressCodes[7] = 'EIGHTH';
        $this->addressCodes[8] = 'NINTH';
        $this->addressCodes[9] = 'TENTH';
        $this->addressCodes[10] = 'ELEVEN';
        $this->addressCodes[11] = 'TWELFTH';
        $this->addressCodes[12] = 'THIRTEEN';
        // M1 code has this as FORTEEN
        $this->addressCodes[13] = 'FOURTEEN';
        $this->addressCodes[14] = 'FIFTEEN';
        $this->addressCodes[15] = 'SIXTEEN';
        $this->addressCodes[16] = 'SEVENTEEN';
        $this->addressCodes[17] = 'EIGHTEEN';
        // M1 code has this as NINTEEN
        $this->addressCodes[18] = 'NINETEEN';

        for ($i = 19; $i < 500; $i++) {
            // get the ones, tens, and hundreds digits
            $num = intval($i + 1);
            $ones = $num % 10;
            $num -= $ones;
            $tens = $num % 100;
            $num -= $tens;
            $tens /= 10;
            $hnds = $num / 100;
            $res = [];

            if ($hnds > 0) {
                $res[] = "$hnds-HND";
            }

            if ($tens === 0) {
                if ($ones > 0) {
                    $res[] = $ones;
                }
            } elseif ($tens === 1) {
                switch ($ones) {
                    case 0:
                        $res[] = 'TENTH';
                        break;
                    // M1 code has this as ELEVEN
                    case 1:
                        $res[] = 'ELEVENTH';
                        break;
                    case 2:
                        $res[] = 'TWELFTH';
                        break;
                    case 3:
                        $res[] = 'THIRTEEN';
                        break;
                    default:
                        $res[] = $ones . 'TEEN';
                        break;
                }
            } elseif ($tens > 1) {
                $res[] = $tens . 'TY';
                if ($ones > 0) {
                    $res[] = $ones;
                }
            }

            $this->addressCodes[$i] = implode('-', $res);
        }
    }

    /**
     * This assumes $addressCode is the "largest" address code from the sorted list
     * of customer address codes.
     *
     * @param $addressCode
     * @return mixed
     */
    public function getNextAddressCode($addressCode)
    {
        $this->_populateAddressCodeList();
        $idx = array_search($addressCode, $this->addressCodes);
        if ($idx === false) {
            return $this->addressCodes[0];
        }

        // next code
        $idx += 1;
        if (isset($this->addressCodes[$idx])) {
            return $this->addressCodes[$idx];
        } else {
            $max = count($this->addressCodes) - 1;
            return $this->addressCodes[$max];
        }
    }

    /**
     * @return string
     */
    public function getFirstAddressCode()
    {
        $this->_populateAddressCodeList();
        return $this->addressCodes[0];
    }

    /**
     * Sorts the list of address codes from SalesPad according to the order from _populateAddressCodeList()
     *
     * Address codes that were not generated by that method will not be returned in this list.  This is
     * basically the same logic as the M1 code, except that it will handle gaps in the address codes.
     *
     * @param array $unsortedAddressCodes
     * @param $customerId
     * @return array
     * @throws \Exception
     */
    public function sortAddressCodes($unsortedAddressCodes, $customerId)
    {
        $this->_populateAddressCodeList();
        if (count($unsortedAddressCodes) > 500) {
            throw new \Exception(__('Customer %1 trying to create 501st address', $customerId));
        }
        $sortedAddressCodes = [];
        foreach ($unsortedAddressCodes as $unsAddrCode) {
            $idx = array_search($unsAddrCode, $this->addressCodes);
            if ($idx !== false) {
                $sortedAddressCodes[] = $idx;
            }
        }

        sort($sortedAddressCodes);

        for ($i = 0; $i < count($sortedAddressCodes); $i++) {
            $sortedAddressCodes[$i] = $this->addressCodes[$sortedAddressCodes[$i]];
        }

        return $sortedAddressCodes;
    }

    protected function _convertOrderItem(array $orderItemData)
    {
        $itemConvert = [
            'sku' => ['sku'],
            'productName' => ['name']
        ];
        $data = [];
        foreach ($itemConvert as $salesPadField => $fields) {
            foreach ($fields as $field) {
                $data[$field] = $orderItemData[$salesPadField];
            }
        }
        $data['qty_approved'] = $orderItemData['qtyApproved'];
        $data['price'] = $orderItemData['unitPrice']['value'];
        $data['subtotal'] = $orderItemData['subtotal']['value'];


        /** @var \Magento\Sales\Model\Order\Item $orderItem */
        $orderItem = $this->orderItemFactory->create();
        $orderItem->setData($data);

        /** @var \Magento\Sales\Api\Data\OrderItemExtensionInterface $extAttrs */
        $extAttrs = $orderItem->getExtensionAttributes();
        $extAttrs = $extAttrs ? $extAttrs : $this->orderItemExtensionFactory->create();
        $orderItem->setExtensionAttributes($extAttrs);
        $orderItem->setProductType('default');

        $sku = $data['sku'] ?: '';
        if ($sku) {
            try {
                $product = $this->productRepository->get($sku);
                $orderItem->setProductId($product->getId());
                $orderItem->setProductType($product->getTypeId());
            } catch (NoSuchEntityException $e) {
                // product no longer exists, or is otherwise not in Magento
                $product = $this->productFactory->create();
                $product->setProductType('simple');
                $orderItem->setProduct($product);
            }
        }

        return $orderItem;
    }

    protected function _convertCreditmemo(array $orderData)
    {

        $data = [];
        $incrementId = $orderData['creditMemoId'];
        $realIncrementId = $orderData['creditMemoId'];

        $order = $this->orderFactory->create();
        $order->setId(-1);
        //$order->setData($data);
        $order->setIncrementId($incrementId);
        //$order->setShippingDescription($this->_getShippingDescription(trim($orderData['Shipping_Method'])));

        $order->setCreatedAt($this->convertDate($orderData['creditMemoDate']));
        // $realOrder is used for reordering

        $source = trim(strtolower($orderData['status']));
        switch ($source) {
            case 'open':
                $salesPadStatus = __('Pending');
                break;
            case 'history':
                $salesPadStatus = __('Complete');
                break;
            case 'void':
                $salesPadStatus = __('Canceled');
                break;
            default:
                $salesPadStatus = __('Pending');
                break;
        }
        //$order->setSalesPadStatus($salesPadStatus);

        return $order;
    }

    /**
     * @param $incrementId
     * @return OrderModel
     */
    public function getRealOrder($incrementId)
    {
        /** @var \Magento\Sales\Model\Order $realOrder */
        $realOrder = $this->orderFactory->create();
        $realOrder->loadByIncrementId($incrementId);
        return $realOrder;
    }

    protected function _convertAddress(array $addressData, $firstName, $lastName)
    {
        $addressConvert = [
            'zip' => 'postcode',
            'city' => 'city',
            'countryCode' => 'country_id',
            'name' => 'company',
        ];
        $data = [];
        foreach ($addressConvert as $piField => $field) {
            $data[$field] = trim($addressData[$piField]);
        }

        $name = $addressData['name'] ?: '';
        $data['firstname'] = '';
        $data['lastname'] = '';

        $regionId = trim($addressData['state'] ?: '');
        /** @var \Magento\Directory\Model\ResourceModel\Region\Collection $regionCollection */
        $regionCollection = $this->regionCollectionFactory->create();
        $regionCollection->addCountryFilter($addressData['countryCode']);
        $regionCollection->addRegionCodeFilter($regionId);
        /** @var \Magento\Directory\Model\Region $region */
        $region = $regionCollection->getFirstItem();
        if ($region->getId()) {
            $data['region_id'] = $region->getCode();
            $data['region'] = $region->getName();
        } else {
            $data['region_id'] = $regionId;
            $data['region'] = $regionId;
        }


        $address = $this->orderAddressFactory->create();
        $address->setData($data);
        return $address;
    }

    protected function _getShippingDescription($salesPadShippingCode)
    {
        $code = '';
        switch ($salesPadShippingCode) {
            case 'BESTWAY':
                $code = 'FEDEX_GROUND';
                break;
            case 'FEDX2':
                $code = 'FEDEX_2_DAY';
                break;
            case 'FEDEXPRI':
                $code = 'PRIORITY_OVERNIGHT';
                break;
            case 'FEDEXNDS':
                $code = 'STANDARD_OVERNIGHT';
                break;
            default:
                return $salesPadShippingCode;
        }
        return $this->fedexCarrier->getConfigData('title') . ' - ' . $this->fedexCarrier->getCode('method', $code);
    }

    public function partnersInsightOrderToMagentoOrder(array $orderData)
    {

        $this->orderData = $orderData['data'];
        /** @var \Lyonscg\SalesPad\Model\FakeOrder $order */
        $order = $this->_convertCreditmemo($orderData['data']);

        // TODO SET PURCHASE ORDER

        /** @var \Magento\Sales\Model\Order\Payment $payment */
        $payment = $this->paymentFactory->create();
//        $xPaymentType = trim($this->_getUserFieldData($order['order'], 'xPaymentType'));
//        $xCardType = trim($this->_getUserFieldData($order['order'], 'xCardType'));
//        $xCardLast4Text = trim($this->_getUserFieldData($order['order'], 'xCardLast4Text'));
        $payment->setMethod('checkmo');
//        if (in_array(strtolower($xPaymentType), ['web order', 'credit card'])) {
//            $payment->setData('_salespad_label', __('Credit Card'));
//            $format = (strtolower($xCardType) == 'amex' ? '%s **** ****** *%s' : '%s **** **** **** %s');
//            $ccNumTrunc = sprintf($format, ucfirst(strtolower($xCardType)), strval($xCardLast4Text));
//            $payment->setData('_salespad_text', $ccNumTrunc);
//        } else {
//
//        }
        $order->setPayment($payment);

        $items = [];
        $itemsData = $orderData['data']['creditMemoLines'] ?: [];
        foreach ($itemsData as $itemData) {
            $item = $this->_convertOrderItem($itemData);
            $item->setOrder($order);
            $items[] = $item;
        }

        $name = $order['brand'] ?: '';

        $firstName = '';
        $lastName = '';
        $order->setItems($items);

        $order->setCustomerFirstname($firstName);
        $order->setCustomerLastname($lastName);

        $billingAddress = $this->_convertAddress($orderData['data']['billToAddress'], $firstName, $lastName);
        //$shippingAddress = $this->_convertAddress($orderData['data']['shipToAddress'], $firstName, $lastName);

        $order->setBillingAddress($billingAddress);
        //$order->setShippingAddress($shippingAddress);


        return $order;
    }

    public function getIncrementId(array $orderData, $allowSubstitute = true)
    {
        $incrementId = $this->_getUserFieldData($orderData, 'orderId');
        if (!$incrementId && $allowSubstitute) {
            $incrementId = $orderData['orderId'];
        }
        return $incrementId;
    }

    public function getJobName(array $orderData)
    {
        return $this->_getUserFieldData($orderData, 'xJob_Name');
    }

    protected function _getUserFieldData(array $orderData, $userField)
    {
        $userFieldNames = $orderData['UserFieldNames'] ?? [];
        $userFieldNames = array_flip($userFieldNames);
        $idx = $userFieldNames[$userField] ?: -1;
        if ($idx > -1) {
            return $orderData['UserFieldData'][$idx] ?: '';
        } else {
            return '';
        }
    }

    public function convertDate($date)
    {
        if (!$date) {
            return null;
        }
        preg_replace('/\.\d\d\d/', '', $date);
        $parts = explode('T', $date);
        if (count($parts) !== 2) {
            return null;
        }
        return implode(' ', $parts);
    }

    /**
     * @param $incrementId
     * @return OrderModel
     */
    public function getOrderData($incrementId)
    {
        return $this->orderData;
    }

    public function getPoNumber()
    {
        return $this->orderData['purchaseOrder'];
    }

    public function getCreditmemoDate()
    {
        return $this->orderData['creditMemoDate'];
    }

    public function getStatus()
    {
        return $this->orderData['status'];
    }



    /**
     * @param $incrementId
     * @return OrderModel
     */
    public function getOrder($incrementId)
    {
        return $this->orderFactory->create()->loadByIncrementId($incrementId);
    }
}
