<?php
/**
 * Capgemini_OrderSplit
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\OrderSplit\Api\Checkout\Validator;

use Magento\Framework\Phrase;

interface ResultInterface
{
    public function setIsValid(bool $isValid);

    public function getIsValid(): bool;

    public function addErrorMessage(Phrase $message);

    public function getErrorMessages(): array;
}
