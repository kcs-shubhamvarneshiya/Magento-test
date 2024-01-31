<?php

namespace Lyonscg\AddressRestrictions\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const XML_PATH_ADDRESS_DELETE_LOG_ENABLED = 'lyonscg_addressrestrictions/address_delete_log/enabled';

    const XML_PATH_ADDRESS_DELETE_LOG_CUSTOMER_GROUPS = 'lyonscg_addressrestrictions/address_delete_log/customer_groups';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function isEnabled()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ADDRESS_DELETE_LOG_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getCustomerGroups()
    {
        return explode(',', (string)$this->scopeConfig->getValue(
            self::XML_PATH_ADDRESS_DELETE_LOG_CUSTOMER_GROUPS,
            ScopeInterface::SCOPE_STORE
        ));
    }
}
