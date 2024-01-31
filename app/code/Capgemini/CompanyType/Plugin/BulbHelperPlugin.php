<?php
/**
 * Capgemini_CompanyType
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\CompanyType\Plugin;

use Capgemini\CompanyType\Model\Config;
use Capgemini\LightBulbs\Helper\Data;

/**
 * A plugin to restrict bulb sale for wholesale customers.
 */
class BulbHelperPlugin
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

    /**
     * @param Data $subject
     * @param bool $result
     * @return bool
     */
    public function afterCanSellBulbs(Data $subject, bool $result): bool
    {
        if ($this->config->getCustomerCompanyType() == Config::WHOLESALE) {
            $result = false;
        }
        return $result;
    }
}
