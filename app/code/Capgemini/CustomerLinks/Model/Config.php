<?php
/**
 * Capgemini_CustomerLinks
 */
namespace Capgemini\CustomerLinks\Model;

use Capgemini\CompanyType\Model\Config as CompanyConfig;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * CustomerLinks configuration
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
     * Get exclude links based on customer type
     *
     * @return array
     */
    public function getExcludeLinks() : array
    {
        $customerType = $this->companyConfig->getCustomerCompanyType();

        $links = $this->getExcludeLinksRetail();

        if ($customerType === CompanyConfig::WHOLESALE) {
            $links = $this->getExcludeLinksWholesale();
        }
        if ($customerType === CompanyConfig::TRADE) {
            $links = $this->getExcludeLinksTrade();
        }

        if (empty($links)) {
            return [];
        }
        return explode(',', $links);
    }

    /**
     * Get excluded retail paths
     */
    protected function getExcludeLinksRetail()
    {
        return $this->getConfig('exclude_links_retail');
    }

    /**
     * Get excluded retail paths
     */
    protected function getExcludeLinksTrade()
    {
        return $this->getConfig('exclude_links_trade');
    }

    /**
     * Get excluded retail paths
     */
    protected function getExcludeLinksWholesale()
    {
        return $this->getConfig('exclude_links_wholesale');
    }

    /**
     * Get customer_type_links config value by key
     *
     * @param $key
     * @return mixed
     */
    protected function getConfig($key)
    {
        return $this->getConfigValue('customer_type_links/general/' . $key);
    }

    /**
     * Get scope config value for current store
     *
     * @param $path
     * @return mixed
     */
    protected function getConfigValue($path)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }
}
