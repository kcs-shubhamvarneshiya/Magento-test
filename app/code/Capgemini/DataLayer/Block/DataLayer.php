<?php
/**
 * @category     Capgemini
 * @package      Capgemini_DataLayer
 */

namespace Capgemini\DataLayer\Block;

use Capgemini\ServerSideAnalytics\Model\GAClient;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Theme\Block\Html\Header\Logo;
use Magento\Company\Api\CompanyManagementInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Helper\Data;

class DataLayer extends Template
{
    const AJAX_URL = 'capgemini_datalayer/ajax/customerData';

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
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * DataLayer constructor.
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param CategoryRepositoryInterface $categoryRepository
     * @param CompanyManagementInterface $companyRepository
     * @param \Magento\Customer\Model\SessionFactory $sessionFactory
     * @param CollectionFactory $productCollectionFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param Data $catalogHelper
     * @param Logo $logo
     * @param Template\Context $context
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Capgemini\DataLayer\Helper\Data $helper
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        \Magento\Checkout\Model\Session $checkoutSession,
        CategoryRepositoryInterface $categoryRepository,
        CompanyManagementInterface $companyRepository,
        \Magento\Customer\Model\SessionFactory $sessionFactory,
        CollectionFactory $productCollectionFactory,
        ScopeConfigInterface $scopeConfig,
        Data $catalogHelper,
        Logo $logo,
        Template\Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Checkout\Model\Cart $cart,
        \Capgemini\DataLayer\Helper\Data $helper,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->serializer = $serializer;
        $this->checkoutSession = $checkoutSession;
        $this->categoryRepository = $categoryRepository;
        $this->logo = $logo;
        $this->companyRepository = $companyRepository;
        $this->customerSession = $sessionFactory->create();
        $this->productCollectionFactory = $productCollectionFactory;
        $this->catalogHelper = $catalogHelper;
        $this->request = $request;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->cart = $cart;
        $this->helper = $helper;
        $this->productRepository = $productRepository;
        $this->scopeConfig = $scopeConfig;

        parent::__construct($context, $data);
    }

    protected function _beforeToHtml()
    {
        if ($this->isHomePage()) {
            $this->setTemplate('home.phtml');
            return parent::_beforeToHtml();
        }

        if($this->isProductPage()) {
            $this->setTemplate('product.phtml');
            return parent::_beforeToHtml();
        }

        if ($this->isCategoryPage()) {
            $this->setTemplate('category.phtml');
            return parent::_beforeToHtml();
        }

        if ($this->isCheckoutCartPage()) {
            $this->setTemplate('cart.phtml');
            return parent::_beforeToHtml();
        }

        if ($this->isCheckoutIndexPage()) {
            $this->setTemplate('checkout.phtml');
            return parent::_beforeToHtml();
        }

        if ($this->isCheckoutSuccessPage()) {
            $this->setTemplate('confirmation.phtml');
            return parent::_beforeToHtml();
        }

        if ($this->isSearchResult()) {
            $this->setTemplate('searchresult.phtml');
            return parent::_beforeToHtml();
        }

        $this->setTemplate('other.phtml');
        return parent::_beforeToHtml();
    }

    /**
     * @return int|null
     */
    public function isLoggedIn()
    {
        if ($this->customerSession->isLoggedIn()) {
            return $this->customerSession->getId();
        } else {
            return 0;
        }
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentUrl()
    {
        return $this->_storeManager->getStore()->getCurrentUrl();
    }

    /**
     * @return bool
     */
    public function isHomePage()
    {
        return  $this->logo->isHomePage();
    }

    /**
     * @return bool|string
     */
    public function getHomePageData()
    {
        $homePageData =  $this->helper->getCustomerData();

        return $this->serializer->serialize($homePageData);
    }

    public function getCategoryPageData()
    {
        $productListBlock  = $this->getLayout()->getBlock('category.products.list');
        $productCollection = [];
        if (is_object($productListBlock)) {
            $productCollection = $productListBlock->getLoadedProductCollection();
        }
        $categoryBreadcrumbs  = $this->catalogHelper->getBreadcrumbPath();
        $categoryBreadcrumbsArray = [];
        foreach ($categoryBreadcrumbs as $breadcrumb) {
            $categoryBreadcrumbsArray[] = $breadcrumb['label'];
        }
        $categoryBreadcrumbs = implode('|', $categoryBreadcrumbsArray);
        $categoryProducts = [];
        $categoryIds = [];
        foreach ($productCollection as $product) {
            $categoryIds = array_merge($categoryIds, array_slice($product->getCategoryIds(), 5));
        }
        $categories = $this->getProductsCategories($categoryIds);
        foreach ($productCollection  as $product) {
            $productName = str_replace(["'","''",'|', '"','""',';'], '', $product->getName());
            $categoryNames = [];
            foreach ($product->getCategoryIds() as $categoryId) {
                $category = $categories[$categoryId] ?? [];
                if (!empty($category)) {
                    $categoryNames[] = str_replace("'", " ", $category->getName());
                    if (count($categoryNames) >= 5) {
                        break;
                    }
                }
            }
            $categoryProducts[$product->getId()] = [
                'name' => $productName,
                'id' => $product->getId(),
                'sku' => $product->getSku(),
                'category' => implode("/", $categoryNames),
                'position' => $product->getCatIndexPosition(),
                'list' => $categoryBreadcrumbs,
                'brand' =>  $product->getAttributeText('brand') ?? ''
            ];
        }

        $categoryPageData =  $this->helper->getCustomerData();
        $categoryPageData['products'] = $categoryProducts;
        return $this->serializer->serialize($categoryPageData);
    }

    public function getCategoryPageImpressions()
    {
        $productListBlock = $this->getLayout()->getBlock('category.products.list');
        $productCollection = [];
        if (is_object($productListBlock)) {
            $productCollection = $productListBlock->getLoadedProductCollection();
        }
        $categoryBreadcrumbs  = $this->catalogHelper->getBreadcrumbPath();
        $categoryBreadcrumbsArray = [];
        foreach ($categoryBreadcrumbs as $breadcrumb) {
            $categoryBreadcrumbsArray[] = $breadcrumb['label'];
        }
        $categoryBreadcrumbs = implode('|', $categoryBreadcrumbsArray);
        $categoryProducts = [];
        foreach ($productCollection  as $product) {
            $productName = str_replace(["'","''",'|', '"','""',';'], '', $product->getName());
            $categoryProducts[] = [
                'name' => $productName,
                'id' => $product->getSku(),
                'position' => $product->getCatIndexPosition(),
                'list' => $categoryBreadcrumbs,
                'brand' => $product->getAttributeText('brand') ?? ''
            ];
        }

        return $this->serializer->serialize($categoryProducts);
    }

    /**
     * Get Search Result Products Data
     *
     * @return array
     */
    public function getSearchPageImpressions()
    {
        $searchListBlock = $this->getLayout()->getBlock('search_result_list');
        $productCollection = [];
        if (is_object($searchListBlock)) {
            $productCollection = $searchListBlock->getLoadedProductCollection();
        }
        $categoryBreadcrumbs  = $this->catalogHelper->getBreadcrumbPath();
        $categoryBreadcrumbsArray = [];
        foreach ($categoryBreadcrumbs as $breadcrumb) {
            $categoryBreadcrumbsArray[] = $breadcrumb['label'];
        }
        $categoryBreadcrumbs = implode('|', $categoryBreadcrumbsArray);
        $products = [];
        foreach ($productCollection  as $product) {
            $productName = str_replace(["'","''",'|', '"','""',';'], '', $product->getName());
            $products[] = [
                'name' => $productName,
                'id' => $product->getSku(),
                'position' => $product->getCatIndexPosition(),
                'list' => $categoryBreadcrumbs,
                'brand' => $product->getAttributeText('brand') ?? ''
            ];
        }
        
        return $this->serializer->serialize($products);
    }

    /**
     * Get current store currency code
     *
     * @return string
     */
    protected function getCurrentCurrencyCode()
    {
        return $this->_storeManager->getStore()->getCurrentCurrencyCode();
    }

    /**
     * @param Customer $customer
     * @return CompanyInterface
     */
    protected function getCompanyForCustomer($customerId)
    {
        return $this->companyRepository->getByCustomerId($customerId);
    }

    /**
     * @return bool
     */
    public function isCategoryPage()
    {
        if ($this->_request->getFullActionName() == 'catalog_category_view') {
            return true;
        } elseif($this->_request->getFullActionName() == 'capgemini_bloomreach_category_proxy_category_view'){
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
    public function isCheckoutIndexPage()
    {
        if ($this->_request->getFullActionName() == 'checkout_index_index') {
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
    public function isSearchResult()
    {
        if ($this->_request->getFullActionName() == 'capgemini_bloomreach_search_proxy_result_index') {
            return true;
        } elseif($this->_request->getFullActionName() == 'search_result_index'){
            return true;
        }

        return false;
    }

    /**
     * @return bool|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductPageData()
    {
        $product = $this->registry->registry('product');
        $price = $product->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue();
        $finalPrice = $product->getFinalPrice();
        if (!$price) {
            $price = $finalPrice;
        }
        $data =  $this->helper->getCustomerData();
        $data['name'] = str_replace(["'",'"',',',';','|'], '', $product->getName());
        $data['id'] = $product->getId();
        $data['sku'] = $product->getSku();
        $data['price'] = round($finalPrice, 2);
        $data['category'] = $this->getProductCategories($product);
        $data['fullPrice'] = round($price, 2);
        $data['list'] = $this->getCategoryFrom();
        $data['images'] =  $product->getMediaGalleryImages()->count();
        $data['brand'] =  $this->helper->getProductBrand($product);
        $data['style'] =  $this->helper->getProductStyle($product);

        return $this->serializer->serialize($data);
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return array|string|null
     */
    protected function getProductStyle(\Magento\Catalog\Model\Product $product) {
        $this->helper->getProductStyle($product);
    }

    /**
     * @return string|string[]
     */
    protected function getCategoryFrom()
    {
        $category = $this->registry->registry('current_category');
        if (empty($category)) {
            return '';
        }

        return str_replace('/', '|', $category->getUrlPath() ?? '');
    }

    /**
     * @param $product
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getProductsCategories($product)
    {
        $categoriesCollection = $this->categoryCollectionFactory->create();
        $categories = [];
        if (count($product) > 0) {
            $categories = $categoriesCollection
                ->addAttributeToFilter('entity_id', ['in' => $product])
                ->addAttributeToSelect(['name'])
                ->getItems();
        }
        return $categories;
    }

    /**
     * @param $product
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getProductCategories($product)
    {
        $categoriesCollection = $this->categoryCollectionFactory->create();
        $categoriesData = [];
        if (count($product->getCategoryIds()) > 0) {
            $categories = $categoriesCollection
                ->addAttributeToFilter('entity_id', ['in' => $product->getCategoryIds()])
                ->addAttributeToSelect(['name']);

            foreach ($categories as $category) {
                $categoriesData[] = str_replace("'", " ", $category->getName());

                if (count($categoriesData) >= 5) {
                    break;
                }
            }
            $categoriesData = implode("/", $categoriesData);
        }
        return $categoriesData;
    }

    /**
     * @return bool|string
     * @throws \Magento\Framework\Exception\InvalidArgumentException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function getCheckoutPageData()
    {
        $data =  $this->helper->getCustomerData();

        $products = [];
        foreach ($this->cart->getQuote()->getAllItems() as $item) {
            if ($item->getHasChildren()) {

                continue;
            }

            $productData = [];
            $productData['name'] = str_replace(["'",'"',',',';','|'], '', $item->getProduct()->getName());
            $productData['id'] = $item->getProduct()->getSku();
            $productData['price'] = round($this->helper->calculateItemUnitPrice($item), 2);
            $productData['quantity'] = ($qtyItem = $item->getParentItem()) ? $qtyItem->getQty() : $item->getQty();
            $productData['category'] = $this->getProductCategories($item->getProduct());
            $productData['fullPrice'] = round($item->getProduct()->getPrice(), 2);
            $productData['olapic'] = true;
            $productData['brand'] =  $this->helper->getProductBrand($item->getProduct());

            $products[] = $productData;
        }

        $data['products'] = $products;

        return $this->serializer->serialize($data);
    }

    /**
     * @return bool|string
     * @throws \Magento\Framework\Exception\InvalidArgumentException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function getCheckoutSuccessPageData()
    {
        $order = $this->checkoutSession->getLastRealOrder();
        $revenue = $order->getBaseTotalDue() - $order->getShippingAmount() - $order->getTaxAmount();
        $data =  $this->helper->getCustomerData();
        $data['id'] = $order->getIncrementId();
        if((float)$revenue > 0){
            $data['revenue'] = $revenue;
        } else {
            $data['revenue'] = $order->getSubTotal();
        }
        if((float)$order->getBaseTotalDue() > 0){
            $data['salesRevenue'] = $order->getBaseTotalDue();
        } else {
            $data['salesRevenue'] = $order->getGrandTotal();
        }
        $data['tax'] = $order->getTaxAmount();
        $data['shipping'] = $order->getShippingAmount();
        $data['coupon'] = $order->getCouponCode();
        $data['couponAmount'] = $order->getDiscountAmount();
        $data['shippingOption'] = $order->getShippingMethod();
        $data['orderQuantity'] = $order->getTotalQtyOrdered();
        $data['event_id'] = 'order.' . $order->getIncrementId();
        $data['userEmail'] = $data['userEmail'] ?: $order->getCustomerEmail();

        $products = [];
        foreach ($order->getAllItems() as $item) {
            if ($item->getHasChildren()) {

                continue;
            }

            $brand = $this->helper->getProductBrand($item->getProduct());

            $productData= [];
            $productData['name'] = str_replace(["'",'"',',',';','|'], '', $item->getProduct()->getName());
            $productData['id'] = $item->getProduct()->getSku();
            $productData['price'] = round($this->helper->calculateItemUnitPrice($item), 2);
            $productData['quantity'] = ($qtyItem = $item->getParentItem()) ? $qtyItem->getQtyOrdered() : $item->getQtyOrdered();
            $productData['coupon'] = $order->getCouponCode();
            $productData['couponAmount'] = $item->getDiscountAmount();
            $productData['category'] = $this->getProductCategories($item->getProduct());
            $productData['fullPrice'] = round($item->getProduct()->getPrice(), 2);
            $productData['olapic'] = true;
            $productData['brand'] =  $brand;
            $productData['style'] =  $this->helper->getProductStyle($item->getProduct());

            $products[] = $productData;
        }

        $data['products'] = $products;

        return $this->serializer->serialize($data);
    }

    /**
     * @return bool|string
     */
    public function getOtherPageData()
    {
        return $this->serializer->serialize($this->helper->getCustomerData());
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

        if ($this->isCheckoutCartPage()) {
            return 'cart';
        }

        if ($this->isCheckoutIndexPage()) {
            return 'checkout';
        }

        if ($this->isCheckoutSuccessPage()) {
            return 'order_confirmation';
        }

        if ($this->isSearchResult()) {
            return 'search_result_page';
        }

        return 'other';
    }

    public function getAjaxUrl()
    {
        return $this->getUrl(self::AJAX_URL);
    }
}
