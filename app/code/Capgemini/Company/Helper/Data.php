<?php
namespace Capgemini\Company\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const ENABLE_TAX_EXEMPT_NOTIFICATION = 'company/general/enable_tax_exempt_notification';
    const ENABLE_MEMBER_STATE = 'company/general/enable_member_state';
    const ENABLE_VAT_NUMBER = 'company/general/enable_vat_number';
    const MEMBER_STATE_COUNTRIES = 'company/general/member_state_countries';


    /**
     * @return bool
     */
    public function isTaxExemptNotificationEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::ENABLE_TAX_EXEMPT_NOTIFICATION,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return bool
     */
    public function isMemberStateEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::ENABLE_MEMBER_STATE,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return bool
     */
    public function isVatNumberEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::ENABLE_VAT_NUMBER,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return false|string[]
     */
    public function getMemberStateCountries()
    {
        return explode(
            ',',
            (string) $this->scopeConfig->getValue(
                self::MEMBER_STATE_COUNTRIES,
                ScopeInterface::SCOPE_WEBSITE
            )
        );
    }
}
