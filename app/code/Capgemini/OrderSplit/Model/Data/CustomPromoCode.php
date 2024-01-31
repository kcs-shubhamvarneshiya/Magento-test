<?php
/**
 * Capgemini_OrderSplit
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

namespace Capgemini\OrderSplit\Model\Data;

use Capgemini\OrderSplit\Api\Data\CustomPromoCodeInterface;

/**
 * Custom Promo Code data model
 */
class CustomPromoCode extends \Magento\Framework\DataObject
    implements \Capgemini\OrderSplit\Api\Data\CustomPromoCodeInterface, \JsonSerializable
{
    /**
     * @inheritDoc
     */
    public function getDivision(): ?string
    {
        return $this->getData('division');
    }

    /**
     * @inheritDoc
     */
    public function setDivision(string $division)
    {
        return $this->setData('division', $division);
    }

    /**
     * @inheritDoc
     */
    public function getPromoCode(): ?string
    {
        return $this->getData('promo_code');
    }

    /**
     * @inheritDoc
     */
    public function setPromoCode(string $promoCode)
    {
        return $this->setData('promo_code', $promoCode);
    }

    public function jsonSerialize(): mixed
    {
        return $this->getData();
    }
}