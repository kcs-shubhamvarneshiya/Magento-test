<?php

namespace Capgemini\DataLayer\Observer\Sessid;

use Capgemini\DataLayer\Observer\Sessid;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;

class ObserveSessid implements ObserverInterface
{
    const SESSION_COOKIE_NAME = 'PHPSESSID';
    const CONTROL_COOKIE_NAME = 'control-sessid-presence';
    const ACTION_LOG_IN = 'action_log_in';
    const ACTION_LOG_OUT = 'action_log_out';
    const ACTION_NON_LOG = 'action_non_log';
    const COOKIE_ACTION_ERROR_MESSAGE_PATTERN = 'Could not %s "' . self::CONTROL_COOKIE_NAME . '" due to: "%s".';

    /**
     * @var string
     */
    protected static $actionStatus = self::ACTION_NON_LOG;
    /**
     * @var CookieManagerInterface
     */
    protected $cookieManager;
    /**
     * @var CookieMetadataFactory
     */
    protected $cookieMetadataFactory;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    public static function setActionStatus($actionStatus)
    {
        self::$actionStatus = $actionStatus;
    }

    public function __construct(
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory  $cookieMetadataFactory,
        LoggerInterface        $logger
    )
    {
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        if (!$observer->getData('response')->headersSent()) {
            switch (self::$actionStatus) {
                case self::ACTION_NON_LOG:
                    $this->setControlCookie();
                    break;
                case self::ACTION_LOG_IN:
                    if ($this->cookieManager->getCookie(self::CONTROL_COOKIE_NAME)) {
                        $this->deleteControlCookie();
                    }
                    break;
                case self::ACTION_LOG_OUT:
                    break;
                default:
                    $this->logger->error(__METHOD__ . ': Invalid Action Status!');
            }
        }
    }

    private function setControlCookie()
    {
        $metadata = $this->cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setPath('/')
            ->setHttpOnly(false);
        try {
            $this->cookieManager->setPublicCookie(
                self::CONTROL_COOKIE_NAME,
                '0',
                $metadata
            );
        } catch (\Exception $exception) {
            $this->logCookieActionError('set', $exception);
        }
    }

    private function deleteControlCookie()
    {
        $metadata = $this->cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setPath('/');
        try {
            $this->cookieManager->deleteCookie(self::CONTROL_COOKIE_NAME, $metadata);
        } catch (\Exception $exception) {
            $this->logCookieActionError('delete', $exception);
        }
    }

    private function logCookieActionError(string $action, \Exception $exception)
    {
        $this->logger->error(
            sprintf(
                self::COOKIE_ACTION_ERROR_MESSAGE_PATTERN,
                $action,
                $exception->getMessage()
            )
        );
    }
}
