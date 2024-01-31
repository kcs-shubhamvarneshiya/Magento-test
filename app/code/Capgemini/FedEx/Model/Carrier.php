<?php

namespace Capgemini\FedEx\Model;

use Magento\Fedex\Model\Carrier as FedExCarrier;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Webapi\Soap\ClientFactory;
use Magento\Framework\Xml\Security;
use Magento\Quote\Model\Quote\Address\RateRequest;

class Carrier extends FedExCarrier
{
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var integer
     */
    protected $_packageCount;

    /**
     * Carrier constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param Security $xmlSecurity
     * @param \Magento\Shipping\Model\Simplexml\ElementFactory $xmlElFactory
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param \Magento\Shipping\Model\Tracking\ResultFactory $trackFactory
     * @param \Magento\Shipping\Model\Tracking\Result\ErrorFactory $trackErrorFactory
     * @param \Magento\Shipping\Model\Tracking\Result\StatusFactory $trackStatusFactory
     * @param \Magento\Directory\Model\RegionFactory $regionFactory
     * @param \Magento\Directory\Model\CountryFactory $countryFactory
     * @param \Magento\Directory\Model\CurrencyFactory $currencyFactory
     * @param \Magento\Directory\Helper\Data $directoryData
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Module\Dir\Reader $configReader
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param array $data
     * @param Json|null $serializer
     * @param ClientFactory|null $soapClientFactory
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        Security $xmlSecurity,
        \Magento\Shipping\Model\Simplexml\ElementFactory $xmlElFactory,
        \Magento\Shipping\Model\Rate\ResultFactory $rateFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\Shipping\Model\Tracking\ResultFactory $trackFactory,
        \Magento\Shipping\Model\Tracking\Result\ErrorFactory $trackErrorFactory,
        \Magento\Shipping\Model\Tracking\Result\StatusFactory $trackStatusFactory,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Directory\Helper\Data $directoryData,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Module\Dir\Reader $configReader,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        array $data = [],
        Json $serializer = null,
        ClientFactory $soapClientFactory = null
    ) {
        parent::__construct(
            $scopeConfig,
            $rateErrorFactory,
            $logger,
            $xmlSecurity,
            $xmlElFactory,
            $rateFactory,
            $rateMethodFactory,
            $trackFactory,
            $trackErrorFactory,
            $trackStatusFactory,
            $regionFactory,
            $countryFactory,
            $currencyFactory,
            $directoryData,
            $stockRegistry,
            $storeManager,
            $configReader,
            $productCollectionFactory,
            $data,
            $serializer,
            $soapClientFactory
        );
        $this->productRepository = $productRepository;
        $this->_packageCount = 0;
    }

    /**
     * @param RateRequest $request
     * @return $this|Carrier
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function setRequest(RateRequest $request)
    {
        parent::setRequest($request);
        $r = $this->_rawRequest;

        // CLMI-604 - add logic from M1 to add dimensional weight information

        $dimensionData = [];
        $itemQty = 0;
        foreach ($request->getAllItems() as $item) {
            $productId = $item->getProduct()->getId();
            $productType = $item->getProduct()->getTypeId();
            $product = $this->productRepository->getById($productId);
            $cart1DimWeight = $product->getData('carton1_dimensional_weight');
            $cart2DimWeight = $product->getData('carton2_dimensional_weight');
            $finalAmount = $product->getFinalPrice();
            if ($productType === 'configurable') {
                $itemQty = $item->getQty();
            }
            if ($productType === 'simple') {
                $dimensionData[$productId] = [
                    'Carton1DimensionalWeight' => $cart1DimWeight,
                    'Carton2DimensionalWeight' => $cart2DimWeight,
                    'FinalAmount' => $finalAmount,
                    'ItemQty' => $itemQty,
                ];
            }
        }

        if (count($dimensionData) > 0) {
            $r->setDimensionData($dimensionData);
        }
        $this->setRawRequest($r);
        return $this;
    }

    protected function addDimensionsToPackageLineItems()
    {
        $packageLineItems = [];
        $r = $this->_rawRequest;
        $dimensionData = $r->getDimensionData();
        $i = 1;
        $packageCount = 0;

        foreach ($dimensionData as $key => $value)
        {
            if($value['Carton1DimensionalWeight'] < 150 && $value['Carton2DimensionalWeight'] < 150)
            {
                $packageLineItems[$i - 1] = [
                    'GroupPackageCount' => $value['ItemQty'],
                    'Weight' => [
                        'Value' => (float)$value['Carton1DimensionalWeight'],
                        'Units' => $this->getConfigData('unit_of_measure')
                    ],
                    'InsuredValue' => [
                        'Amount' => (float)$value['FinalAmount'],
                        'Currency' => $this->getCurrencyCode()
                    ],
                ];
                $packageCount += $value['ItemQty'];
                if ($value['Carton2DimensionalWeight'] && $value['Carton2DimensionalWeight'] != 'NULL' && $value['Carton2DimensionalWeight'] != null && $value['Carton2DimensionalWeight'] != 0) {
                    $i++;
                    $packageCount += $value['ItemQty'];
                    $packageLineItems[$i - 1] = [
                        'GroupPackageCount' => $value['ItemQty'],
                        'Weight' => [
                            'Value' => (float)$value['Carton2DimensionalWeight'],
                            'Units' => $this->getConfigData('unit_of_measure')
                        ],
                        'InsuredValue' => [
                            'Amount' => (float)$value['FinalAmount'],
                            'Currency' => $this->getCurrencyCode()
                        ],
                    ];
                }

                $i++;
            } else {
                $packageLineItems[$i - 1] = [
                    'GroupPackageCount' => $value['ItemQty'],
                    'Weight' => [
                        'Value' => 1,
                        'Units' => $this->getConfigData('unit_of_measure')
                    ],
                    'InsuredValue' => [
                        'Amount' => (float)$value['FinalAmount'],
                        'Currency' => $this->getCurrencyCode()
                    ],
                ];
                $packageCount += $value['ItemQty'];
                $i++;
            }
        }
        $this->_packageCount = $packageCount;
        return $packageLineItems;
    }

    /**
     * Forming request for rate estimation depending to the purpose
     *
     * @param string $purpose
     * @return array
     */
    protected function _formRateRequest($purpose)
    {
        $r = $this->_rawRequest;
        $ratesRequest = [
            'WebAuthenticationDetail' => [
                'UserCredential' => ['Key' => $r->getKey(), 'Password' => $r->getPassword()],
            ],
            'ClientDetail' => ['AccountNumber' => $r->getAccount(), 'MeterNumber' => $r->getMeterNumber()],
            'Version' => $this->getVersionInfo(),
            'RequestedShipment' => [
                'DropoffType' => $r->getDropoffType(),
                'ShipTimestamp' => date('c'),
                'PackagingType' => $r->getPackaging(),
                'TotalInsuredValue' => ['Amount' => $r->getValue(), 'Currency' => $this->getCurrencyCode()],
                'Shipper' => [
                    'Address' => ['PostalCode' => $r->getOrigPostal(), 'CountryCode' => $r->getOrigCountry()],
                ],
                'Recipient' => [
                    'Address' => [
                        'PostalCode' => $r->getDestPostal(),
                        'CountryCode' => $r->getDestCountry(),
                        'Residential' => (bool)$this->getConfigData('residence_delivery'),
                    ],
                ],
                'ShippingChargesPayment' => [
                    'PaymentType' => 'SENDER',
                    'Payor' => ['AccountNumber' => $r->getAccount(), 'CountryCode' => $r->getOrigCountry()],
                ],
                'CustomsClearanceDetail' => [
                    'CustomsValue' => ['Amount' => $r->getValue(), 'Currency' => $this->getCurrencyCode()],
                ],
                'RateRequestTypes' => 'LIST',
                'PackageDetail' => 'INDIVIDUAL_PACKAGES',
                'RequestedPackageLineItems' => $this->addDimensionsToPackageLineItems(),
                'PackageCount' => strval($this->_packageCount),

                /*
                'RequestedPackageLineItems' => [
                    '0' => [
                        'Weight' => [
                            'Value' => (double)$r->getWeight(),
                            'Units' => $this->getConfigData('unit_of_measure'),
                        ],
                        'GroupPackageCount' => 1,
                    ],
                ],
                */
            ],
        ];

        if ($r->getDestCity()) {
            $ratesRequest['RequestedShipment']['Recipient']['Address']['City'] = $r->getDestCity();
        }

        if ($purpose == self::RATE_REQUEST_GENERAL) {
            if (!isset($ratesRequest['RequestedShipment']['RequestedPackageLineItems'][0]['InsuredValue'])) {
                $ratesRequest['RequestedShipment']['RequestedPackageLineItems'][0]['InsuredValue'] = [
                    'Amount' => $r->getValue(),
                    'Currency' => $this->getCurrencyCode(),
                ];
            }
        } else {
            if ($purpose == self::RATE_REQUEST_SMARTPOST) {
                $ratesRequest['RequestedShipment']['ServiceType'] = self::RATE_REQUEST_SMARTPOST;
                $ratesRequest['RequestedShipment']['SmartPostDetail'] = [
                    'Indicia' => (double)$r->getWeight() >= 1 ? 'PARCEL_SELECT' : 'PRESORTED_STANDARD',
                    'HubId' => $this->getConfigData('smartpost_hubid'),
                ];
            }
        }

        return $ratesRequest;
    }

    /**
     * Processing additional validation to check if carrier applicable.
     *
     * CLMI-604 - remove weight checks
     *
     * @param \Magento\Framework\DataObject $request
     * @return $this|bool|\Magento\Framework\DataObject
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @since 100.2.6
     */
    public function processAdditionalValidation(\Magento\Framework\DataObject $request)
    {
        //Skip by item validation if there is no items in request
        if (!count($this->getAllItems($request))) {
            return $this;
        }

        $errorMsg = '';
        $showMethod = $this->getConfigData('showmethod');

        if (!$errorMsg && !$request->getDestPostcode() && $this->isZipCodeRequired($request->getDestCountryId())) {
            $errorMsg = __('This shipping method is not available. Please specify the zip code.');
        }

        if ($errorMsg && $showMethod) {
            $error = $this->_rateErrorFactory->create();
            $error->setCarrier($this->_code);
            $error->setCarrierTitle($this->getConfigData('title'));
            $error->setErrorMessage($errorMsg);

            return $error;
        } elseif ($errorMsg) {
            return false;
        }

        return $this;
    }
}
