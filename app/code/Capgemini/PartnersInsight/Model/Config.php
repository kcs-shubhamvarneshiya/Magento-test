<?php
/**
 * Capgemini_PartnersInsight
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\PartnersInsight\Model;

use Capgemini\CompanyType\Model\Config as CompanyConfig;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Partners insight configuration
 */
class Config
{
    /**
     * @var CompanyConfig
     */
    protected $companyConfig;
    /**
     * @var Session
     */
    protected $customerSession;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var EncryptorInterface
     */
    protected $encryptor;

    /**
     * Constructor
     *
     * @param CompanyConfig $companyConfig
     * @param Session $customerSession
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        CompanyConfig $companyConfig,
        Session $customerSession,
        ScopeConfigInterface $scopeConfig,
        EncryptorInterface $encryptor
    ) {

        $this->companyConfig = $companyConfig;
        $this->customerSession = $customerSession;
        $this->scopeConfig = $scopeConfig;
        $this->encryptor = $encryptor;
    }

    /**
     * Check is Partners insight is enabled and available for the customer
     */
    public function isAllowed()
    {
        if ($this->getPiConfig('active')) {
            $customer = $this->customerSession->getCustomer();
            return $this->companyConfig->getCustomerCompanyType($customer) === \Capgemini\CompanyType\Model\Config::WHOLESALE;
        }
        return false;
    }

    /**
     * Get partners insight config value by key
     *
     * @param $key
     * @return mixed
     */
    public function getPiConfig($key)
    {
        $value = $this->getConfigValue('partners_insight/general/' . $key);
        if (in_array($key, ['api_x_api_key', 'api_mock_password'])) {
            $value = $this->encryptor->decrypt($value);
        }
        return $value;
    }

    /**
     * Get scope config value for current store
     *
     * @param $path
     * @return mixed
     */
    public function getConfigValue($path)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }
}