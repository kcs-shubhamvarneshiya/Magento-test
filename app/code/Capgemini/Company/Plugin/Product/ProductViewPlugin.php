<?php
/**
 * Capgemini_Company
 *
 * @category  Capgemini
 * @package   Capgemini_Company
 * @author    Tanya Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */
namespace Capgemini\Company\Plugin\Product;

use Capgemini\Company\Observer\CustomerLogin;
use Magento\Catalog\Controller\Product\View as ProductViewController;
use Magento\Customer\Model\Session;
use Magento\Framework\Stdlib\Cookie\PublicCookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

/**
 * Class ProductViewPlugin
 * @package Capgemini\Company\Plugin\Product
 */
class ProductViewPlugin
{
    /**
     * @var Session
     */
    protected $session;

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
     * @param CookieManagerInterface $cookieManager
     * @param PublicCookieMetadataFactory $publicCookieMetadataFactory
     */
    public function __construct(
        Session $session,
        CookieManagerInterface $cookieManager,
        PublicCookieMetadataFactory $publicCookieMetadataFactory
    ) {
        $this->session = $session;
        $this->cookieManager = $cookieManager;
        $this->publicCookieMetadataFactory = $publicCookieMetadataFactory;
    }

    /**
     * @param ProductViewController $subject
     * @return void
     */
    public function beforeExecute(ProductViewController $subject)
    {
        $customer = $this->session->getCustomer();
        $id = $customer->getId();
        if (!$id) {
            try {
                $meta = $this->publicCookieMetadataFactory->create();
                $meta->setPath('/');
                $this->cookieManager->setPublicCookie(CustomerLogin::COOKIE_NAME, '', $meta);
            } catch (\Exception $e) {
            }
        }
    }
}
