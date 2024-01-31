<?php

namespace Lyonscg\SalesPad\Model;

use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const XML_PATH_STAGING_ENABLED              = 'lyonscg_salespad/general/staging_enabled';
    const XML_PATH_STAGE_API_URL                = 'lyonscg_salespad/general/stage_api_url';
    const XML_PATH_STAGE_API_USERNAME           = 'lyonscg_salespad/general/stage_api_username';
    const XML_PATH_STAGE_API_PASSWORD           = 'lyonscg_salespad/general/stage_api_password';
    const XML_PATH_PROD_API_URL                 = 'lyonscg_salespad/general/prod_api_url';
    const XML_PATH_PROD_API_USERNAME            = 'lyonscg_salespad/general/prod_api_username';
    const XML_PATH_PROD_API_PASSWORD            = 'lyonscg_salespad/general/prod_api_password';
    const XML_PATH_SALES_PERSON_ID              = 'lyonscg_salespad/general/sales_person_id';
    const XML_PATH_WAREHOUSE_CODE               = 'lyonscg_salespad/general/warehouse_code';
    const XML_PATH_CURRENCY_ID                  = 'lyonscg_salespad/general/currency_id';
    const XML_PATH_CURRENCY_DEC                 = 'lyonscg_salespad/general/currency_dec';
    const XML_PATH_ACTIVE_USER_LIMIT            = 'lyonscg_salespad/general/active_user_limit';
    const XML_PATH_LOGGING_ENABLED              = 'lyonscg_salespad/general/logging_enabled';
    const XML_PATH_USE_ENTITY_ID_IN_AGGREGATION = 'lyonscg_salespad/general/use_entity_id_in_aggregation';
    const XML_PATH_ERROR_LOG_LIFETIME           = 'lyonscg_salespad/general/errorlog_lifetime';
    const XML_PATH_CUSTOMER_SYNC_ENABLED        = 'lyonscg_salespad/sync/customer_sync_enabled';
    const XML_PATH_CUSTOMER_SYNC_UPDATE_ENABLED = 'lyonscg_salespad/sync/customer_sync_update_enabled';
    const XML_PATH_CUSTOMER_SYNC_EMAIL          = 'lyonscg_salespad/sync/cusotmer_sync_error_email';
    const XML_PATH_CUSTOMER_SYNC_EMAIL_FROM     = 'lyonscg_salespad/sync/customer_sync_error_email_from';
    const XML_PATH_CUSTOMER_NAME_CLASS          = 'lyonscg_salespad/sync/customer_sync_name_class';
    const XML_PATH_CUSTOMER_SYNC_LIMIT          = 'lyonscg_salespad/sync/customer_sync_limit';
    const XML_PATH_ORDER_SYNC_ENABLED           = 'lyonscg_salespad/sync/order_sync_enabled';
    const XML_PATH_ORDER_SYNC_EMAIL             = 'lyonscg_salespad/sync/order_sync_error_email';
    const XML_PATH_ORDER_SYNC_EMAIL_FROM        = 'lyonscg_salespad/sync/order_sync_error_email_from';
    const XML_PATH_ORDER_SYNC_LIMIT             = 'lyonscg_salespad/sync/order_sync_limit';
    const XML_PATH_QUOTE_SYNC_ENABLED           = 'lyonscg_salespad/sync/quote_sync_enabled';
    const XML_PATH_QUOTE_SYNC_EMAIL             = 'lyonscg_salespad/sync/quote_sync_error_email';
    const XML_PATH_QUOTE_SYNC_EMAIL_FROM        = 'lyonscg_salespad/sync/quote_sync_error_email_from';
    const XML_PATH_QUOTE_SYNC_LIMIT             = 'lyonscg_salespad/sync/quote_sync_limit';
    const XML_PATH_SESSION_ID                   = 'lyonscg_salespad/sync/session_id';
    const XML_PATH_MAP_SHIPPINGS                = 'lyonscg_salespad/map/shipping_method';
    const XML_PATH_CARRIER_TRACKING_URL         = 'lyonscg_salespad/tracking/carrier_tracking_url';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ConfigInterface
     */
    private $configWriter;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ConfigInterface $configWriter
     * @param SerializerInterface $serializer
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ConfigInterface  $configWriter,
        SerializerInterface $serializer
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
        $this->serializer = $serializer;
    }

    /**
     * @return bool
     */
    public function isStaging()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_STAGING_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getApiUrl()
    {
        return $this->isStaging() ? $this->getStageApiUrl() : $this->getProdApiUrl();
    }

    /**
     * @return mixed
     */
    public function getApiUsername()
    {
        return $this->isStaging() ? $this->getStageApiUsername() : $this->getProdApiUsername();
    }

    /**
     * @return mixed
     */
    public function getApiPassword()
    {
        return $this->isStaging() ? $this->getStageApiPassword() : $this->getProdApiPassword();
    }

    /**
     * @return mixed
     */
    public function getStageApiUrl()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_STAGE_API_URL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getStageApiUsername()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_STAGE_API_USERNAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getStageApiPassword()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_STAGE_API_PASSWORD,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getProdApiUrl()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PROD_API_URL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getProdApiUsername()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PROD_API_USERNAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getProdApiPassword()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PROD_API_PASSWORD,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getSalesPersonId()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SALES_PERSON_ID,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getWarehouseCode()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_WAREHOUSE_CODE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getCurrencyId()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CURRENCY_ID,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getCurrencyDec()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CURRENCY_DEC,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getActiveUserLimit()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ACTIVE_USER_LIMIT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function isLoggingEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_LOGGING_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function isUseEntityIdInLogsAggregation()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_USE_ENTITY_ID_IN_AGGREGATION,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getErrorLogLifetime()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ERROR_LOG_LIFETIME,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getCustomerSyncEnabled()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CUSTOMER_SYNC_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getCustomerSyncUpdateEnabled()
    {
        return false;
//        return $this->scopeConfig->getValue(
//            self::XML_PATH_CUSTOMER_SYNC_UPDATE_ENABLED,
//            ScopeInterface::SCOPE_STORE
//        );
    }

    /**
     * @return array|string[]
     */
    public function getCustomerErrorEmails()
    {
        $emailString = $this->scopeConfig->getValue(
            self::XML_PATH_CUSTOMER_SYNC_EMAIL,
            ScopeInterface::SCOPE_STORE
        );
        $emails = explode(',', $emailString);
        return array_map(function ($email) {
            return trim($email);
        }, $emails);
    }

    public function getCustomerErrorEmailFrom()
    {
        $emailString = $this->scopeConfig->getValue(
            self::XML_PATH_CUSTOMER_SYNC_EMAIL_FROM,
            ScopeInterface::SCOPE_STORE
        );
        return trim($emailString);
    }

    public function getCustomerAllowNameClassChange()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CUSTOMER_NAME_CLASS,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getCustomerSyncLimit()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CUSTOMER_SYNC_LIMIT,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getOrderSyncEnabled()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ORDER_SYNC_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return array|string[]
     */
    public function getOrderErrorEmails()
    {
        $emailString = $this->scopeConfig->getValue(
            self::XML_PATH_ORDER_SYNC_EMAIL,
            ScopeInterface::SCOPE_STORE
        );
        $emails = explode(',', $emailString);
        return array_map(function ($email) {
            return trim($email);
        }, $emails);
    }

    public function getOrderErrorEmailFrom()
    {
        $emailString = $this->scopeConfig->getValue(
            self::XML_PATH_ORDER_SYNC_EMAIL_FROM,
            ScopeInterface::SCOPE_STORE
        );
        return trim($emailString);
    }

    public function getOrderSyncLimit()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ORDER_SYNC_LIMIT,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getQuoteSyncEnabled()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_QUOTE_SYNC_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return array|string[]
     */
    public function getQuoteErrorEmails()
    {
        $emailString = $this->scopeConfig->getValue(
            self::XML_PATH_QUOTE_SYNC_EMAIL,
            ScopeInterface::SCOPE_STORE
        );
        $emails = explode(',', $emailString);
        return array_map(function ($email) {
            return trim($email);
        }, $emails);
    }

    public function getQuoteErrorEmailFrom()
    {
        $emailString = $this->scopeConfig->getValue(
            self::XML_PATH_QUOTE_SYNC_EMAIL_FROM,
            ScopeInterface::SCOPE_STORE
        );
        return trim($emailString);
    }

    public function getQuoteSyncLimit()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_QUOTE_SYNC_LIMIT,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getSessionId()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SESSION_ID,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function setSessionId($sessionId)
    {
        $this->configWriter
            ->saveConfig(self::XML_PATH_SESSION_ID, $sessionId, 'default', 0);
    }

    /**
     * @return array|bool|float|int|string|null
     */
    public function getShippingMap()
    {
        $shippingMap = $this->scopeConfig->getValue(self::XML_PATH_MAP_SHIPPINGS);

        if ($shippingMap === null) {
            return [];
        }

        return $this->serializer->unserialize($shippingMap);
    }

    /**
     * @return array|bool|float|int|string|null
     */
    public function getCarrierTrackingUrl()
    {
        $carrierTrackingUrl = $this->scopeConfig->getValue(self::XML_PATH_CARRIER_TRACKING_URL);

        if ($carrierTrackingUrl === null) {
            return [];
        }

        return $this->serializer->unserialize($carrierTrackingUrl);
    }
}
