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

interface ValidatorInterface
{
    /**
     * @param array $fields
     * @return bool
     */
    public function validate(array $fields): bool;

    /**
     * @return Phrase
     */
    public function getErrorMessage(): Phrase;
}
