<?php
/**
 * Capgemini_OrderSplit
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

namespace Capgemini\OrderSplit\Api\Data;

/**
 * Custom PO Number interface
 */
interface CustomPoNumberInterface
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
     * PO Number getter
     *
     * @return string|null
     */
    public function getPoNumber(): ?string;

    /**
     * PO Number setter
     *
     * @param string $poNumber
     * @return $this
     */
    public function setPoNumber(string $poNumber);
}