<?php
/**
 * Capgemini_Company
 *
 * @category  Capgemini
 * @package   Capgemini_Company
 * @author    Tanya Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */
namespace Capgemini\Company\Plugin\Checkout;

use Capgemini\Company\Observer\CustomerLogin;
use Magento\Checkout\Controller\Cart\Index as CartIndexController;
use Magento\Company\Api\CompanyManagementInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Stdlib\Cookie\PublicCookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

/**
 * Class CartPlugin
 * @package Capgemini\Company\Plugin\Checkout
 */
class CartPlugin
{
    /**
     * @var Session
     */
    protected $session;

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
     * CompanyPlugin constructor.
     * @param Session $session
     * @param CompanyManagementInterface $companyManagement
     * @param CookieManagerInterface $cookieManager
     * @param PublicCookieMetadataFactory $publicCookieMetadataFactory
     */
    public function __construct(
        Session $session,
        CompanyManagementInterface $companyManagement,
        CookieManagerInterface $cookieManager,
        PublicCookieMetadataFactory $publicCookieMetadataFactory
    ) {
        $this->session = $session;
        $this->companyManagement = $companyManagement;
        $this->cookieManager = $cookieManager;
        $this->publicCookieMetadataFactory = $publicCookieMetadataFactory;
    }

    /**
     * @param CartIndexController $subject
     * @return void
     */
    public function beforeExecute(CartIndexController $subject)
    {
        $isTradeCustomer = true;
        $customer = $this->session->getCustomer();
        $id = $customer->getId();
        if (!$id) {
            $isTradeCustomer = false;
        }
        if ($isTradeCustomer) {
            $company = $this->companyManagement->getByCustomerId($id);
            $isTradeCustomer = ($company) ? true : false;
        }
        if (!$isTradeCustomer) {
            try {
                $meta = $this->publicCookieMetadataFactory->create();
                $meta->setPath('/');
                $this->cookieManager->setPublicCookie(CustomerLogin::COOKIE_NAME, '', $meta);
            } catch (\Exception $e) {
            }
        }
    }
}
