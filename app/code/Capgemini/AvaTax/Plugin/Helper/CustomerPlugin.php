<?php

namespace Capgemini\AvaTax\Plugin\Helper;

use Capgemini\AvaTax\Helper\TechnicalModel;
use ClassyLlama\AvaTax\Helper\Customer as CustomerHelper;
use Magento\Customer\Api\Data\CustomerInterface;
use Psr\Log\InvalidArgumentException;

class CustomerPlugin
{
    public function beforeGetCustomerAttributeValue(
        CustomerHelper $subject,
        CustomerInterface $customer,
        string $customerCode
    ) {
        if (!array_key_exists($customerCode,TechnicalModel::EXTENSION_ATTRIBUTES_ALLOWED_FOR_CUSTOMER_CODE_FORMAT)) {

            return [$customer, $customerCode];
        }

        if ($customer->getCustomAttribute($customerCode)) {
            throw new InvalidArgumentException(sprintf(
                'Capgemini_AvaTax: Ambiguity detected: %s exists both as custom as well as extension attribute of Customer Entity',
                $customerCode)
            );
        }

        $technicalModel = new TechnicalModel($customer);

        return [$technicalModel, $customerCode];
    }
}
