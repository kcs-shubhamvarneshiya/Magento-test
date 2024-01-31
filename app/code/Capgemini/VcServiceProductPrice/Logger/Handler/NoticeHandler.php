<?php
/**
 * Capgemini_VcServiceProductPrice
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_VcServiceProductPrice
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */

declare(strict_types=1);

namespace Capgemini\VcServiceProductPrice\Logger\Handler;

use Magento\Framework\Logger\Handler\Base as BaseHandler;
use Monolog\Logger as MonologLogger;

/**
 * class NoticeHandler
 */
class NoticeHandler extends BaseHandler
{
    /**
     * Logging level
     *
     * @var int
     */
    protected $loggerType = MonologLogger::NOTICE;

    /**
     * File name
     *
     * @var string
     */
    protected $fileName = '/var/log/vc_service_price.log';
}
