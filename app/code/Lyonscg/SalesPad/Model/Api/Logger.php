<?php

namespace Lyonscg\SalesPad\Model\Api;

use Lyonscg\SalesPad\Model\Config;
use Psr\Log\LoggerInterface;

class Logger
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var bool|null
     */
    protected $loggingEnabled;

    /**
     * Logger constructor.
     * @param LoggerInterface $logger
     * @param Config $config
     */
    public function __construct(
        LoggerInterface $logger,
        Config $config
    ) {
        $this->logger = $logger;
        $this->config = $config;
        $this->loggingEnabled = null;
    }

    /**
     * @param $data
     * @param null $forceDebug
     */
    public function debug($data, $forceDebug = null)
    {
        if ($this->isLoggingEnabled() || $forceDebug) {
            if (is_array($data)) {
                $this->logger->debug(str_replace("\n", "\n  ", var_export($data, true)));
            } else {
                $this->logger->debug($data);
            }
        }
    }

    /**
     * @return bool|null
     */
    public function isLoggingEnabled()
    {
        if ($this->loggingEnabled === null) {
            $this->loggingEnabled = $this->config->isLoggingEnabled();
        }
        return $this->loggingEnabled;
    }
}
