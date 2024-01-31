<?php
/**
 * Capgemini_Company
 *
 * @category  Capgemini
 * @package   Capgemini_Company
 * @author    Logan Montgomery <logan.montgomery@capgemini.com>
 * @author    Tanya Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */
namespace Capgemini\Company\Observer;

use Magento\Company\Api\CompanyManagementInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Stdlib\Cookie\PublicCookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class CustomerLogin
 * @package Capgemini\Company\Observer
 */
class CustomerLogin implements ObserverInterface
{
    const COOKIE_NAME = 'trade_account';
    /**
     * @var CompanyManagementInterface
     */
    protected $companyManagement;

    /**
     * @var CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var PublicCookieMetadataFactory
     */
    protected $publicCookieMetadataFactory;

    /**
     * @var Json
     */
    protected $serializer;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\Session\Config\ConfigInterface
     */
    private $sessionConfig;

    /**
     * CustomerLogin constructor.
     * @param CompanyManagementInterface $companyManagement
     * @param CookieManagerInterface $cookieManager
     * @param PublicCookieMetadataFactory $publicCookieMetadataFactory
     * @param Json $serializer
     * @param LoggerInterface $logger
     * @param \Magento\Framework\Session\Config\ConfigInterface $sessionConfig
     */
    public function __construct(
        CompanyManagementInterface $companyManagement,
        CookieManagerInterface $cookieManager,
        PublicCookieMetadataFactory $publicCookieMetadataFactory,
        Json $serializer,
        LoggerInterface $logger,
        \Magento\Framework\Session\Config\ConfigInterface $sessionConfig
    ) {
        $this->companyManagement = $companyManagement;
        $this->cookieManager = $cookieManager;
        $this->publicCookieMetadataFactory = $publicCookieMetadataFactory;
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->sessionConfig = $sessionConfig;
    }

    /**
     * @param EventObserver $observer
     */
    public function execute(EventObserver $observer)
    {
        /** @var \Magento\Customer\Model\Customer $customer */
        try {
            $customer = $observer->getEvent()->getCustomer();
            $id = $customer->getId();
            if (!$id) {
                return;
            }
            $company = $this->companyManagement->getByCustomerId($id);
            $meta = $this->publicCookieMetadataFactory->create();
            $meta->setPath('/');
            if ($company) {
                $meta->setDuration($this->sessionConfig->getCookieLifetime());
                $cookieData = [
                    'price_label' => strval(__('Trade'))
                ];
                try {
                    $this->cookieManager->setPublicCookie(self::COOKIE_NAME, $this->serializer->serialize($cookieData), $meta);
                } catch (\Exception $e) {
                    $this->logger->warning('Trade customer loggin warning: ' . $e->getMessage());
                }
            } else {
                try {
                    $this->cookieManager->setPublicCookie(CustomerLogin::COOKIE_NAME, '', $meta);
                } catch (\Exception $e) {
                    $this->logger->warning('Trade customer loggin warning: ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            $this->logger->warning('Trade customer loggin warning: ' . $e->getMessage());
        }
    }
}
