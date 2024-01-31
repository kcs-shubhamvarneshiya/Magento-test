<?php

namespace Capgemini\Payfabric\Plugin;

use Capgemini\Payfabric\Helper\IgnoreValidatedRecaptcha;
use Magento\Framework\Validation\ValidationResult;
use Magento\ReCaptchaValidation\Model\Validator;
use Magento\ReCaptchaValidationApi\Api\Data\ValidationConfigInterface;

class RegisterRequest
{
    private const DUPLICATION_ERROR_CODE = 'timeout-or-duplicate';

    /**
     * @var array
     */
    private array $successResults;

    public function afterIsValid(
        Validator $subject,
        ValidationResult $result,
        string $reCaptchaResponse,
        ValidationConfigInterface $validationConfig
    ) {
        $errors = $result->getErrors();

        if (empty($errors)) {
            $this->successResults[$reCaptchaResponse] = $result;

            return $this->successResults[$reCaptchaResponse];
        }

        if (true === IgnoreValidatedRecaptcha::getShouldIgnore()) {
            if (isset($this->successResults[$reCaptchaResponse])) {
                if (count($errors) === 1) {
                    if (key($errors) === self::DUPLICATION_ERROR_CODE) {

                        return $this->successResults[$reCaptchaResponse];
                    }
                }
            }
        }

        return $result;
    }
}
