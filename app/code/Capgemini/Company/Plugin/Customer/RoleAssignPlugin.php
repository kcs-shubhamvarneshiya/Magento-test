<?php


namespace Capgemini\Company\Plugin\Customer;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Company\Api\RoleManagementInterface;
use Magento\Company\Model\Action\Customer\Assign;

class RoleAssignPlugin
{
    protected $roleManagement;

    public function __construct(
        RoleManagementInterface $roleManagement
    ) {
        $this->roleManagement = $roleManagement;
    }

    public function beforeAssignCustomerRole(Assign $subject, CustomerInterface $customer, $roleId)
    {
        // 0 can be admin role, so that is fine
        if ($roleId === null || $roleId === '') {
            $companyAttrs = $customer->getExtensionAttributes() ? $customer->getExtensionAttributes()->getCompanyAttributes() : null;
            if ($companyAttrs && $companyAttrs->getCompanyId()) {
                try {
                    $defaultRole = $this->roleManagement->getCompanyDefaultRole($companyAttrs->getCompanyId());
                    $roleId = $defaultRole->getId();
                } catch (\Exception $e) {
                }
            }
        }
        return [$customer, $roleId];
    }
}
