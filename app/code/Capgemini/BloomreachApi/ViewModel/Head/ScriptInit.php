<?php

namespace Capgemini\BloomreachApi\ViewModel\Head;

use Bloomreach\Connector\Block\ConfigurationSettingsInterface;
use Bloomreach\Connector\ViewModel\Head\ScriptInit as Orig;
use Capgemini\BloomreachApi\Helper\Data as ModuleHelper;
use Capgemini\BloomreachApi\Plugin\ConfigSave;
use Lyonscg\Catalog\Plugin\App\Action\ContextPlugin;
use Magento\Catalog\Block\Category\View;
use Magento\Catalog\Helper\Data;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Customer\Model\Context;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Model\SessionFactory;
use Magento\Customer\Model\Visitor;
use Magento\Directory\Model\Currency;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\Utility\Files;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\GroupedProduct\Model\Product\Type\Grouped;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Swatches\Helper\Data as SwatchHelper;
use Psr\Log\LoggerInterface;

class ScriptInit extends Orig
{
    const INNER_SEARCH = 'capgemini_bloomreach_api/correct/search';
    const INNER_COLLECTION = 'capgemini_bloomreach_api/correct/category';
    const LOGGED_IN_TEMPLATE_FIELD_ID = 'productlist_template_text_logged_in';
    const PRICE_CONTAINER_OPENING_TAG_FIELD_ID = 'price_content_tag';
    const USE_MAGENTO_AS_PROXY = ConfigurationSettingsInterface::SETTINGS_APIURL_PATH . '/' . 'use_magento_as_proxy';
    const SETTINGS_PIXEL_DOMAIN_KEY  = ConfigurationSettingsInterface::SETTINGS_GENERAL_PATH . '/pixel_domain_key';
    const SITESEARCH_TEMPLATE_PRODUCTLIST_LOGGED_IN = ConfigurationSettingsInterface::SITESEARCH_PATH . '/' . self::LOGGED_IN_TEMPLATE_FIELD_ID;
    const COLLECTIONS_TEMPLATE_PRODUCTLIST_LOGGED_IN = ConfigurationSettingsInterface::COLLECTIONS_PATH . '/' . self::LOGGED_IN_TEMPLATE_FIELD_ID;
    const PRICE_CONTAINER_OPENING_TAG_SITESEARCH = ConfigurationSettingsInterface::SITESEARCH_PATH . '/' . self::PRICE_CONTAINER_OPENING_TAG_FIELD_ID;
    const PRICE_CONTAINER_OPENING_TAG_COLLECTIONS = ConfigurationSettingsInterface::COLLECTIONS_PATH . '/' . self::PRICE_CONTAINER_OPENING_TAG_FIELD_ID;
    const PROXY_PAGE_MAPPING = [
        'capgemini_bloomreach_category_proxy_category_view' => 'category',
        'capgemini_bloomreach_search_proxy_result_index'    => 'search'
    ];

    /**
     * @var string
     */
    protected $_pixelDomainKey;
    /**
     * @var HttpContext
     */
    private HttpContext $httpContext;
    /**
     * @var Http
     */
    private Http $request;
    /**
     * @var ModuleHelper
     */
    private ModuleHelper $moduleHelper;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Http $request,
        CheckoutSession $checkoutSession,
        Json $jsonSerializer,
        LoggerInterface $logger,
        Data $catalogHelper,
        View $categoryView,
        Configurable $configurable,
        Grouped $grouped,
        SwatchHelper $swatchHelper,
        Resolver $layerResolver,
        Files $files,
        CookieManagerInterface $cookieManager,
        Visitor $visitor,
        CustomerSession $customerSession,
        SessionFactory $sessionFactory,
        StoreManagerInterface $storeManager,
        PriceCurrencyInterface $priceCurrency,
        Currency $currency,
        HttpContext $httpContext,
        ModuleHelper $moduleHelper
    ) {
        parent::__construct(
            $scopeConfig,
            $request,
            $checkoutSession,
            $jsonSerializer,
            $logger,
            $catalogHelper,
            $categoryView,
            $configurable,
            $grouped,
            $swatchHelper,
            $layerResolver,
            $files,
            $cookieManager,
            $visitor,
            $customerSession,
            $sessionFactory,
            $storeManager,
            $priceCurrency,
            $currency
        );
        $this->httpContext = $httpContext;
        $this->moduleHelper = $moduleHelper;
        $this->request = $request;
    }

    public function initAppSetting()
    {
        parent::initAppSetting();
        if (!$this->_pixelDomainKey) {
            $this->_pixelDomainKey = $this->getStoreConfigValue(self::SETTINGS_PIXEL_DOMAIN_KEY);
        }
    }

    /**
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->httpContext->getValue(Context::CONTEXT_AUTH);
    }

    public function isUseMagentoAsProxy()
    {
        return $this->getStoreConfigValue(self::USE_MAGENTO_AS_PROXY);
    }

    /**
     * @return string
     */
    public function getInnerSearchRoute(): string
    {
        return self::INNER_SEARCH;
    }

    /**
     * @return string
     */
    public function getInnerCollectionRoute(): string
    {
        return self::INNER_COLLECTION;
    }

    /**
     * @param string $type
     * @return string
     */
    public function getProductListTemplate(string $type): string
    {
        switch ($type) {
            case 'search':
                if (!$this->isLoggedIn()) {

                    return $this->getStoreConfigValue(self::SITESEARCH_TEMPLATE_PRODUCTLIST);
                }

                $productListTemplate = (string) $this->getStoreConfigValue(self::SITESEARCH_TEMPLATE_PRODUCTLIST_LOGGED_IN);

                break;
            case 'category':
                if (!$this->isLoggedIn()) {

                    return $this->getStoreConfigValue(self::COLLECTIONS_TEMPLATE_PRODUCTLIST);
                }

                $productListTemplate = (string) $this->getStoreConfigValue(self::COLLECTIONS_TEMPLATE_PRODUCTLIST_LOGGED_IN);

                break;
            default:
                return '';
        }

        if (!$productListTemplate) {
            switch ($type) {
                case 'search':
                    $productListTemplate = (string) $this->getStoreConfigValue(self::SITESEARCH_TEMPLATE_PRODUCTLIST);
                    $priceContainerOpeningTag = (string) $this->getStoreConfigValue(self::PRICE_CONTAINER_OPENING_TAG_SITESEARCH);

                    break;
                case 'category':
                    $productListTemplate = (string) $this->getStoreConfigValue(self::COLLECTIONS_TEMPLATE_PRODUCTLIST);
                    $priceContainerOpeningTag = (string) $this->getStoreConfigValue(self::PRICE_CONTAINER_OPENING_TAG_COLLECTIONS);

                    break;
                default:
                    return '';
            }
            $classAttribute = strstr($priceContainerOpeningTag, 'class="');
            $closingQuotePos = strpos($classAttribute, '"', 7);
            $classAttribute = substr($classAttribute, 0, $closingQuotePos + 1);
            $productListTemplate = $this->moduleHelper->prepareLoggedInTemplate(
                $productListTemplate,
                $priceContainerOpeningTag,
                $classAttribute
            );
        }

        $specialPriceLabel = $this->httpContext->getValue(ContextPlugin::IS_TRADE_CUSTOMER_CONTEXT) ? 'Trade' : 'Sale';

        return str_replace(
            ConfigSave::SPECIAL_PRICE_LABEL_PLACEHOLDER,
            $specialPriceLabel,
            $productListTemplate
        );
    }

    public function getCurrentPageName()
    {
        $parent = parent::getCurrentPageName();

        if ($parent !== 'other') {

            return $parent;
        }

        return self::PROXY_PAGE_MAPPING[$this->request->getFullActionName()] ?? $parent;
    }

    public function getPixelDomainKey()
    {
        return $this->_pixelDomainKey;
    }
}
