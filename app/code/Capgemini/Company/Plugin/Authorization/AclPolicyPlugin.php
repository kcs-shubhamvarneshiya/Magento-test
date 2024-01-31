<?php

namespace Capgemini\Company\Plugin\Authorization;
use Magento\Company\Model\Authorization\AclPolicy;
use Magento\Company\Api\RoleRepositoryInterface;
class AclPolicyPlugin
{
    protected RoleRepositoryInterface $roleRepository;
    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;

    }
    public function afterIsAllowed(AclPolicy $subject, $result,  $roleId, $resourceId, $privilege)
    {
        if ($roleId != null && $roleId !== 0) {
            $roleData = $this->roleRepository->get($roleId);
            /**
             * @var $permissions \Magento\Company\Api\Data\PermissionInterface[]
             */
            $permissions = $roleData->getPermissions();
            foreach ($permissions as $permission) {
                if ($permission->getResourceId() === $resourceId) {
                    $result = ($permission->getPermission() === "allow") ? true : false;
                }
            }
        }
            return $result;
    }
}
