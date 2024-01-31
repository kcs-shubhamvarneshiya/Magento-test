<?php

namespace Lyonscg\OrderSearch\Controller\Guest;

use Lyonscg\SalesPad\Helper\Order as OrderHelper;
use Lyonscg\SalesPad\Model\Api\Order as OrderApi;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Registry as CoreRegistry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Helper\Guest as GuestHelper;

class View extends \Magento\Sales\Controller\Guest\View
{
    /**
     * @var OrderApi
     */
    protected $orderApi;

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    protected $orderHelper;

    /**
     * View constructor.
     * @param Context $context
     * @param GuestHelper $guestHelper
     * @param PageFactory $resultPageFactory
     * @param OrderApi $orderApi
     * @param OrderRepositoryInterface $orderRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CoreRegistry $coreRegistry
     */
    public function __construct(
        Context $context,
        GuestHelper $guestHelper,
        PageFactory $resultPageFactory,
        OrderApi $orderApi,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderHelper $orderHelper,
        CoreRegistry $coreRegistry
    ) {
        parent::__construct($context, $guestHelper, $resultPageFactory);
        $this->orderApi = $orderApi;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderHelper = $orderHelper;
        $this->coreRegistry = $coreRegistry;
    }

    public function execute()
    {
        $zip = $this->getRequest()->getParam('oar_zip');
        $orderId = $this->getRequest()->getParam('oar_order_id');

        $order = false;
        $salesDoc = $orderId;
        try {
            $order = $this->orderApi->search($salesDoc, $zip);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->resultRedirectFactory->create()->setPath('sales/guest/form');
        }

        if ($order === false) {
            $this->messageManager->addErrorMessage(__('Order %1 not found', $orderId));
            return $this->resultRedirectFactory->create()->setPath('sales/guest/form');
        }


        $fakeOrder = $this->orderHelper->salesPadOrderToMagentoOrder($order);
        $this->coreRegistry->register('salespad_current_order', $order);
        $this->coreRegistry->register('current_order', $fakeOrder);

        $resultPage = $this->resultPageFactory->create();
        $this->guestHelper->getBreadcrumbs($resultPage);
        $resultPage->getConfig()->getTitle()->set(__('Order #%1', $salesDoc));
        return $resultPage;
    }
}
