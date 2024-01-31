<?php
/**
 * Capgemini_DropShipFee
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\DropShipFee\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    public const SPLIT_BY_ATTRIBUTE = 'division';
    public const XML_CONFIG_DROP_SHIP_FEE_ENABLE  = 'shipping/drop_ship_fee/enable';
    public const XML_CONFIG_DROP_SHIP_FEE_AMOUNT  = 'shipping/drop_ship_fee/amount';

    /**
     * @param int $storeId
     * @return string|null
     */
    public function isEnable(int $storeId): ?string
    {
        return $this->scopeConfig->getValue(
            self::XML_CONFIG_DROP_SHIP_FEE_ENABLE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string|null
     */
    public function getAmount(int $storeId): ?string
    {
        return $this->scopeConfig->getValue(
            self::XML_CONFIG_DROP_SHIP_FEE_AMOUNT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
