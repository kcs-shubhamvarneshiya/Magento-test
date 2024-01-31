<?php

namespace Capgemini\Company\Plugin\Customer;

use Capgemini\Company\Model\Company\DataProvider;
use Lyonscg\SalesPad\Api\CustomerLinkRepositoryInterface;
use Magento\Company\Model\CompanySuperUserGet;

class UpdateSalesPadNum
{
    private static $spCustomerNum;

    public static function getSpCustomerNum()
    {
        return self::$spCustomerNum;
    }

    public function aroundGetUserForCompanyAdmin(CompanySuperUserGet $subject, callable $proceed, array $data)
    {
        self::$spCustomerNum = $data['extension_attributes'][DataProvider::SALESPAD_CUSTOMER_NUM] ?? null;
        $result = $proceed($data);
        self::$spCustomerNum = null;

        return $result;
    }
}
