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

use Magento\Framework\DataObject;

class PriceData extends DataObject implements PriceDataInterface
{
    /**
     * @var string
     */
    protected string $sku;

    /**
     * @var float
     */
    protected float $price;

    /**
     * @var string
     */
    protected string $unitOfMeasure;

    /**
     * @param string $sku
     * @return void
     */
    public function setSku(string $sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @param float $price
     * @return void
     */
    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getUnitOfMeasure(): string
    {
        return $this->unitOfMeasure;
    }

    /**
     * @param string $unitOfMeasure
     * @return void
     */
    public function setUnitOfMeasure(string $unitOfMeasure)
    {
        $this->unitOfMeasure = $unitOfMeasure;
    }
}
