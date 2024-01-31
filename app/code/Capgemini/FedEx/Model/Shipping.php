<?php


namespace Capgemini\FedEx\Model;


use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Registry;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\Error;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Rate\CarrierResultFactory;
use Magento\Shipping\Model\Rate\PackageResult;
use Magento\Shipping\Model\Rate\PackageResultFactory;
use Magento\Shipping\Model\Rate\Result;
use Magento\Quote\Model\Quote\Address\RateRequestFactory;

class Shipping extends \Magento\Shipping\Model\Shipping
{
    /**
     * @var PackageResultFactory
     */
    protected $packageResultFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var Registry
     */
    protected $registry;


    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Shipping\Model\Config $shippingConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Shipping\Model\CarrierFactory $carrierFactory,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Shipping\Model\Shipment\RequestFactory $shipmentRequestFactory,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        \Magento\Framework\Math\Division $mathDivision,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        ProductRepositoryInterface $productRepository,
        CheckoutSession $checkoutSession,
        Registry $registry,
        RateRequestFactory $rateRequestFactory = null,
        ?PackageResultFactory $packageResultFactory = null,
        ?CarrierResultFactory $carrierResultFactory = null
    ) {
        parent::__construct(
            $scopeConfig,
            $shippingConfig,
            $storeManager,
            $carrierFactory,
            $rateResultFactory,
            $shipmentRequestFactory,
            $regionFactory,
            $mathDivision,
            $stockRegistry,
            $rateRequestFactory,
            $packageResultFactory,
            $carrierResultFactory
        );
        $this->packageResultFactory = $packageResultFactory
            ?? ObjectManager::getInstance()->get(PackageResultFactory::class);
        $this->productRepository = $productRepository;
        $this->checkoutSession = $checkoutSession;
        $this->registry = $registry;
    }

    /**
     * Checks availability of carrier.
     *
     * @param string $carrierCode
     * @return bool
     */
    private function isShippingCarrierAvailable(string $carrierCode): bool
    {
        return $this->_scopeConfig->isSetFlag(
            'carriers/' . $carrierCode . '/' . $this->_availabilityConfigField,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Prepare carrier to find rates.
     *
     * @param string $carrierCode
     * @param RateRequest $request
     * @return AbstractCarrier
     * @throws \RuntimeException
     */
    private function prepareCarrier(string $carrierCode, RateRequest $request): AbstractCarrier
    {
        $carrier = $this->isShippingCarrierAvailable($carrierCode)
            ? $this->_carrierFactory->create($carrierCode, $request->getStoreId())
            : null;
        if (!$carrier) {
            throw new \RuntimeException('Failed to initialize carrier');
        }
        $carrier->setActiveFlag($this->_availabilityConfigField);
        $result = $carrier->checkAvailableShipCountries($request);
        if (false !== $result && !$result instanceof Error) {
            $result = $carrier->processAdditionalValidation($request);
        }
        if (!$result) {
            /*
             * Result will be false if the admin set not to show the shipping module
             * if the delivery country is not within specific countries
             */
            throw new \RuntimeException('Cannot collect rates for given request');
        } elseif ($result instanceof Error) {
            $this->getResult()->append($result);
            throw new \RuntimeException('Error occurred while preparing a carrier');
        }

        return $carrier;
    }

    /**
     * Collect rates of given carrier
     *
     * @param string $carrierCode
     * @param RateRequest $request
     * @return \Magento\Shipping\Model\Shipping
     */
    public function collectCarrierRates($carrierCode, $request)
    {
        try {
            $carrier = $this->prepareCarrier($carrierCode, $request);
        } catch (\RuntimeException $exception) {
            return $this;
        }

        /** @var Result|\Magento\Quote\Model\Quote\Address\RateResult\Error|null $result */
        $result = null;
        if ($carrier->getConfigData('shipment_requesttype')) {
            $packages = $this->composePackagesForCarrier($carrier, $request);
            if (!empty($packages)) {
                //Multiple shipments
                /** @var PackageResult $result */
                $result = $this->packageResultFactory->create();
                foreach ($packages as $weight => $packageCount) {
                    $request->setPackageWeight($weight);
                    $packageResult = $carrier->collectRates($request);
                    if (!$packageResult) {
                        return $this;
                    } else {
                        $result->appendPackageResult($packageResult, $packageCount);
                    }
                }
            }
        } else {
            // CLMI-604
            $cartItems = $request->getAllItems();
            $overWeightFlag = 0;
            foreach ($cartItems as $item) {
                $product = $this->productRepository->getById($item->getProduct()->getId());
                if ($product->getData('carton1_dimensional_weight') > 150 || $product->getData('carton2_dimensional_weight') > 150) {
                    $overWeightFlag++;
                }
            }
            $this->registry->unregister('cartonOverWeightFlag');
            $this->registry->register('cartonOverWeightFlag', $overWeightFlag);
            $this->checkoutSession->setData('cartonOverWeightFlag', $overWeightFlag);
            $result = $carrier->collectRates($request);
        }
        if (!$result) {
            //One shipment for all items.
            $result = $carrier->collectRates($request);
        }

        if (!$result) {
            return $this;
        } elseif ($result instanceof Result) {
            $this->getResult()->appendResult($result, $carrier->getConfigData('showmethod') != 0);
        } else {
            $this->getResult()->append($result);
        }

        return $this;
    }
}
