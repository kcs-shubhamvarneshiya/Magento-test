<?php
/**
 * Capgemini_OrderSplit
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\OrderSplit\Model\Checkout\Validator;

use Capgemini\OrderSplit\Api\Checkout\Validator\ResultInterface;
use Magento\Framework\Phrase;

class Result implements ResultInterface
{
    /**
     * @var bool
     */
    private $isValid;

    /**
     * @var array
     */
    private $errorMessages;

    /**
     * @param bool $isValid
     * @return void
     */
    public function setIsValid(bool $isValid)
    {
        $this->isValid = $isValid;
    }

    /**
     * @return bool
     */
    public function getIsValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @param Phrase $message
     * @return void
     */
    public function addErrorMessage(Phrase $message)
    {
        $this->errorMessages[] = $message;
    }

    /**
     * @return array
     */
    public function getErrorMessages(): array
    {
        return $this->errorMessages ?? [];
    }
}
