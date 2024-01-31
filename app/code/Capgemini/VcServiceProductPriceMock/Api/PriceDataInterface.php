<?php
/**
 * Capgemini_VcServiceProductPriceMock
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_VcServiceProductPriceMock
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */

declare(strict_types=1);

namespace Capgemini\VcServiceProductPriceMock\Api;

interface PriceDataInterface
{
    /**
     * @param string $sku
     * @return void
     */
    public function setSku(string $sku);

    /**
     * @return string
     */
    public function getSku(): string;

    /**
     * @param float $price
     * @return void
     */
    public function setPrice(float $price);

    /**
     * @return float
     */
    public function getPrice(): float;

    /**
     * @return string
     */
    public function getUnitOfMeasure(): string;

    /**
     * @param string $unitOfMeasure
     * @return void
     */
    public function setUnitOfMeasure(string $unitOfMeasure);
}
