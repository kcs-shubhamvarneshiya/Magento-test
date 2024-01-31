<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Controller;

use Capgemini\RequestToOrder\Model\PermissionManager;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\SessionException;
use Magento\Framework\UrlInterface;

class Request
{
    /**
     * @var CustomerSession
     */
    protected CustomerSession $customerSession;

    /**
     * @var PermissionManager
     */
    protected PermissionManager $permissionManager;

    /**
     * @var RedirectFactory
     */
    protected RedirectFactory $resultRedirectFactory;

    /**
     * @var UrlInterface
     */
    protected UrlInterface $urlInterface;

    /**
     * @var RedirectInterface
     */
    protected RedirectInterface $redirect;

    /**
     * @param RedirectInterface $redirect
     * @param PermissionManager $permissionManager
     * @param RedirectFactory $resultRedirectFactory
     * @param UrlInterface $urlInterface
     * @param CustomerSession $customerSession
     */
    public function __construct(
        RedirectInterface $redirect,
        PermissionManager $permissionManager,
        RedirectFactory   $resultRedirectFactory,
        UrlInterface      $urlInterface,
        CustomerSession   $customerSession
    ) {
        $this->redirect = $redirect;
        $this->permissionManager = $permissionManager;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->urlInterface = $urlInterface;
        $this->customerSession = $customerSession;
    }

    /**
     * @return bool
     * @throws NoSuchEntityException
     */
    protected function validateCustomer(): bool
    {
        $customer = $this->getCustomer();

        return $this->permissionManager->isRequestToOrderAllowed($customer);
    }

    /**
     * @return Customer
     */
    protected function getCustomer(): Customer
    {
        return $this->customerSession->getCustomer();
    }

    /**
     * @return void
     * @throws SessionException
     */
    protected function checkCustomerAuth()
    {
        if (!$this->customerSession->isLoggedIn()) {
            $this->customerSession->authenticate();
        }
    }

    /**
     * @return Redirect
     */
    protected function getRedirectToNoRoute(): Redirect
    {
        return $this->createRedirect('noroute');
    }

    /**
     * @param string $route
     * @return Redirect
     */
    protected function createRedirect(string $route): Redirect
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setUrl($this->urlInterface->getUrl($route));

        return $resultRedirect;
    }

    /**
     * @return Redirect
     */
    protected function getRedirectToIndex(): Redirect
    {
        return $this->createRedirect($this->redirect->getRefererUrl());
    }
}
