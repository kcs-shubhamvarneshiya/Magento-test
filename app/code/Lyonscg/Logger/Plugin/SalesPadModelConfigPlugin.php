<?php


namespace Lyonscg\Logger\Plugin;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Lyonscg\Logger\Logger\Framework\Monolog;
use Magento\Framework\App\State;

/**
 * Class SalesPadModelConfigPlugin
 * @package Lyonscg\Logger\Plugin
 */
class SalesPadModelConfigPlugin
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var State
     */
    private $appState;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        State $appState
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->appState = $appState;
    }

    /**
     * @param \Lyonscg\SalesPad\Model\Config $subject
     * @param $result
     */
    public function afterIsLoggingEnabled(\Lyonscg\SalesPad\Model\Config $subject, $result)
    {
        $additionalCondition = $this->scopeConfig->isSetFlag(Monolog::XML_PATH_DEBUG_ENABLED) ||
                               $this->appState->getMode() === State::MODE_PRODUCTION;

        return $result && $additionalCondition;
    }
}
