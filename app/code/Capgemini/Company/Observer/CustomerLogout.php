<?php

namespace Capgemini\Company\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Stdlib\Cookie\PublicCookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

class CustomerLogout implements ObserverInterface
{
    /**
     * @var CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var PublicCookieMetadataFactory
     */
    protected $publicCookieMetadataFactory;

    /**
     * CustomerLogout constructor.
     * @param CookieManagerInterface $cookieManager
     * @param PublicCookieMetadataFactory $publicCookieMetadataFactory
     */
    public function __construct(
        CookieManagerInterface $cookieManager,
        PublicCookieMetadataFactory $publicCookieMetadataFactory
    ) {
        $this->cookieManager = $cookieManager;
        $this->publicCookieMetadataFactory = $publicCookieMetadataFactory;
    }
    /**
     * @param EventObserver $observer
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function execute(EventObserver $observer)
    {
        try {
            $meta = $this->publicCookieMetadataFactory->create();
            $meta->setPath('/');
            $this->cookieManager->setPublicCookie(CustomerLogin::COOKIE_NAME, '', $meta);
        } catch (\Exception $e) {
            // ignore
        }
    }
}
