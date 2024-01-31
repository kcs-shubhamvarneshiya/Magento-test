<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Controller\Request;

use Capgemini\RequestToOrder\Controller\Request;
use Capgemini\RequestToOrder\Model\PermissionManager;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\App\ResponseInterface as ResponseInterfaceAlias;
use Magento\Framework\App\View;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException as NoSuchEntityExceptionAlias;
use Magento\Framework\Exception\SessionException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Request implements HttpGetActionInterface
{
    public const URL_PATH = 'orequest/request/index';
    public const URL_PATH_SUCCESS = 'orequest/request/success';

    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * @param PageFactory $resultPageFactory
     * @param RedirectInterface $redirect
     * @param PermissionManager $permissionManager
     * @param RedirectFactory $resultRedirectFactory
     * @param UrlInterface $urlInterface
     * @param CustomerSession $customerSession
     * @param View $view
     */
    public function __construct(
        PageFactory       $resultPageFactory,
        RedirectInterface $redirect,
        PermissionManager $permissionManager,
        RedirectFactory   $resultRedirectFactory,
        UrlInterface      $urlInterface,
        CustomerSession   $customerSession,
        View $view
    ) {
        parent::__construct(
            $redirect,
            $permissionManager,
            $resultRedirectFactory,
            $urlInterface,
            $customerSession
        );
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return ResponseInterfaceAlias|Redirect|ResultInterface|Page
     * @throws NoSuchEntityExceptionAlias
     * @throws SessionException
     */
    public function execute()
    {
        try {
            $this->checkCustomerAuth();

            if (!$this->validateCustomer()) {
                return $this->getRedirectToNoRoute();
            }

            /** @var Page $resultPage */
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set(__('Request To Order'));

            $block = $resultPage->getLayout()->getBlock('customer.account.link.back');
            if ($block) {
                $block->setRefererUrl($this->redirect->getRefererUrl());
            }
            return $resultPage;
        } catch (LocalizedException $exception) {
            return $this->getRedirectToIndex();
        }
    }
}
