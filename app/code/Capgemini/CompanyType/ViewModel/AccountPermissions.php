<?php
/**
 * Capgemini_CompanyType
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\CompanyType\ViewModel;

use Capgemini\CompanyType\Model\Config;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Account permissions model
 */
class AccountPermissions implements ArgumentInterface
{
    /**
     * @var CustomerSession
     */
    protected CustomerSession $customerSession;
    /**
     * @var Config
     */
    protected Config $companyTypeConfig;

    /**
     * @param CustomerSession $customerSession
     * @param Config $companyTypeConfig
     */
    public function __construct(
        CustomerSession $customerSession,
        Config $companyTypeConfig
    ) {
        $this->companyTypeConfig = $companyTypeConfig;
        $this->customerSession = $customerSession;
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
     * Check if customer can change email
     *
     * @return bool
     */
    public function canChangeEmail()
    {
        return $this->getCustomerType() !== Config::WHOLESALE;
    }

    /**
     * Check if customer can change name
     *
     * @return bool
     */
    public function canChangeName()
    {
        return $this->getCustomerType() !== Config::WHOLESALE;
    }
}
