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

use Capgemini\OrderSplit\Api\Checkout\Validator\ValidatorInterface;
use Magento\Framework\Phrase;

class BaseValidation implements ValidatorInterface
{
    /**
     * @param array $fields
     * @return bool
     */
    public function validate(array $fields): bool
    {
        list($poNumberValue, $businessBackendValue) = $fields;

        if ($poNumberValue && substr($poNumberValue, -1) == '0') {
            return false;
        }

        return true;
    }

    /**
     * @return Phrase
     */
    public function getErrorMessage(): Phrase
    {
        return __('0 is in the end of string');
    }
}
