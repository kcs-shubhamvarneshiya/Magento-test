<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Model;

use Capgemini\RequestToOrder\Api\Data\OrderRequestInterface;
use Capgemini\RequestToOrder\Api\OrderRequestRepositoryInterface;
use Capgemini\RequestToOrder\Helper\Data as RequestHelper;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Area;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\View\LayoutFactory;
use Magento\Store\Model\StoreManagerInterface;

class RequestManagement
{
    /**
     * @var OrderRequestRepositoryInterface
     */
    protected $requestToOrderRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var RequestHelper
     */
    protected $helper;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @param OrderRequestRepositoryInterface $requestToOrderRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param RequestHelper $helper
     * @param StoreManagerInterface $storeManager
     * @param LayoutFactory $layoutFactory
     * @param TransportBuilder $transportBuilder
     */
    public function __construct(
        OrderRequestRepositoryInterface $requestToOrderRepository,
        CustomerRepositoryInterface     $customerRepository,
        RequestHelper                   $helper,
        StoreManagerInterface           $storeManager,
        LayoutFactory                   $layoutFactory,
        TransportBuilder                $transportBuilder
    ) {
        $this->requestToOrderRepository = $requestToOrderRepository;
        $this->customerRepository = $customerRepository;
        $this->helper = $helper;
        $this->storeManager = $storeManager;
        $this->layoutFactory = $layoutFactory;
        $this->transportBuilder = $transportBuilder;
    }

    /**
     * @param $orderRequest
     * @return void
     * @throws LocalizedException
     * @throws MailException
     * @throws NoSuchEntityException
     */
    public function submitRequest($orderRequest)
    {
        if (!$this->sendEmail($orderRequest)) {
            throw new LocalizedException(__('There is an error with email sending'));
        }

        $orderRequest->setStatus(OrderRequestInterface::STATUS_DISABLE);

        $this->requestToOrderRepository->save($orderRequest);
    }

    /**
     * @param $customerId
     * @return OrderRequestInterface|null
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCustomerRequest($customerId)
    {
        $orderRequest = $this->requestToOrderRepository->getByCustomerId((int)$customerId, true);
        $customer = $this->customerRepository->getById($customerId);
        if (!$orderRequest) {
            $orderRequest = $this->requestToOrderRepository->create();
            $orderRequest->setName($customer->getFirstname() . ' ' . $customer->getLastname());
            $orderRequest->setEmail($customer->getEmail());
            $orderRequest->setCustomerId((int)$customerId);
            $orderRequest->setStatus(OrderRequestInterface::STATUS_ENABLE);
        }

        return $orderRequest;
    }

    /**
     * @param OrderRequestInterface $orderRequest
     * @return bool
     * @throws LocalizedException
     * @throws MailException
     * @throws NoSuchEntityException
     */
    private function sendEmail(
        OrderRequestInterface $orderRequest
    ) {
        $store = $this->storeManager->getStore();
        $website = $this->storeManager->getWebsite();
        $websiteId = (int)$website->getId();
        $emailsTo = $this->helper->getRepresentativeEmail($websiteId);
        $emailFrom = $this->helper->getEmailFrom($websiteId);

        $requestItems = $orderRequest->getItems();
        $itemsBlock = $this->layoutFactory->create()
            ->createBlock(\Capgemini\RequestToOrder\Block\Request\Items::class);
        $itemsBlock->setRequestItems($requestItems);

        $requestData = [
            'id' => $orderRequest->getId(),
            'created_at' => $orderRequest->getCreatedAt(),
            'name' => $orderRequest->getName(),
            'phone' => $orderRequest->getPhone(),
            'email' => $orderRequest->getEmail(),
            'vc_account' => $orderRequest->getVcAccount(),
            'tech_account' => $orderRequest->getTechAccount(),
            'gl_account' => $orderRequest->getGlAccount(),
            'items_html' => $itemsBlock->toHtml(),
            'comments' => $orderRequest->getComments()
        ];

        if (!empty($emailsTo)) {
            $template = $this->helper->getSubmitEmailTemplate();

            if ($this->helper->isEmailCustomerCopy($websiteId)) {
                $this->transportBuilder->addBcc($orderRequest->getEmail());
            }

            $data = [
                'website_name' => $website->getName(),
                'group_name' => $store->getGroup()->getName(),
                'store_name' => $store->getName(),
                'data' => $requestData
            ];

            $transport = $this->transportBuilder->setTemplateIdentifier(
                $template
            )->setTemplateOptions(
                ['area' => Area::AREA_FRONTEND, 'store' => $store->getId()]
            )
                ->setFrom($emailFrom)
                ->setTemplateVars($data)
                ->addTo($emailsTo)
                ->getTransport();

            $transport->sendMessage();
            return true;
        }

        return false;
    }
}
