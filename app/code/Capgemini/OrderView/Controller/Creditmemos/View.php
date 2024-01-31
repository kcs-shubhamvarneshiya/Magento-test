<?php

namespace Capgemini\OrderView\Controller\Creditmemos;

use Capgemini\CompanyType\Model\Config;
use Capgemini\OrderView\Helper\Data;
use Capgemini\PartnersInsight\Model\Api\Creditmemo as PiOrderApi;
use Capgemini\PartnersInsight\Helper\Creditmemo  as PiOrderHelper;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class View extends \Magento\Framework\App\Action\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var Registry
     */
    protected $registry;

    protected $order = null;
    protected $ponumber = null;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var ForwardFactory
     */
    private $forwardFactory;

    /**
     * @var PiOrderApi
     */
    protected $piOrderApi;

    /**
     * @var PiOrderHelper
     */
    protected $piOrderHelper;

    /**
     * View constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Data $helper
     * @param Registry $registry
     * @param Session $customerSession
     * @param ForwardFactory $forwardFactory
     * @param PiOrderApi $piOrderApi
     * @param PiOrderHelper $piOrderHelper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Data $helper,
        Registry $registry,
        Session $customerSession,
        ForwardFactory $forwardFactory,
        PiOrderApi  $piOrderApi,
        PiOrderHelper  $piOrderHelper
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->helper = $helper;
        $this->registry = $registry;
        $this->customerSession = $customerSession;
        $this->forwardFactory = $forwardFactory;
        $this->piOrderApi = $piOrderApi;
        $this->piOrderHelper = $piOrderHelper;
    }

    public function execute()
    {
        if (!$this->customerSession->isLoggedIn()) {
            $this->customerSession->authenticate();
        }

        $orderNumber = $this->getRequest()->getParam('id');

        $order = $this->_getCreditmemo($orderNumber);

        if (!$order) {
            $resultForward = $this->forwardFactory->create();
            $resultForward->setController('index');
            $resultForward->forward('defaultNoRoute');
            return $resultForward;
        }

        $this->registry->register('current_order', $order);

        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Credit Memo #%1', $orderNumber));

        $block = $resultPage->getLayout()->getBlock('customer.account.link.back');
        if ($block) {
            $block->setRefererUrl($this->_redirect->getRefererUrl());
        }

        return $resultPage;
    }

    protected function _getCreditmemo($orderId)
    {
        if ($this->order === null) {
            // check for wholesale customers
            if ($this->helper->getCustomerType() == Config::WHOLESALE) {
                $orderData = $this->piOrderApi->getCreditmemo($orderId);
                $this->order = $this->piOrderHelper->partnersInsightOrderToMagentoOrder($orderData);
            }
        }
        return $this->order;
    }

    protected function _getCustomerNum()
    {
        return $this->helper->getCustomerNumber();
    }

    public function getPoNumber()
    {
        return $this->ponumber;
    }
}
