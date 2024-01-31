<?php

namespace Capgemini\RequestToOrder\Model;

use Capgemini\CompanyType\Model\Product\PurchasePermission;
use Magento\Framework\Exception\NoSuchEntityException;

class PermissionManager
{
    /**
     * @var PurchasePermission
     */
    protected PurchasePermission $purchasePermission;

    /**
     * @param PurchasePermission $purchasePermission
     */
    public function __construct(
        PurchasePermission $purchasePermission
    ) {
        $this->purchasePermission = $purchasePermission;
    }

    /**
     * @param $customer
     * @param $product
     * @return bool
     * @throws NoSuchEntityException
     */
    public function isRequestToOrderAllowed($customer, $product = false): bool
    {
        if (!$this->purchasePermission->canValidate($customer)) {
            return false;
        }

        if ($product) {
            return $this->purchasePermission->validateByProduct(
                    $product, $customer
                ) == false;
        }

        return true;
    }
}
