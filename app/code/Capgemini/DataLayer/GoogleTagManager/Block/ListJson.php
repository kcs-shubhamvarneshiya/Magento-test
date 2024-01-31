<?php

namespace Capgemini\DataLayer\GoogleTagManager\Block;

use Magento\Catalog\Model\Category;

class ListJson extends \Magento\GoogleTagManager\Block\ListJson
{
    /**
     * @var \Capgemini\DataLayer\Helper\Data
     */
    protected $dataLayerHelper;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * ListJson constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\GoogleTagManager\Helper\Data $helper
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Checkout\Helper\Cart $checkoutCart
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Banner\Model\ResourceModel\Banner\CollectionFactory $bannerColFactory
     * @param \Magento\GoogleTagManager\Model\Banner\Collector $bannerCollector
     * @param \Capgemini\DataLayer\Helper\Data $dataLayerHelper
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\GoogleTagManager\Helper\Data $helper,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Helper\Cart $checkoutCart,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Banner\Model\ResourceModel\Banner\CollectionFactory $bannerColFactory,
        \Magento\GoogleTagManager\Model\Banner\Collector $bannerCollector,
        \Capgemini\DataLayer\Helper\Data $dataLayerHelper,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        array $data = []
    )
    {
        $this->dataLayerHelper = $dataLayerHelper;
        $this->productRepository = $productRepository;
        parent::__construct($context, $helper, $jsonHelper, $registry, $checkoutSession, $customerSession, $checkoutCart, $layerResolver, $moduleManager, $request, $bannerColFactory, $bannerCollector, $data);
    }

    /**
     * Format product item for output to json
     *
     * @param \Magento\Quote\Model\Quote\Item $item
     * @return array
     */
    protected function _formatProduct($item)
    {
        $product = [];
        $product['id'] = $item->getSku();
        $product['name'] = $item->getName();
        $product['price'] = $item->getPrice();
        $product['category'] = $this->dataLayerHelper->getProductCategories($item->getProduct());
        $product['qty'] = $item->getQty();
        $product['fullPrice'] = $item->getProduct()->getPrice();
        $product['olapic'] = true;
        $product['brand'] =  $this->dataLayerHelper->getProductBrand($item->getProduct());
        return $product;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCustomerData()
    {
        return $this->jsonHelper->jsonEncode($this->dataLayerHelper->getCustomerData());
    }

    /**
     * @param $product
     * @return array
     */
    public  function  getProductCategories($product)
    {
        return $this->dataLayerHelper->getProductCategories($product);
    }

    /**
     * @return array|string
     */
    public function getCategoryBreadcrubms()
    {
        return $this->dataLayerHelper->getCategoryBreadcrubms();
    }

    /**
     *
     */
    public function getProductPageData()
    {
        return $this->dataLayerHelper->getProductPageData();
    }
}
