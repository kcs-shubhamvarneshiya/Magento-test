<?php

namespace Capgemini\OrderView\Block;

use Capgemini\OrderView\Helper\Data;
use Capgemini\OrderView\Model\CreditmemoList;
use Capgemini\OrderView\Model\CreditmemoListFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Sales\Helper\Reorder as ReorderHelper;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;
class Creditmemos extends \Magento\Framework\View\Element\Template
{
    const INITIAL_LIMIT = 20;
    /**
     * @var CreditmemoListFactory
     */
    protected $creditmemoListFactory;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var ReorderHelper
     */
    protected $reorderHelper;

    /**
     * @var PostHelper
     */
    protected $postHelper;
    /**
     * @var PricingHelper
     */
    protected $pricingHelper;

    /**
     * @var CreditmemoList
     */
    protected $creditList  = null;

    /**
     * @var RequestInterface
     */
    protected RequestInterface $request;

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
        CreditmemoListFactory $creditmemoListFactory,
        Data $helper,
        ReorderHelper $reorderHelper,
        PostHelper $postHelper,
        PricingHelper $pricingHelper,
        RequestInterface $request,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->creditmemoListFactory = $creditmemoListFactory;
        $this->helper = $helper;
        $this->reorderHelper = $reorderHelper;
        $this->postHelper = $postHelper;
        $this->request = $request;
        $this->pricingHelper = $pricingHelper;
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
     * @return CreditmemoList
     */
    public function getCreditmemos($page, $limit)
    {
        if ($this->creditList  === null) {
            $this->creditList = $this->creditmemoListFactory->create();
            $this->creditList->setPageSize($limit)->setCurPage($page);
            $this->creditList->setCustomerNumber($this->getCustomerNumber());
        }

        return $this->creditList ;
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
    public function getPrice($price)
    {
        return $this->pricingHelper->currency($price,true, false);
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

