<?php

namespace Capgemini\BloomreachThematic\Helper;

use Bloomreach\Connector\Block\ConfigurationSettingsInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Request\Http;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Stdlib\CookieManagerInterface;

class Data extends AbstractHelper
{
    const TRACKING_COOKIE_NAME = '_br_uid_2';
    const XML_CONFIG_PATH_SETTINGS_ENDPOINT_THEMATIC = ConfigurationSettingsInterface::SETTINGS_APIURL_PATH . '/thematic_endpoint';
    const XML_CONFIG_PATH_THEMATIC_URL_PREFIX = 'bloomreach_settings/seo_widgets/thematic_url_prefix';
    const XML_CONFIG_PATH_THEMATIC_REQUEST_ID = 'bloomreach_settings/seo_widgets/thematic_request_id';
    const XML_CONFIG_PATH_THEMATIC_ROWS = 'catalog/frontend/grid_per_page';
    const XML_CONFIG_PATH_THEMATIC_PRODUCT_ATTRIBUTES = 'bloomreach_settings/seo_widgets/thematic_product_attributes';
    const STAGING_API_ENDPOINT_THEMATIC = 'https://staging-core.dxpapi.com/api/v1/core/';
    const PRODUCTION_API_ENDPOINT_THEMATIC = 'https://core.dxpapi.com/api/v1/core/';

    /**
     * @var Http
     */
    protected $_request;
    /**
     * @var CookieManagerInterface
     */
    private $cookieManager;
    private static $requestType;
    private static $searchType;
    private static $pageKey;
    private static $userId;

    public function __construct(Context $context, CookieManagerInterface $cookieManager)
    {
        parent::__construct($context);
        $this->cookieManager = $cookieManager;
    }

    public function setRequestType(string $requestType)
    {
        self::$requestType = $requestType;
    }

    public function setSearchType(string $searchType)
    {
        self::$searchType = $searchType;
    }

    public function setPageKey($pageKey)
    {
        self::$pageKey = $pageKey;
    }

    public function setUserId($userId)
    {
        self::$userId = $userId;
    }

    public function getRequestType()
    {
        return self::$requestType;
    }

    public function getSearchType()
    {
        return self::$searchType;
    }

    public function getPageKey()
    {
        return self::$pageKey;
    }

    public function getUserId()
    {
        return self::$userId;
    }

    public function getIsThematicRequest()
    {
        return isset(self::$requestType, self::$searchType);
    }

    /**
     * @return mixed
     */
    public function getThematicUrlPrefix(): mixed
    {
        return $this->getStoreScopeConfigValue(self::XML_CONFIG_PATH_THEMATIC_URL_PREFIX);
    }

    public function getPagePath()
    {
        return '/' . $this->getThematicUrlPrefix() . '/' . $this->getPageKey();
    }

    /**
     * @return string
     */
    public function getEndpointUrl(): string
    {
        if ('prod' === $this->getStoreScopeConfigValue(self::XML_CONFIG_PATH_SETTINGS_ENDPOINT_THEMATIC)) {

            return self::PRODUCTION_API_ENDPOINT_THEMATIC;
        }

        return self::STAGING_API_ENDPOINT_THEMATIC;
    }

    public function getRequestParams(): array
    {
        return $this->_request->getParams();
    }

    public function getAccountId()
    {
        return $this->getStoreScopeConfigValue(ConfigurationSettingsInterface::SETTINGS_ACC_ID);
    }

    public function getAuthKey()
    {
        return $this->getStoreScopeConfigValue(ConfigurationSettingsInterface::SETTINGS_AUTH_KEY);
    }

    public function getDomainKey()
    {
        return $this->getStoreScopeConfigValue(ConfigurationSettingsInterface::SETTINGS_DOMAIN_KEY);
    }

    public function getRequestId()
    {
        return $this->getStoreScopeConfigValue(self::XML_CONFIG_PATH_THEMATIC_REQUEST_ID);
    }

    public function getUrl()
    {
        return $this->_request->getUriString();
    }

    public function getRefUrl()
    {
        return $this->_httpHeader->getHttpReferer() ?: $this->getUrl();
    }

    public function getTrackingCookie()
    {
        return $this->cookieManager->getCookie(self::TRACKING_COOKIE_NAME);
    }

    public function getRows()
    {
        return $this->getStoreScopeConfigValue(self::XML_CONFIG_PATH_THEMATIC_ROWS);
    }

    public function getProductAttributes()
    {
        return $this->getStoreScopeConfigValue(self::XML_CONFIG_PATH_THEMATIC_PRODUCT_ATTRIBUTES);
    }

    public function getUserIp()
    {
        return $this->_remoteAddress->getRemoteAddress();
    }

    public function getUserAgent()
    {
        return $this->_httpHeader->getHttpUserAgent();
    }

    public function logError(string $message)
    {
        $this->_logger->error($message, ['module' => 'Capgemini_BloomreachThematic']);
    }

    public function logWarning(string $message)
    {
        $this->_logger->warning($message, ['module' => 'Capgemini_BloomreachThematic']);
    }

    public function getThematicPagePath()
    {
        return $this->_request->getOriginalPathInfo();
    }

    /**
     * @param string $path
     * @return mixed
     */
    protected function getStoreScopeConfigValue(string $path): mixed
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE
        );
    }
}
