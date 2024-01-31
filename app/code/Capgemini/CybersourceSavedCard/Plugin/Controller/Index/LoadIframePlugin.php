<?php
/**
 * Capgemini_Cybersource
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\CybersourceSavedCard\Plugin\Controller\Index;

use Capgemini\CompanyType\Model\Config;
use Capgemini\WholesaleAddress\ViewModel\WholesaleDetector;
use CyberSource\SecureAcceptance\Controller\Index\LoadIFrame;

/**
 * Plugin for \CyberSource\SecureAcceptance\Controller\Index\LoadIFrame
 */
class LoadIframePlugin
{
    /**
     * @var \Capgemini\CompanyType\Model\Config
     */
    protected $config;

    /**
     * @param \Capgemini\CompanyType\Model\Config $config
     */
    public function __construct(\Capgemini\CompanyType\Model\Config $config)
    {
        $this->config = $config;
    }

    /**
     * Force enable vault for wholesale customers
     *
     * @param LoadIFrame $subject
     * @return array
     */
    public function beforeExecute(LoadIFrame $subject): array
    {
        if ($this->config->getCustomerCompanyType() === Config::WHOLESALE) {
            $params = $subject->getRequest()->getParams();
            $params['vaultIsEnabled'] = 1;
            $subject->getRequest()->setParams($params);
        }
        return [];
    }
}
