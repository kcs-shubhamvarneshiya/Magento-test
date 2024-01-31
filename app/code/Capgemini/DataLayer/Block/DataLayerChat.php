<?php
/**
 * @category     Capgemini
 * @package      Capgemini_DataLayer
 */

namespace Capgemini\DataLayer\Block;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Theme\Block\Html\Header\Logo;
use Magento\Company\Api\CompanyManagementInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Helper\Data;

class DataLayerChat extends Template
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $serializer;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var Logo
     */
    protected $logo;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var CompanyManagementInterface
     */
    protected $companyRepository;

    /**
     * @var CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var Data
     */
    protected $catalogHelper;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;

    /**
     * @var \Capgemini\DataLayer\Helper\Data
     */
    protected $helper;

    protected $listProduct;

    /**
     * DataLayerChat constructor.
     * @param Logo $logo
     * @param Template\Context $context
     * @param \Magento\Framework\App\Request\Http $request
     * @param array $data
     */
    public function __construct(
        Logo $logo,
        Template\Context $context,
        \Magento\Framework\App\Request\Http $request,
        array $data = []
    ) {
        $this->logo = $logo;
        $this->request = $request;

        parent::__construct($context, $data);
    }

    /**
     * @return bool
     */
    public function isHomePage()
    {
        return  $this->logo->isHomePage();
    }

    /**
     * @return bool
     */
    public function isCategoryPage()
    {
        if ($this->_request->getFullActionName() == 'catalog_category_view') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isProductPage()
    {
        if ($this->_request->getFullActionName() == 'catalog_product_view') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isCheckoutCartPage()
    {
        if ($this->_request->getFullActionName() == 'checkout_cart_index') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isCheckoutSuccessPage()
    {
        if ($this->_request->getFullActionName() == 'checkout_onepage_success') {
            return true;
        }

        return false;
    }


    /**
     * @return bool
     */
    public function isCheckoutIndexPage()
    {
        if ($this->_request->getFullActionName() == 'checkout_index_index') {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getPageType()
    {
        if ($this->isHomePage()) {
            return 'homepage';
        }

        if ($this->isCategoryPage()) {
            return 'product_listing_page';
        }

        if ($this->isProductPage()) {
            return 'product_detail_page';
        }

        if ($this->isCheckoutCartPage() || $this->isCheckoutIndexPage()) {
            return 'checkout';
        }

        if ($this->isCheckoutSuccessPage()) {
            return 'order_confirmation';
        }

        return 'other';
    }
}
