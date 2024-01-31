<?php

namespace Capgemini\AvaTax\Helper;

use Lyonscg\SalesPad\Api\Data\CustomerLinkInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Psr\Log\InvalidArgumentException;

class TechnicalModel
{
    const EXTENSION_ATTRIBUTES_ALLOWED_FOR_CUSTOMER_CODE_FORMAT = [
        CustomerLinkInterface::SALESPAD_CUSTOMER_NUM => 'SalesPad Customer Number'
    ];

    /**
     * @var CustomerInterface
     */
    private $customer;

    public function __construct(CustomerInterface $customer)
    {
        $this->customer = $customer;
    }

    public function __call($method, $args)
    {
        if (method_exists($this->customer, $method)) {

            return call_user_func_array([$this->customer, $method], $args);
        }

        throw new InvalidArgumentException(sprintf(
                'Capgemini_AvaTax: % - no such method in the customer object',
                $method)
        );
    }

    public function getSalesPadCustomerNum()
    {
        return $this->customer->getExtensionAttributes()->getSalesPadCustomerNum();
    }
}
