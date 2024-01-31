<?php
/**
 * Capgemini_WholesaleAddress
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\WholesaleAddress\ViewModel;

use Capgemini\CompanyType\Model\Config;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Wholesale customer type detector
 */
class WholesaleDetector implements ArgumentInterface
{
    /**
     * @var Session
     */
    protected $customerSession;
    /**
     * @var Config
     */
    protected $companyTypeConfig;

    /**
     * CustomerType constructor.
     *
     * @param Session $customerSession
     * @param Config $companyTypeConfig
     */
    public function __construct(
        Session $customerSession,
        Config $companyTypeConfig
    ) {
        $this->customerSession = $customerSession;
        $this->companyTypeConfig = $companyTypeConfig;
    }

    /**
     * Get current customer type
     *
     * @return string
     */
    public function getCustomerType()
    {
        $customer = $this->customerSession->getCustomer();
        return $this->companyTypeConfig->getCustomerCompanyType($customer);
    }

    /**
     * Check if current customer is wholesale
     *
     * @return bool
     */
    public function isWholesaleCustomer()
    {
        return $this->getCustomerType() === Config::WHOLESALE;
    }
}