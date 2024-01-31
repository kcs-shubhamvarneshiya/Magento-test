<?php
/**
 * Capgemini_CompanyType
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\CompanyType\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

/**
 * Config provider for checkout
 */
class CheckoutConfigProvider implements ConfigProviderInterface
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return [
            'isWholesaleCustomer' => $this->config->getCustomerCompanyType() === Config::WHOLESALE
        ];
    }
}
