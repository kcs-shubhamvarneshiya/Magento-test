<?php

namespace Lyonscg\Contact\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Capgemini\CompanyType\Model\Config;

/**
 * @api \Lyonscg\Contact\Helper\Data
 */
class Data extends AbstractHelper
{
    protected const PHONE = 'contact/contact_info/phone';
    protected const EMAIL = 'contact/contact_info/email';
    protected const WHOLESALE_PHONE = 'contact/contact_info/wholesalephone';
    protected const WHOLESALE_EMAIL = 'contact/contact_info/wholesaleemail';
    /**
     * @var Config
     */
    public $customer;

    /**
     * @param \Capgemini\CompanyType\Model\Config
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Config $config,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ){
        $this->config = $config;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * Check customer type ex. wholesale , trade or retail
     * @return bool
     */
    public function isWholeSaleCustomer()
    {
        $customerType = $this->config->getCustomerCompanyType();
        if($customerType == Config::WHOLESALE){
            return true;
        }
        return false;
    }

    /**
     * Get Contact Information Phone by store
     * @return string|null
     */
    public function getPhone()
    {
        if($this->isWholeSaleCustomer()){
            $phone = self::WHOLESALE_PHONE;
        } else {
            $phone = self::PHONE;
        }
        return $this->scopeConfig->getValue(
            $phone,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
    }

    /**
     * Get Contact Information Email by store
     * @return string|null
     */
    public function getEmail()
    {
        if($this->isWholeSaleCustomer()){
            $email = self::WHOLESALE_EMAIL;
        } else {
            $email = self::EMAIL;
        }
        return $this->scopeConfig->getValue(
            $email,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
    }

    /**
     * Get Current Store Id
     * @return int
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getStoreId();
    }
}