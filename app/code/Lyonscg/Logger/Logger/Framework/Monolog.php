<?php
/**
 * @category    Lyonscg
 * @package     Lyonscg_Logger
 * @copyright   Copyright (c) 2021 Lyons Consulting Group (www.lyonscg.com)
 * @author      Tanya Mamchik <tanya.mamchik@capgemini.com>
 */

namespace Lyonscg\Logger\Logger\Framework;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
use Monolog\Logger;

class Monolog extends \Magento\Framework\Logger\Monolog
{
    const XML_PATH_DEBUG_ENABLED = 'lyonscg_logger/general/debug_enabled';
    const XML_PATH_INFO_ENABLED = 'lyonscg_logger/general/info_enabled';
    const XML_PATH_NOTICE_ENABLED = 'lyonscg_logger/general/notice_enabled';
    const XML_PATH_WARNING_ENABLED = 'lyonscg_logger/general/warning_enabled';
    const XML_PATH_ERROR_ENABLED = 'lyonscg_logger/general/error_enabled';
    const XML_PATH_CRITICAL_ENABLED = 'lyonscg_logger/general/critical_enabled';
    const XML_PATH_ALERT_ENABLED = 'lyonscg_logger/general/alert_enabled';
    const XML_PATH_EMERGENCY_ENABLED = 'lyonscg_logger/general/emergency_enabled';
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var callable[]|State
     */
    private $mode;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        $name,
        ScopeConfigInterface $scopeConfig,
        State $appState,
        array $handlers = [],
        array $processors = []
    ) {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($name, $handlers, $processors);
        $this->mode = $appState->getMode();
    }

    /**
     * Adds a log record.
     *
     * @param integer $level The logging level
     * @param string $message The log message
     * @param array $context The log context
     * @return Boolean Whether the record has been processed
     */
    public function addRecord(int $level, string $message, array $context = [], \Monolog\DateTimeImmutable $datetime = null):bool
    {
        if ($this->isEnable($level) || $this->mode === State::MODE_PRODUCTION) {
            if ($message instanceof \Exception && !isset($context['exception'])) {
                $context['exception'] = $message;
            }

            $message = $message instanceof \Exception ? $message->getMessage() : $message;

            return parent::addRecord($level, $message, $context);
        }
        return true;
    }

    /**
     * @param integer $level
     * @return bool
     */
    public function isEnable($level):bool
    {
        switch ($level) {
            case Logger::DEBUG :
                return $this->scopeConfig->isSetFlag(self::XML_PATH_DEBUG_ENABLED);
            case Logger::INFO :
                return $this->scopeConfig->isSetFlag(self::XML_PATH_INFO_ENABLED);
            case Logger::NOTICE :
                return $this->scopeConfig->isSetFlag(self::XML_PATH_NOTICE_ENABLED);
            case Logger::WARNING :
                return $this->scopeConfig->isSetFlag(self::XML_PATH_WARNING_ENABLED);
            case Logger::ERROR :
                return $this->scopeConfig->isSetFlag(self::XML_PATH_ERROR_ENABLED);
            case Logger::CRITICAL :
                return $this->scopeConfig->isSetFlag(self::XML_PATH_CRITICAL_ENABLED);
            case Logger::ALERT :
                return $this->scopeConfig->isSetFlag(self::XML_PATH_ALERT_ENABLED);
            case Logger::EMERGENCY :
                return $this->scopeConfig->isSetFlag(self::XML_PATH_EMERGENCY_ENABLED);
        }
        return true;
    }
}
