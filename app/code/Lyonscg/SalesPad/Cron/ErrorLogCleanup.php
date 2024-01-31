<?php


namespace Lyonscg\SalesPad\Cron;

use Lyonscg\SalesPad\Model\Config;
use Lyonscg\SalesPad\Api\ErrorLogRepositoryInterface;
use Lyonscg\SalesPad\Model\Api\Logger;

class ErrorLogCleanup
{
    /**
     * @var Config
     */
    protected $config;

    protected $errorLogRepository;

    protected $logger;

    public function __construct(
        Config $config,
        ErrorLogRepositoryInterface $errorLogRepository,
        Logger $logger
    ) {
        $this->config = $config;
        $this->errorLogRepository = $errorLogRepository;
        $this->logger = $logger;
    }

    public function execute()
    {
        $errorLogLifetime = intval($this->config->getErrorLogLifetime());
        if (!$errorLogLifetime) {
            return;
        }

        try {
            $this->errorLogRepository->deleteOldEntries($errorLogLifetime);
        } catch (\Throwable $e) {
            $this->logger->debug('Exception when deleting old ErrorLog entries: ' . $e->getMessage());
        }
    }
}
