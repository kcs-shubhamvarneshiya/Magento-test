<?php

namespace Capgemini\OrderView\Block;

use Capgemini\OrderView\Helper\Data;
use Capgemini\OrderView\Model\InvoiceList;
use Capgemini\OrderView\Model\InvoiceListFactory;
use Magento\Sales\Helper\Reorder as ReorderHelper;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Helper\PostHelper;

class Invoices extends \Magento\Framework\View\Element\Template
{
    const INITIAL_LIMIT = 20;
    protected $_template = 'Capgemini_OrderView::invoice-list.phtml';

    /**
     * @var InvoiceListFactory
     */
    protected $invoiceListFactory;

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
     * @var invoiceList
     */
    protected $invoiceList  = null;

    /**
     * @var string
     */
    protected $customerNumber;

    /**
     * @var string
     */
    protected $customerType;

    /*
    /* @param RequestInterface $request
    */

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        InvoiceListFactory $invoiceListFactory,
        Data $helper,
        ReorderHelper $reorderHelper,
        PostHelper $postHelper,
        RequestInterface $request,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->invoiceListFactory = $invoiceListFactory;
        $this->helper = $helper;
        $this->reorderHelper = $reorderHelper;
        $this->request = $request;
        $this->postHelper = $postHelper;
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
     * @return invoiceList
     */
    public function getInvoices($page, $limit,$filtersParams)
    {
        if ($this->invoiceList  === null) {
            $this->invoiceList  = $this->invoiceListFactory->create();
            $this->invoiceList ->setPageSize($limit)->setCurPage($page);
            $this->invoiceList ->setCustomerNumber($this->getCustomerNumber());
            if(!empty($filtersParams))
            {
                $this->invoiceList ->addApiFilters($filtersParams);
            }
        }
        return $this->invoiceList ;
    }
    public function getInvoiceFilters()
    {
        if ($this->invoiceList  !== null) {
            return $this->invoiceList->getCustomFilters();
        }
    }
    public function getInvoiceFilter($field)
    {
        if ($this->invoiceList  !== null && !empty($field)) {
            return $this->invoiceList->getCustomFilter($field);
        }
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
            $reorderUrl = $this->getUrl('orderview/invoices/reorder', ['invoice_id' => $order->getId()]);
            return $this->postHelper->getPostData($reorderUrl);
        } else {
            return '';
        }
    }
    public function removeParam($params, $removeParam)
    {
        if(isset($params[$removeParam]))
        {
            unset($params[$removeParam]);
        }
        return $params;
    }

    public function getCustomerId(){

        $customer = $this->helper->getCustomer();
        if($this->request->getParam('account_id'))
        {
            $customerId = $this->request->getParam('account_id');
        }else {
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
        if(isset($customerId) && ($customerId!='')){
            return $customerId;
            }else{
                return false;
            }
    }
}
