<?php

namespace Capgemini\DataLayer\Helper;

use Exception;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\Helper\Context;
use Magento\Company\Api\CompanyManagementInterface;
use Magento\Catalog\Helper\Data as CatalogHelper;
use Magento\Framework\Exception\InvalidArgumentException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Registry;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Sales\Model\Order\Item as OrderItem;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var
     */
    protected $customerSession;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var CompanyManagementInterface
     */
    protected $companyRepository;

    /**
     * @var CatalogHelper
     */
    protected $catalogHelper;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * Data constructor.
     * @param Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Magento\Customer\Model\SessionFactory $sessionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param CompanyManagementInterface $companyRepository
     */
    public function __construct(
        Context $context,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Customer\Model\SessionFactory $sessionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        CompanyManagementInterface $companyRepository,
        CatalogHelper $catalogHeper,
        Registry $registry
    )
    {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->customerSession = $sessionFactory->create();
        $this->storeManager = $storeManager;
        $this->companyRepository = $companyRepository;
        $this->catalogHelper = $catalogHeper;
        $this->registry = $registry;
        parent::__construct($context);
    }

    /**
     * @param $product
     * @return array
     */
    public function getProductCategories($product)
    {
        $categoriesCollection = $this->categoryCollectionFactory->create();
        $categoriesData = [];
        if (count($product->getCategoryIds()) > 0) {
            $categories = $categoriesCollection
                ->addAttributeToFilter('entity_id', ['in' => $product->getCategoryIds()])
                ->addAttributeToSelect(['name']);

            foreach ($categories as $category) {
                $categoriesData[] = $category->getName();

                if (count($categoriesData) >= 5) {
                    break;
                }
            }
            $categoriesData = implode("/", $categoriesData);
        }
        if (is_array($categoriesData) && count($categoriesData) == 0) {
            return  '';
        } else {
            return $categoriesData;
        }
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCustomerData()
    {
        if ($this->customerSession->isLoggedIn()) {
            $customerEmail = $this->customerSession->getCustomer()->getEmail();
            $customerHashedEmail = hash('sha256', strtolower($this->customerSession->getCustomer()->getEmail()));
            $company = $this->getCompanyForCustomer($this->customerSession->getCustomerId());
            if ($company) {
                $tradeCustomer = '1';

                $customerClass = $company->getBusinessType();
            } else {
                $tradeCustomer = '0';
                $customerClass = '';
            }
        } else {
            $customerHashedEmail = '';
            $customerEmail = '';
            $tradeCustomer = '0';
            $customerClass = '';
        }
        return [
            'loggedinStatus' => (int)$this->customerSession->isLoggedIn(),
            'hashedEmail' => $customerHashedEmail,
            'userEmail' => $customerEmail,
            'currencyCode' => $this->getCurrentCurrencyCode(),
            'tradeCustomer' => $tradeCustomer,
            'customerClass' => $customerClass
        ];
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getCurrentCurrencyCode()
    {
        return $this->storeManager->getStore()->getCurrentCurrencyCode();
    }

    /**
     * @param $customerId
     * @return \Magento\Company\Api\Data\CompanyInterface
     */
    protected function getCompanyForCustomer($customerId)
    {
        return $this->companyRepository->getByCustomerId($customerId);
    }

    /**
     * @return array|string
     */
    public function getCategoryBreadcrubms()
    {
        $categoryBreadcrumbs  = $this->catalogHelper->getBreadcrumbPath();
        $categoryBreadcrumbsArray = [];
        foreach($categoryBreadcrumbs as $breadcrumb) {
            $categoryBreadcrumbsArray[] = $breadcrumb['label'];
        }
        $categoryBreadcrumbs = implode('|', $categoryBreadcrumbsArray);

        return $categoryBreadcrumbs;
    }


    /**
     * @return bool|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductPageData()
    {

        $product = $this->registry->registry('product');

        $data =  $this->getCustomerData();
        $data['name'] = str_replace(["'",'"',',',';','|'], '', $product->getName());
        $data['id'] = $product->getId();
        $data['price'] = number_format($product->getFinalPrice(), 2);
        $data['category'] = $this->getProductCategories($product);
        $data['fullPrice'] = number_format($product->getPrice(), 2);
        $data['list'] = $this->getCategoryFrom();
        $data['images'] =  $product->getMediaGalleryImages()->count();

        return $data;
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

        return str_replace('/', '|', $category->getUrlPath());
    }

    /**
     * @param $product
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function  getProductBrand($product)
    {
        if ($product->getTypeId() == 'configurable') {
            $childProducts = $product->getTypeInstance()->getUsedProducts($product);
            if (count($childProducts) > 0) {
                $childProduct = array_shift($childProducts);
                $brand = $childProduct->getAttributeText('brand');
                if (!empty($brand)) {
                    return $brand;
                } else {
                    return '';
                }
            }
        } elseif ($product->getTypeId() == 'simple' ) {
            $brand = $product->getAttributeText('brand');
            if (!empty($brand)) {
                return $brand;
            } else {
                return '';
            }
        }

        return '';
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return array|string|null
     */
    public function getProductStyle(\Magento\Catalog\Model\Product $product) {
        $style = $product->getStyle();
        if (!empty($style) && $style) {
            $productStyle = $product->getAttributeText('style');
            return (is_array($productStyle)) ?
                implode(',', $productStyle) : $productStyle;
        }
        return '';
    }

    /**
     * @param QuoteItem|OrderItem $item
     * @return float
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function calculateItemUnitPrice($item): float
    {
        $performCalculation = function ($item, $qtyCallBack) {
            $rowTotalAfterDiscount = $item->getRowTotal() - $item->getDiscountAmount();
            $qty = call_user_func([$item, $qtyCallBack]);

            return $rowTotalAfterDiscount / $qty;
        };

        if ($item instanceof QuoteItem) {
            $qtyCallBack = 'getQty';
        } elseif ($item instanceof OrderItem) {
            $qtyCallBack = 'getQtyOrdered';
        } else {

            throw new InvalidArgumentException(__('Only OrderItem an QuoteItem instances are excepted here.'));
        }

        if (!$parentItem = $item->getParentItem()) {

            return $performCalculation($item, $qtyCallBack);
        }

        $productType = $parentItem->getProduct()->getTypeId();
        switch ($productType) {
            case Configurable::TYPE_CODE:

                return $performCalculation($parentItem, $qtyCallBack);
            default:
                throw new Exception(
                    sprintf(
                        'No method exist for calculating unit price of the item based on its %s parent product.',
                        strtoupper($productType)
                    )
                );
        }
    }
}
