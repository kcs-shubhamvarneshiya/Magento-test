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

use Capgemini\RequestToOrder\Api\OrderRequestRepositoryInterface;
use Capgemini\RequestToOrder\Model\PermissionManager;
use Capgemini\RequestToOrder\Model\RequestManagement;
use Magento\Company\Api\CompanyRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\App\View;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Company\Api\CompanyManagementInterface;

class Submit extends Index implements HttpPostActionInterface
{
    /**
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * @var JsonFactory
     */
    protected JsonFactory $jsonResultFactory;

    /**
     * @var OrderRequestRepositoryInterface
     */
    protected OrderRequestRepositoryInterface $requestToOrderRepository;

    /**
     * @var RequestManagement
     */
    protected RequestManagement $orderRequestManagement;

    /**
     * @var RedirectFactory
     */
    protected RedirectFactory $resultRedirectFactory;

    /**
     * @var MessageManagerInterface
     */
    protected MessageManagerInterface $messageManager;

    /**
     * @var CompanyManagementInterface
     */
    protected CompanyManagementInterface $companyManagement;

    /**
     * @var CompanyRepositoryInterface
     */
    private CompanyRepositoryInterface $companyRepository;

    /**
     * @var CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @param RequestInterface $request
     * @param JsonFactory $jsonResultFactory
     * @param MessageManagerInterface $messageManager
     * @param RedirectFactory $resultRedirectFactory
     * @param RequestManagement $orderRequestManagement
     * @param OrderRequestRepositoryInterface $requestToOrderRepository
     * @param PermissionManager $permissionManager
     * @param UrlInterface $urlInterface
     * @param PageFactory $resultPageFactory
     * @param RedirectInterface $redirect
     * @param CustomerSession $customerSession
     * @param CompanyManagementInterface $companyManagement
     * @param CompanyRepositoryInterface $companyRepository
     * @param CurrentCustomer $currentCustomer
     * @param View $view
     */
    public function __construct(
        RequestInterface                $request,
        JsonFactory                     $jsonResultFactory,
        MessageManagerInterface         $messageManager,
        RedirectFactory                 $resultRedirectFactory,
        RequestManagement               $orderRequestManagement,
        OrderRequestRepositoryInterface $requestToOrderRepository,
        PermissionManager               $permissionManager,
        UrlInterface                    $urlInterface,
        PageFactory                     $resultPageFactory,
        RedirectInterface               $redirect,
        CustomerSession                 $customerSession,
        CompanyManagementInterface      $companyManagement,
        CompanyRepositoryInterface      $companyRepository,
        CurrentCustomer                 $currentCustomer,
        View $view
    ) {
        parent::__construct(
            $resultPageFactory,
            $redirect,
            $permissionManager,
            $resultRedirectFactory,
            $urlInterface,
            $customerSession,
            $view
        );
        $this->request = $request;
        $this->messageManager = $messageManager;
        $this->requestToOrderRepository = $requestToOrderRepository;
        $this->orderRequestManagement = $orderRequestManagement;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->urlInterface = $urlInterface;
        $this->jsonResultFactory = $jsonResultFactory;
        $this->companyManagement = $companyManagement;
        $this->companyRepository = $companyRepository;
        $this->currentCustomer = $currentCustomer;
    }


    public function execute()
    {
        try {
            $this->checkCustomerAuth();

            if (!$this->validateCustomer()) {
                return $this->getRedirectToNoRoute();
            }

            $data = $this->request->getPostValue();
            $requestId = $data['request_id'] ?? null;

            if (!$requestId) {
                throw new LocalizedException(__('Please, specify request entity id'));
            }

            $orderRequest = $this->requestToOrderRepository->getById((int)$requestId);
            $customer = $this->currentCustomer->getCustomer();
            $customerId = $customer->getId();
            $companyMan = $this->companyManagement->getByCustomerId($customerId);
            $companyId = null;
            $company = null;
            if ($companyMan) {
                $companyId = $companyMan->getId();
            }

            if ($companyId) {
                $company = $this->companyRepository->get($companyId);
            }

            $name = $data['name'] ?? null;
            $phone = $data['phone'] ?? null;
            $email = $data['email'] ?? null;
            $comments = $data['comments'] ?? null;

            $vc_account = null;
            if ($customer->getCustomAttribute('customer_number_vc')) {
                $vc_account = $customer->getCustomAttribute('customer_number_vc')->getValue();
            }

            $gl_account = null;
            if ($customer->getCustomAttribute('customer_number_gl')) {
                $gl_account = $customer->getCustomAttribute('customer_number_gl')->getValue();
            }

            $tech_account = null;
            if ($customer->getCustomAttribute('customer_number_tech')) {
                $tech_account = $customer->getCustomAttribute('customer_number_tech')->getValue();
            }

            if ($name) {
                $orderRequest->setName($name);
            }

            if ($phone) {
                $orderRequest->setPhone($phone);
            }

            if ($email) {
                $orderRequest->setEmail($email);
            }

            if ($vc_account) {
                $orderRequest->setVcAccount($vc_account);
            }

            if ($gl_account) {
                $orderRequest->setGlAccount($gl_account);
            }

            if ($tech_account) {
                $orderRequest->setTechAccount($tech_account);
            }

            if ($comments) {
                $orderRequest->setComments($comments);
            }

            $this->orderRequestManagement->submitRequest($orderRequest);

            $this->messageManager->addSuccessMessage(__('Request was successfully submitted'));

            return $this->redirectToSuccess();
        } catch (LocalizedException $exception) {
            $this->messageManager->addExceptionMessage($exception, __($exception->getMessage()));
        }

        return $this->getRedirectToIndex();
    }

    /**
     * @return Redirect
     */
    private function redirectToSuccess()
    {
        return $this->createRedirect(parent::URL_PATH_SUCCESS);
    }
}
