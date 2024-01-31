<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Store\Model\ScopeInterface;


class Data extends AbstractHelper
{
    public const CONFIG_PATH_GENERAL_ACTIVE = 'rto/general/active';
    public const CONFIG_PATH_GENERAL_REP_EMAIL = 'rto/general/representative_email';
    public const CONFIG_PATH_GENERAL_EMAIL_FROM = 'rto/general/send_from_email';
    public const CONFIG_PATH_GENERAL_EMAIL_CUSTOMER_COPY = 'rto/general/email_customer_copy';
    public const CONFIG_PATH_GENERAL_PDP_MODAL = 'rto/general/pdp_modal_copy';
    public const EMAIL_TEMPLATE_ID = 'order_request_submit';
    public const STORE_PHONE_NUMBER = 'general/store_information/phone';

    /**
     * @param int $website
     * @return string|null
     */
    public function isActive(int $website): ?string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_PATH_GENERAL_ACTIVE,
            ScopeInterface::SCOPE_WEBSITE,
            $website
        );
    }

    /**
     * @param int $website
     * @return string|null
     */
    public function isEmailCustomerCopy(int $website): ?string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_PATH_GENERAL_EMAIL_CUSTOMER_COPY,
            ScopeInterface::SCOPE_WEBSITE,
            $website
        );
    }

    /**
     * @param int $website
     * @return array|null
     */
    public function getRepresentativeEmail(int $website): ?array
    {
        $emails = $this->scopeConfig->getValue(
            self::CONFIG_PATH_GENERAL_REP_EMAIL,
            ScopeInterface::SCOPE_WEBSITE,
            $website
        );

        $emails = explode(',', $emails);

        return array_map('trim', $emails);
    }

    /**
     * @param int $website
     * @return string|null
     */
    public function getPdpModalText(int $website): ?string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_PATH_GENERAL_PDP_MODAL,
            ScopeInterface::SCOPE_WEBSITE,
            $website
        );
    }

    /**
     * @return string
     */
    public function getSubmitEmailTemplate(): string
    {
        return self::EMAIL_TEMPLATE_ID;
    }

    /**
     * Get store the phone
     * @return string
     */
    public function getCustomerServiceNumber(int $website):string
    {
        return $this->scopeConfig->getValue(
            self::STORE_PHONE_NUMBER,
            ScopeInterface::SCOPE_WEBSITE,
            $website
        );
    }

    /**
     * Get store the phone
     * @return string
     */
    public function getEmailFrom(int $website):array
    {
        $sender = [
            'email' => $this->scopeConfig->getValue(self::CONFIG_PATH_GENERAL_EMAIL_FROM, ScopeInterface::SCOPE_WEBSITE, $website),
            'name' => 'Sales'
        ];
        return $sender;
    }

    /**
     * Get json params for move from cart action
     *
     * @param AbstractItem $item
     */
    public function getCartMovePostJson(AbstractItem $item)
    {
        $url = $this->_getUrl('orequest/cart/move');
        $data = ['id' => $item->getId()];
        return json_encode(['action' => $url, 'data' => $data]);
    }
}
