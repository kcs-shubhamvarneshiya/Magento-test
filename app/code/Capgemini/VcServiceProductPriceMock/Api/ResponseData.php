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

class ResponseData extends DataObject implements ResponseDataInterface
{
    /**
     * @var bool
     */
    protected bool $success;

    /**
     * @var string
     */
    protected string $message;

    /**
     * @var array
     */
    protected array $errors = [];

    /**
     * @var array
     */
    protected array $priceData = [];

    /**
     * @return bool
     */
    public function getSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     * @return void
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return void
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     * @return void
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @return \Capgemini\VcServiceProductPriceMock\Api\PriceDataInterface[]
     */
    public function getPriceData()
    {
        return $this->priceData;
    }

    /**
     * @param \Capgemini\VcServiceProductPriceMock\Api\PriceDataInterface $priceData
     * @return void
     */
    public function addPriceData(\Capgemini\VcServiceProductPriceMock\Api\PriceDataInterface $priceData): void
    {
        $this->priceData[] = $priceData;
    }
}
