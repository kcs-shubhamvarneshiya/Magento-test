<?php

namespace Capgemini\OrderView\Block;
use Capgemini\OrderView\Helper\Data;
use Capgemini\OrderView\Model\OrderList;
use Capgemini\OrderView\Model\OrderListFactory;
use Magento\Framework\Url\DecoderInterface;

class DropDownFilter extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Data
     */
    protected $helper;
    /**
     * @var OrderListFactory
     */
    protected $orderListFactory;
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
    protected $placeHolder;
    /**
     * @var string
     */
    protected $filterUrl;
    /**
     * @var boolean
     */
    protected $searchAvailable;

    /**
     * @var string
     */
    protected $customerType;
    private $urlInterface;
    /**
     * @var DecoderInterface
     */
    protected DecoderInterface $urlDecoder;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        OrderListFactory $orderListFactory,
        Data $helper,
        \Magento\Framework\UrlInterface $urlInterface,
        DecoderInterface $urlDecoder,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->urlDecoder = $urlDecoder;
        $this->orderListFactory = $orderListFactory;
        $this->helper = $helper;
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

    public function getCustomerVcNumber()
    {
        $customerVcId = null;
        $customer = $this->helper->getCustomer();
        if ($customer->getCustomAttribute('customer_number_vc')) {

            $customerVcId = $customer->getCustomAttribute('customer_number_vc')->getValue();
        }
        return $customerVcId;
    }

    public function getCurrentUrl()
    {
        return $this->urlInterface->getCurrentUrl();
    }
    public function setPlaceHolder($placeHolder)
    {
        $this->placeHolder = $placeHolder;
    }
    public function getPlaceHolder()
    {
        return$this->placeHolder;
    }
    public function setFilterUrl($filterUrl)
    {
        $this->filterUrl = $filterUrl;
    }
    public function getFilterUrl()
    {
        return$this->filterUrl;
    }

    /**
     * @param $params
     * @param $removeParam
     * @param $compareValue
     * @return mixed
     */
    public function removeParam($params, $removeParam, $compareValue = null)
    {
        if($compareValue == null)
        {
            if(isset($params[$removeParam]))
            {
                unset($params[$removeParam]);
            }
        }
        else{
            if(isset($params[$removeParam]) && $params[$removeParam] == $compareValue)
            {
                unset($params[$removeParam]);
            }
        }
        return $params;
    }
    public function getFilter()
    {
        if ($this->orderList === null) {
            $this->orderList = $this->orderListFactory->create();
        }
        return $this->orderList->_getFilters();
    }
    public function isSearchAvailable($searchAvailable)
    {
      $this->searchAvailable = $searchAvailable;
      return $this;
    }
    public function getIsSearchAvailable()
    {
      return $this->searchAvailable;
    }
    public function getDecodeValue($searchValue)
    {
        return $this->urlDecoder->decode($searchValue);
    }
}
