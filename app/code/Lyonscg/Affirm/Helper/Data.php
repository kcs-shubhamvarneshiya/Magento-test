<?php

namespace Lyonscg\Affirm\Helper;

use Lyonscg\SalesPad\Helper\Customer as CustomerHelper;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Request\Http;

class Data extends AbstractHelper
{
    const TO_CONFIG_VALUE_MAPPING = [
        'prior'               => 'payment/affirm_gateway/trade_customers/hide_on_checkout',
        'checkout_cart_index'  => 'payment/affirm_gateway/trade_customers/hide_on_cart',
        'catalog_product_view' => 'payment/affirm_gateway/trade_customers/hide_on_prod'
    ];

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var CustomerHelper
     */
    private $customerHelper;

    /**
     * @var bool
     */
    private $isNeedToHideCached;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param CustomerHelper $customerHelper
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        CustomerHelper $customerHelper
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->customerHelper = $customerHelper;
    }

    /**
     * @return bool
     */
    public function isNeedToHide(): bool
    {
        if (!$this->shouldTreatAsCompany()) {
            $this->isNeedToHideCached = false;
        } else if ($this->scopeConfig->getValue(self::TO_CONFIG_VALUE_MAPPING['prior'], 'website')) {
            $this->isNeedToHideCached = true;
        } else if(!$handle = $this->analyzeRequest()) {
            $this->isNeedToHideCached = true;
        } else if (isset(self::TO_CONFIG_VALUE_MAPPING[$handle])) {
            if ($this->scopeConfig->getValue(self::TO_CONFIG_VALUE_MAPPING[$handle], 'website')) {
                $this->isNeedToHideCached = true;
            } else {
                $this->isNeedToHideCached = false;
            }
        } else {
            $this->isNeedToHideCached = false;
        }

        return $this->isNeedToHideCached;
    }

    /**
     * @return false|string
     */
    private function analyzeRequest()
    {
        if (!$this->_request instanceof Http) {

            return false;
        }

        return $this->_request->getFullActionName();
    }

    /**
     * @return bool
     */
    private function shouldTreatAsCompany(): bool
    {
        try {
            $customer = $this->customerSession->getCustomerData();

            if (!$customer) {

                return  false;
            }
        } catch (\Exception $exception) {
            return false;
        }

        return (bool)$this->customerHelper->getCompany($customer);
    }
}
