<?php
/**
 * Capgemini_Payfabric
 *
 * @category   Capgemini
 * @author    Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2020 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\Payfabric\Logger;

use Monolog\Logger as MonoLogger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = MonoLogger::DEBUG;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/payfabric.log';
}
