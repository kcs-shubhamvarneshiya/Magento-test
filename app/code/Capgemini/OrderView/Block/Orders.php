<?php

namespace Capgemini\OrderView\Block;

use Capgemini\OrderView\Helper\Data;
use Capgemini\OrderView\Model\OrderList;
use Capgemini\OrderView\Model\OrderListFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Sales\Helper\Reorder as ReorderHelper;
use Magento\Framework\Data\Helper\PostHelper;

class Orders extends \Magento\Framework\View\Element\Template
{
    const INITIAL_LIMIT = 20;
    protected $_template = 'Capgemini_OrderView::order-list.phtml';

    /**
     * @var OrderListFactory
     */
    protected $orderListFactory;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var ReorderHelper
     */
    protected $reorderHelper;

    /**
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * @var PostHelper
     */
    protected $postHelper;

    /**
     * @var OrderList
     */
    protected $orderList = null;

    /**
     * @var string
     */
    protected $customerNumber;

    /**
     * @var string
     */
    protected $customerType;
    private $urlInterface;
    /*
    /* @param RequestInterface $request
    */

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        OrderListFactory $orderListFactory,
        Data $helper,
        ReorderHelper $reorderHelper,
        PostHelper $postHelper,
        RequestInterface $request,
        \Magento\Framework\UrlInterface $urlInterface,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->orderListFactory = $orderListFactory;
        $this->helper = $helper;
        $this->reorderHelper = $reorderHelper;
        $this->postHelper = $postHelper;
        $this->request = $request;
        $this->urlInterface = $urlInterface;
    }

    /**
     * @return string
     */
    public function getCustomerNumber()
    {
        if ($this->customerNumber === null) {
            $this->customerNumber = $this->helper->getCustomerNumber();
        }
        return $this->customerNumber;
    }

    /**
     * @return string
     */
    public function getCustomerType()
    {
        if ($this->customerType === null) {
            $this->customerType = $this->helper->getCustomerType();
        }
        return $this->customerType;
    }

    /**
     * @return OrderList
     */
    public function getOrders($page, $limit,$customerNumber, $filtersParams)
    {
        if ($this->orderList === null) {
            $this->orderList = $this->orderListFactory->create();
            $this->orderList->setPageSize($limit)->setCurPage($page);
            $this->orderList->setCustomerNumber($customerNumber);
            if(!empty($filtersParams))
            {
                $this->orderList ->addApiFilters($filtersParams);
            }

        }
        return $this->orderList;
    }

     public function getFilter()
    {
        return $this->orderList->_getFilters();
    }

    /**
     * Get Pager child block output
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @return string
     */
    public function getReorderData($order)
    {
        if ($order->getId() && $this->reorderHelper->canReorder($order->getId())) {
            $reorderUrl = $this->getUrl('orderview/orders/reorder', ['order_id' => $order->getId()]);
            return $this->postHelper->getPostData($reorderUrl);
        } else {
            return '';
        }
    }

    public function getCustomerVcNumber()
    {
        $customerVcId = null;
        $customer = $this->helper->getCustomer();
        if ($customer->getCustomAttribute('customer_number_vc')) {

            $customerVcId = $customer->getCustomAttribute('customer_number_vc')->getValue();
        }
        return $customerVcId;
    }
    public function getOrderFilters()
    {
        if ($this->orderList  !== null) {
            return $this->orderList->getCustomFilters();
        }
    }
    public function getOrderFilter($field)
    {
        if ($this->orderList  !== null && !empty($field)) {
            return $this->orderList->getCustomFilter($field);
        }
    }
    public function getCurrentUrl()
    {
        return $this->urlInterface->getCurrentUrl();
    }

    public function getCustomerId(){

        $customer = $this->helper->getCustomer();
       
        if($this->request->getParam('account_id'))
        {
            $customerId = $this->request->getParam('account_id');
        }else {
            if(is_object($customer)){
            if ($customer->getCustomAttribute('customer_number_vc') && $customer->getCustomAttribute('customer_number_vc')->getValue() != null) {
                $customerVcId = $customer->getCustomAttribute('customer_number_vc')->getValue();
                if (!is_null($customerVcId)) {
                    $customerId = trim($customerVcId," ");
                }
            }else if ($customer->getCustomAttribute('customer_number_gl') && $customer->getCustomAttribute('customer_number_gl')->getValue() != null) {
                $customerGlId = $customer->getCustomAttribute('customer_number_gl')->getValue();
                if (!is_null($customerGlId)) {
                    $customerId = trim($customerGlId, " ");
                }
            }else if ($customer->getCustomAttribute('customer_number_tech') && $customer->getCustomAttribute('customer_number_tech')->getValue() != null) {
                $customerTlId = $customer->getCustomAttribute('customer_number_tech')->getValue();

                if (!is_null($customerTlId)) {
                    $customerId = trim($customerTlId, " ");
                }
            }else if(($customer->getExtensionAttributes()->getSalesPadCustomerNum())){
            $customerId = $customer->getExtensionAttributes()->getSalesPadCustomerNum();
            } 
        }
        }
        if(isset($customerId) && ($customerId!='')){
        return $customerId;
        }else{
            return false;
        }

    }
}
