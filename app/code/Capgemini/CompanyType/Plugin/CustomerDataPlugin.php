<?php
/**
 * Capgemini_CompanyType
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\CompanyType\Plugin;

use Magento\Customer\CustomerData\Customer;

/**
 * Customer data plugin to add company type.
 */
class CustomerDataPlugin
{
    /**
     * @var \Capgemini\CompanyType\Model\Config
     */
    protected $config;

    public function __construct(\Capgemini\CompanyType\Model\Config $config)
    {
        $this->config = $config;
    }

    /**
     * Add company type to result
     *
     * @param Customer $subject
     * @param array $result
     * @return array
     */
    public function afterGetSectionData(Customer $subject, array $result): array
    {
        $companyType = $this->config->getCustomerCompanyType();
        $result['companyType'] = $companyType;
        return $result;
    }
}
