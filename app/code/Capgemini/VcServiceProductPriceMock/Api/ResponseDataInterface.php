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

interface ResponseDataInterface
{
    /**
     * @return bool
     */
    public function getSuccess(): bool;

    /**
     * @param bool $success
     * @return void
     */
    public function setSuccess(bool $success): void;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @param string $message
     * @return void
     */
    public function setMessage(string $message): void;

    /**
     * @return array
     */
    public function getErrors(): array;
    /**
     * @param array $errors
     * @return void
     */
    public function setErrors(array $errors): void;

    /**
     * @return \Capgemini\VcServiceProductPriceMock\Api\PriceDataInterface[]
     */
    public function getPriceData();

    /**
     * @param \Capgemini\VcServiceProductPriceMock\Api\PriceDataInterface $priceData
     * @return void
     */
    public function addPriceData(\Capgemini\VcServiceProductPriceMock\Api\PriceDataInterface $priceData): void;
}
