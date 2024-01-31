<?php

namespace Capgemini\RequestToOrder\Plugin\Customer\Block\Account;

use Capgemini\RequestToOrder\Model\PermissionManager;
use Magento\Customer\Block\Account\Navigation;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\AbstractBlock;

class NavigationPlugin
{
    /**
     * @var PermissionManager
     */
    protected PermissionManager $permissionManager;

    /**
     * @var CustomerSession
     */
    protected CustomerSession $customerSession;

    /**
     * @param PermissionManager $permissionManager
     * @param CustomerSession $customerSession
     */
    public function __construct(
        PermissionManager $permissionManager,
        CustomerSession $customerSession
    ) {
        $this->permissionManager = $permissionManager;
        $this->customerSession = $customerSession;
    }

    /**
     * @param Navigation $subject
     * @param callable $proceed
     * @param AbstractBlock $link
     * @return string
     * @throws NoSuchEntityException
     */
    public function aroundRenderLink(
        Navigation $subject,
        callable $proceed,
        AbstractBlock $link
    ): string {
        $customer = $this->customerSession->getCustomer();
        if ($link->getNameInLayout() == 'request-to-order'
            && !$this->permissionManager->isRequestToOrderAllowed($customer)) {
            return '';
        }
        return $proceed($link);
    }
}
