<?php
/**
 * Capgemini_OrderSplit
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\OrderSplit\Model\Checkout;

use Capgemini\OrderSplit\Api\Checkout\Validator\ValidatorInterface;
use Capgemini\OrderSplit\Model\Checkout\Validator\Result;

class PoNumberValidationProcessor
{
    /**
     * @var ValidatorInterface[]
     */
    protected $validators;

    /**
     * @var Result
     */
    protected $validationResult;

    /**
     * @param array $validators
     * @param Result $validationResult
     */
    public function __construct(
        array $validators,
        Result $validationResult
    ) {
        $this->validators = $validators;
        $this->validationResult = $validationResult;
    }

    /**
     * @param array $fields
     * @return Result
     */
    public function validate(array $fields)
    {
        $isValid = true;
        if (!empty($this->validators)) {
            foreach ($this->validators as $validator) {
                $isValid = $validator->validate($fields);
                if (!$isValid) {
                    $this->validationResult->addErrorMessage($validator->getErrorMessage());
                }
            }
        }
        $this->validationResult->setIsValid($isValid);
        return $this->validationResult;
    }
}
