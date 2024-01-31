<?php

namespace Capgemini\OrderView\Block;

use Capgemini\OrderView\Helper\Data;
use Capgemini\OrderView\Model\InvoiceList;
use Capgemini\OrderView\Model\InvoiceListFactory;
use Capgemini\OrderView\Model\CreditmemoList;
use Capgemini\OrderView\Model\CreditmemoListFactory;
use Capgemini\OrderView\Model\OrderList;
use Capgemini\OrderView\Model\OrderListFactory;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;

class RecentData extends \Magento\Framework\View\Element\Template
{
    const DATA_LIMIT = 4;
    /**
     * @var InvoiceListFactory
     */
    protected $invoiceListFactory;
    /**
     * @var InvoiceList
     */
    protected $invoiceList  = null;
    /**
     * @var CreditmemoList
     */
    protected $creditList  = null;

    /**
     * @var CreditmemoListFactory
     */
    protected $creditListFactory;
    /*
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
    /**
     * @var Data
     */
    protected $helper;
    /**
     * @var PricingHelper
     */
    protected $pricingHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param InvoiceListFactory $invoiceListFactory
     * @param CreditmemoListFactory $creditListFactory
     * @param OrderListFactory $orderListFactory
     * @param Data $helper
     * @param PricingHelper $pricingHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        InvoiceListFactory $invoiceListFactory,
        CreditmemoListFactory $creditListFactory,
        OrderListFactory $orderListFactory,
        Data $helper,
        PricingHelper $pricingHelper,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->pricingHelper = $pricingHelper;
        $this->orderListFactory = $orderListFactory;
        $this->invoiceListFactory = $invoiceListFactory;
        $this->creditListFactory = $creditListFactory;
        $this->helper = $helper;
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
     * @return InvoiceList
     */
    public function getInvoices()
    {
        if ($this->invoiceList  === null) {
            $this->invoiceList  = $this->invoiceListFactory->create();
            $this->invoiceList ->setPageSize(self::DATA_LIMIT)->setCurPage(1);
            $this->invoiceList ->setCustomerNumber($this->getCustomerNumber());
        }
        return $this->invoiceList;
    }
    /**
     * @return OrderList
     */
    public function getOrders()
    {
        if ($this->orderList  === null) {
            $this->orderList = $this->orderListFactory->create();
            $this->orderList->setPageSize(self::DATA_LIMIT)->setCurPage(1);
            $this->orderList->setCustomerNumber($this->getCustomerNumber());
        }
        return $this->orderList;
    }

    /**
     * @return array
     */
    public function getOrderSummary()
    {
            $this->orderList = $this->orderListFactory->create();
        return $this->orderList->getOrderSummary($this->getCustomerNumber());
    }

    /**
     * @return array|null
     */
    public function getInvoiceSummary()
    {
        $this->invoiceList  = $this->invoiceListFactory->create();
        return $this->invoiceList->getInvoiceSummary($this->getCustomerNumber());
    }
     /**
     * @return CreditmemoList
     */
    public function getCreditmemos()
    {
        if ($this->creditList  === null) {
            $this->creditList  = $this->creditListFactory->create();
            $this->creditList ->setPageSize(self::DATA_LIMIT)->setCurPage(1);
            $this->creditList ->setCustomerNumber($this->getCustomerNumber());
        }
        return $this->creditList ;
    }

    /**
     * @return mixed
     */
    public function getCreditmemoSummary()
    {
            $this->creditList  = $this->creditListFactory->create();
        return $this->creditList->getCreditMemoSummary($this->getCustomerNumber());
    }
    public function getPrice($price)
    {
        return $this->pricingHelper->currency($price,true, true);
    }

}
