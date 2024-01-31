<?php

namespace Capgemini\AvaTax\Plugin\Model\Config\Source;

use Capgemini\AvaTax\Helper\TechnicalModel;
use ClassyLlama\AvaTax\Model\Config\Source\CustomerCode;

class CustomerCodePlugin
{
    public function afterToOptionArray(CustomerCode $subject, array $result)
    {
        foreach (TechnicalModel::EXTENSION_ATTRIBUTES_ALLOWED_FOR_CUSTOMER_CODE_FORMAT as $value => $label) {
            $result[$value] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return $result;
    }
}
