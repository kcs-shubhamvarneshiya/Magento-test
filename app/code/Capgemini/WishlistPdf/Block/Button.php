<?php
/**
 * Capgemini_WishlistPdf
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\WishlistPdf\Block;

use Capgemini\CompanyType\Model\Config;
use Capgemini\WishlistPdf\Model\Source\PricingType;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template\Context;

/**
 * Print button block
 */
class Button extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Config
     */
    protected $customerTypeConfig;
    /**
     * @var Session
     */
    protected $customerSession;
    /**
     * @var PricingType
     */
    protected $pricingType;

    /**
     * Constructor
     *
     * @param Config $customerTypeConfig
     * @param Session $customerSession
     * @param PricingType $pricingType
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Config $customerTypeConfig,
        Session $customerSession,
        PricingType $pricingType,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerTypeConfig = $customerTypeConfig;
        $this->customerSession = $customerSession;
        $this->pricingType = $pricingType;
    }

    /**
     * Get customer Type
     *
     * @retrun string
     */
    public function getCustomerType()
    {
        $customer = $this->customerSession->getCustomer();
        return $this->customerTypeConfig->getCustomerCompanyType($customer);
    }

    /**
     * @return string
     */
    public function getPrintUrl()
    {
        $wishlistId = $this->getWishlistId();
        if ($wishlistId) {
            $params = ['wishlist_id' => $wishlistId];
        } else {
            $params = [];
        }
        return $this->getUrl('wishlist/pdf/index', $params);
    }

    /**
     * @retrun int | null
     */
    public function getWishlistId()
    {
        return $this->getRequest()->getParam('wishlist_id');
    }

    /**
     * @return array
     */
    public function getPriceTypes()
    {
        return $this->pricingType->toArray();
    }

    /**
     * @param int $type
     * @return bool
     */
    public function canShowPriceType($type)
    {
        if (in_array($type, [PricingType::TRADE_PRICING, PricingType::WITH_MARKUP_PRICE])) {
            $customerType = $this->getCustomerType();
            if (in_array($customerType, [Config::TRADE, Config::WHOLESALE])) {
                return true;
            }
        } else {
            return true;
        }
        return false;
    }
}