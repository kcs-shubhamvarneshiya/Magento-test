<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Controller\Item;

use Capgemini\RequestToOrder\Api\OrderRequest\ItemRepositoryInterface;
use Capgemini\RequestToOrder\Api\OrderRequestRepositoryInterface;
use Capgemini\RequestToOrder\Controller\Request;
use Capgemini\RequestToOrder\Model\PermissionManager;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\UrlInterface;

class Remove extends Request implements HttpGetActionInterface
{
    /**
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * @var OrderRequestRepositoryInterface
     */
    protected OrderRequestRepositoryInterface $requestToOrderRepository;

    /**
     * @var ItemRepositoryInterface
     */
    protected ItemRepositoryInterface $itemRequestRepository;

    /**
     * @var MessageManagerInterface
     */
    protected MessageManagerInterface $messageManager;

    /**
     * @param RequestInterface $request
     * @param ItemRepositoryInterface $itemRequestRepository
     * @param OrderRequestRepositoryInterface $requestToOrderRepository
     * @param MessageManagerInterface $messageManager
     * @param RedirectFactory $resultRedirectFactory
     * @param RedirectInterface $redirect
     * @param PermissionManager $permissionManager
     * @param UrlInterface $urlInterface
     * @param Session $customerSession
     */
    public function __construct(
        RequestInterface                $request,
        ItemRepositoryInterface         $itemRequestRepository,
        OrderRequestRepositoryInterface $requestToOrderRepository,
        MessageManagerInterface         $messageManager,
        RedirectFactory                 $resultRedirectFactory,
        RedirectInterface               $redirect,
        PermissionManager               $permissionManager,
        UrlInterface                    $urlInterface,
        Session                         $customerSession
    ) {
        parent::__construct(
            $redirect,
            $permissionManager,
            $resultRedirectFactory,
            $urlInterface,
            $customerSession
        );
        $this->request = $request;
        $this->itemRequestRepository = $itemRequestRepository;
        $this->requestToOrderRepository = $requestToOrderRepository;
        $this->messageManager = $messageManager;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->redirect = $redirect;
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        try {
            $this->checkCustomerAuth();

            if (!$this->validateCustomer()) {
                return $this->getRedirectToNoRoute();
            }

            $data = $this->request->getParams();
            $requestId = $data['request_id'] ?? null;
            $requestItemId = $data['item_id'] ?? null;

            if (!$requestId) {
                throw new LocalizedException(__('Please, specify request entity id'));
            }
            if (!$requestItemId) {
                throw new LocalizedException(__('Please, specify item id to remove'));
            }

            $requestItem = $this->itemRequestRepository->getById((int)$requestItemId);
            $orderRequest = $this->requestToOrderRepository->getById((int)$requestId);
            $orderRequest->deleteItem($requestItem);

            $this->messageManager->addSuccessMessage(__('Item was successfully removed'));
        } catch (LocalizedException $exception) {
            $this->messageManager->addExceptionMessage($exception, __($exception->getMessage()));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setUrl($this->redirect->getRefererUrl());

        return $resultRedirect;
    }
}
