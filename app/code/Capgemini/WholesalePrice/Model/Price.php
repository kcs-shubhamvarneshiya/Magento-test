<?php
/**
 * Capgemini_WholesalePrice
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_WholesalePrice
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */

declare(strict_types=1);

namespace Capgemini\WholesalePrice\Model;

use Capgemini\CompanyType\Model\Config;
use Capgemini\VcServiceProductPrice\Service\Product\Price as PriceService;
use Capgemini\WholesalePrice\Helper\Cache;
use Capgemini\WholesalePrice\Helper\Customer;
use Capgemini\WholesalePrice\Helper\Data;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Price class
 */
class Price
{
    public const REPORTING_BRAND_ATTRIBUTE = 'reporting_brand';

    public const DIVISION_ATTRIBUTE = 'division';

    public const CUSTOMER_NUMBER_ATTRIBUTE = 'customer_number';

    /**
     * @var Customer
     */
    protected Customer $customerHelper;

    /**
     * @var PriceService
     */
    protected PriceService $priceService;

    /**
     * @var Cache
     */
    protected Cache $priceCache;

    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @var Data
     */
    protected Data $advancedPricingHelper;

    /**
     * @var Config
     */
    protected Config $companyTypeConfig;

    /**
     * @var CustomerRepositoryInterface
     */
    protected CustomerRepositoryInterface $customerRepository;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @var string|null
     */
    private ?string $customerType = null;

    /**
     * @var array|null
     */
    private ?array $customerAttributes = null;

    /**
     * @var array|string[]
     */
    protected array $accountDivisionMapping = [
        'tech' => 'TE',
        'gl' => 'GL'
    ];

    /**
     * @param Customer $customerHelper
     * @param PriceService $priceService
     * @param Cache $priceCache
     * @param ProductRepositoryInterface $productRepository
     * @param Data $advancedPricingHelper
     * @param Config $companyTypeConfig
     * @param CustomerRepositoryInterface $customerRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Customer $customerHelper,
        PriceService                $priceService,
        Cache                       $priceCache,
        ProductRepositoryInterface  $productRepository,
        Data                        $advancedPricingHelper,
        Config                      $companyTypeConfig,
        CustomerRepositoryInterface $customerRepository,
        StoreManagerInterface       $storeManager
    ) {
        $this->customerHelper = $customerHelper;
        $this->priceService = $priceService;
        $this->priceCache = $priceCache;
        $this->productRepository = $productRepository;
        $this->advancedPricingHelper = $advancedPricingHelper;
        $this->companyTypeConfig = $companyTypeConfig;
        $this->customerRepository = $customerRepository;
        $this->storeManager = $storeManager;

        $this->initCustomerData();
    }

    /**
     * Initialize needed customer data for business logic
     *
     * @param null $customerModel
     * @return void
     */
    private function initCustomerData($customerModel = null): void
    {
        $this->customerType = $this->customerHelper->getCustomerType($customerModel);
        $this->customerAttributes = $this->customerHelper->getCustomerAttributes($customerModel);
    }

    /**
     * @param $productsArray
     * @param null $currency
     *
     * @return array|null
     *
     * @throws NoSuchEntityException
     */
    public function getCustomerPricesBunch($productsArray, $currency = null): ?array
    {
        $cachedPricesData = [];
        $updatePricesData = [];
        $skusToUpdate = [];

        foreach ($productsArray as $product) {
            $sku = $product->getSku();
            if ($this->canUseAdvancedPricing($product)) {
                $accountNumber = $this->getCustomerAccountNumber($product);
                $price = $this->loadPriceFromCache($accountNumber['account'], $sku);
                if (!$price) {
                    $skusToUpdate[$sku] = [
                        'account_data' => $accountNumber
                    ];
                } else {
                    $cachedPricesData[$product->getSku()] = $price;
                }
            }
        }

        if (!empty($skusToUpdate)) {
            $accountData = array_unique(
                array_column(
                    $skusToUpdate,
                    'account_data'
                ),
                SORT_REGULAR
            );
            $accountData = array_values($accountData);
            $updatePricesData = $this->priceService->validate(
                $accountData,
                array_keys($skusToUpdate),
                $currency ?? $this->storeManager->getStore()->getCurrentCurrencyCode()
            );

            if (!empty($updatePricesData)) {
                $this->savePricesToCache($skusToUpdate, $updatePricesData);
            }
        }

        return array_merge($cachedPricesData, $updatePricesData);
    }

    /**
     * @param ProductInterface $product
     * @param string|null $currency
     * @param int|null $customerId
     *
     * @return float|null
     *
     * @throws LocalizedException
     *
     * @throws NoSuchEntityException
     */
    public function getCustomerPrice(ProductInterface $product, $currency = null, $customerId = null): ?float
    {
        $price = null;
        if ($customerId) {
            $this->initCustomerData($this->customerRepository->getById($customerId));
        }

        if (!$currency) {
            $currency = $this->storeManager->getStore()->getCurrentCurrencyCode();
        }

        if ($this->canUseAdvancedPricing($product)) {
            $accountNumber = $this->getCustomerAccountNumber($product);
            $sku = $product->getSku();
            $price = $this->loadPriceFromCache($accountNumber['account'], $sku);
            if (!$price) {
                $priceData = $this->priceService->validate([$accountNumber], [$sku], $currency);
                $price = $priceData[$sku] ?? null;
                $this->savePriceToCache($accountNumber['account'], $sku, $price);
            }
        } else {
            return $price;
        }

        return (float)$price;
    }

    /**
     * @param $product
     * @return bool
     */
    public function canUseAdvancedPricing($product): bool
    {
        if ($this->advancedPricingHelper->isEnabled()
            && $this->customerType == Config::WHOLESALE) {
            $pricingBrandsValues = $this->advancedPricingHelper->getAdvancedPricingBrands();
            $productReportingBrandValue = $product->getData(self::REPORTING_BRAND_ATTRIBUTE);
            $customerNumberAttributeValue = $this->getCustomerAccountNumber($product);

            if (in_array($productReportingBrandValue, $pricingBrandsValues)
                && $customerNumberAttributeValue) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $product
     * @return array|null
     */
    public function getCustomerAccountNumber($product): ?array
    {
        $divisionAttributeText = $product->getAttributeText(self::DIVISION_ATTRIBUTE);
        if ($divisionAttributeText) {
            $customerNumberAttributeCode = self::CUSTOMER_NUMBER_ATTRIBUTE
                . '_' . $divisionAttributeText;

            $accountNumber = $this->customerAttributes[$customerNumberAttributeCode] ?? null;
            if ($accountNumber) {
                $division = $this->accountDivisionMapping[$divisionAttributeText] ?? strtoupper($divisionAttributeText);
                return [
                    'division' => $division,
                    'account' => $accountNumber
                ];
            }
        }

        return null;
    }

    /**
     * @param $productsData
     * @param $pricesData
     *
     * @return void
     */
    private function savePricesToCache($productsData, $pricesData): void
    {
        foreach ($productsData as $sku => $data) {
            $accountNumber = $data['account_data']['account'] ?? null;
            if ($accountNumber) {
                $price = $pricesData[$sku] ?? null;
                if ($price) {
                    $this->savePriceToCache($accountNumber, $sku, $price);
                }
            }
        }
    }

    /**
     * @param $accountNumber
     * @param $sku
     * @param $price
     *
     * @return void
     */
    private function savePriceToCache($accountNumber, $sku, $price): void
    {
        $cacheId = $this->getCacheId($accountNumber, $sku);

        $this->priceCache->save($price, $cacheId);
    }

    /**
     * @param $accountNumber
     * @param $sku
     *
     * @return bool|string
     */
    private function loadPriceFromCache($accountNumber, $sku)
    {
        $cacheId = $this->getCacheId($accountNumber, $sku);

        return $this->priceCache->load($cacheId);
    }

    /**
     * @param $accountNumber
     *
     * @param $sku
     * @return string
     */
    private function getCacheId($accountNumber, $sku): string
    {
        return $this->priceCache->getId($accountNumber, $sku);
    }
}
