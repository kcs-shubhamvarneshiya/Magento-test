<?php
/**
 * Capgemini_OrderSplit
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

namespace Capgemini\OrderSplit\Api\Data;

/**
 * Custom Promo Code interface
 */
interface CustomPromoCodeInterface
{
    /**
     * Division getter
     *
     * @return string|null
     */
    public function getDivision(): ?string;

    /**
     * Division setter
     *
     * @param string $division
     * @return $this
     */
    public function setDivision(string $division);

    /**
     * Promo Code getter
     *
     * @return string|null
     */
    public function getPromoCode(): ?string;

    /**
     * Promo Code setter
     *
     * @param string $promoCode
     * @return $this
     */
    public function setPromoCode(string $promoCode);
}