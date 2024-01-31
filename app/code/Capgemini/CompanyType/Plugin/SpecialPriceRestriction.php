<?php
/**
 * Capgemini_CompanyType
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\CompanyType\Plugin;

use Capgemini\CompanyType\Model\Config;
use Magento\Catalog\Pricing\Render\FinalPriceBox;

/**
 * A plugin to restrict special price rendering for Wholesale customers.
 */
class SpecialPriceRestriction
{
    protected \Capgemini\CompanyType\Model\Config $config;

    /**
     * @param \Capgemini\CompanyType\Model\Config $config
     */
    public function __construct(\Capgemini\CompanyType\Model\Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param FinalPriceBox $subject
     * @param bool $result
     * @return bool
     */
    public function afterHasSpecialPrice(FinalPriceBox $subject, bool $result): bool
    {
        if ($this->config->getCustomerCompanyType() == Config::WHOLESALE) {
            $result = false;
        }
        return $result;
    }
}
