<?php
/**
 * Capgemini_ShipOnDate
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\ShipOnDate\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    public const DELIVERY_DATE_METHOD_CODE  = 'ship_on_date';
    public const XML_CONFIG_DELIVERY_DATE_FIRST_AVAIL_DATE = 'carriers/ship_on_date/first_available_date';
    public const XML_CONFIG_DELIVERY_DATE_LAST_AVAIL_DATE  = 'carriers/ship_on_date/last_available_date';
    public const XML_CONFIG_DELIVERY_DATE_AlLOWED_DAYS  = 'carriers/ship_on_date/allowed_days';

    /**
     * @return string|null
     */
    public function getShippingMethodCode(): ?string
    {
        return self::DELIVERY_DATE_METHOD_CODE;
    }

    /**
     * @param int $storeId
     * @return string|null
     */
    public function getFirstAvailableDate(int $storeId): ?string
    {
        return $this->scopeConfig->getValue(
            self::XML_CONFIG_DELIVERY_DATE_FIRST_AVAIL_DATE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string|null
     */
    public function getLastAvailableDate(int $storeId): ?string
    {
        return $this->scopeConfig->getValue(
            self::XML_CONFIG_DELIVERY_DATE_LAST_AVAIL_DATE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string|null
     */
    public function getAllowedDays(int $storeId): ?string
    {
        return $this->scopeConfig->getValue(
            self::XML_CONFIG_DELIVERY_DATE_AlLOWED_DAYS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
