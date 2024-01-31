<?php

namespace Capgemini\OrderView\Block;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Customer\Model\Session as CustomerSession;
use Capgemini\OrderView\Helper\Data;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\CatalogInventory\Model\StockState;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\Timezone\LocalizedDateToUtcConverterInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Url\DecoderInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

class Inventory extends \Magento\Framework\View\Element\Template
{
    const SEARCH_PATH = 'orderview/inventory/index';

    /**
     * @var Data
     */
    protected Data $helper;

    /**
     * @var string
     */
    protected String $customerType;
    /**
     * @var StockState
     */
    protected StockState $stockItem;
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;
    private ProductRepositoryInterface $productRepository;
    protected StockItemRepository $_stockItemRepository;
    protected TimezoneInterface $timezone;
    /**
     * @var DecoderInterface
     */
    protected DecoderInterface $urlDecoder;
    /**
     * @var CustomerSession
     */
    protected CustomerSession $customerSession;
    /**
     * @var CustomerRegistry
     */
    private CustomerRegistry $customerRegistry;
    protected $customerSessionFactory;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var Configurable
     */
    protected $configurableType;
    protected \Magento\ConfigurableProduct\Model\Product\Type\Configurable $productTypeConfigurable;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param Data $helper
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockItem
     * @param ProductRepositoryInterface $productRepository
     * @param StoreManagerInterface $storeManager
     * @param StockItemRepository $stockItemRepository
     * @param TimezoneInterface $timezone
     * @param DecoderInterface $urlDecoder
     * @param CustomerSession $customerSession
     * @param CustomerRegistry $customerRegistry
     * @param LoggerInterface $logger
     * @param Configurable $configurableType
     * @param array $data
     */
    public function __construct(
        \Capgemini\RequestToOrder\Model\PermissionManager $permissionManager,
        \Capgemini\CompanyType\Model\Product\PurchasePermission $purchasePermission,
        \Magento\Framework\View\Element\Template\Context $context,
        Data $helper,
        \Magento\CatalogInventory\Api\StockStateInterface $stockItem,
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager,
        StockItemRepository $stockItemRepository,
        TimezoneInterface $timezone,
        DecoderInterface $urlDecoder,
        CustomerRegistry $customerRegistry,
        CustomerSession $customerSession,
        \Magento\Customer\Model\SessionFactory $customerSessionFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Psr\Log\LoggerInterface $logger,
        Configurable $configurableType,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $productTypeConfigurable,
        array $data = []
    ) {
        $this->productRepository = $productRepository;
        $this->helper = $helper;
        $this->customerType = "";
        $this->_stockItemRepository = $stockItemRepository;
        $this->stockItem = $stockItem;
        $this->timezone = $timezone;
        $this->storeManager = $storeManager;
        $this->urlDecoder = $urlDecoder;
        $this->permissionManager = $permissionManager;
        $this->purchasePermission = $purchasePermission;
        $this->customerSession = $customerSession;
        $this->customerRegistry = $customerRegistry;
        $this->_customerSessionFactory = $customerSessionFactory;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        $this->logger = $logger;
        $this->configurableType = $configurableType;
        $this->productTypeConfigurable = $productTypeConfigurable;
        parent::__construct($context, $data);
    }
    /**
     * @return string
     */
    public function getCustomerType():string
    {
        if ($this->customerType == "") {
            $this->customerType = $this->helper->getCustomerType();
        }
        return $this->customerType;
    }

    /**
     * @return string
     */
    public function getSearchUrl():string
    {
       return $this->getUrl(self::SEARCH_PATH);
    }
    public function getQty($sku)
    {
        $product = $this->productRepository->get($sku);
        return$this->stockItem->getStockQty($product->getId(), $product->getStore()->getWebsiteId());

    }
    public function getProductBySku($sku)
    {
        $product = null;
        try{
            $product = $this->productRepository->get($sku);
        }catch (NoSuchEntityException $noSuchEntityException)
        {
            $product = null;
        }
        return $product;
    }
    public function getStoreManager()
    {
        return $this->storeManager;
    }
    public function getStockItem($productId)
    {
        return $this->_stockItemRepository->get($productId);
    }
    public function getAvailableDate($product)
    {
        /**
         * @var $product \Magento\Catalog\Api\Data\ProductInterface
         */
        $availableDate = "NA";

        if($product->getCustomAttribute('availability_message'))
        {
            $availabilityMessageValue = $product->getCustomAttribute('availability_message')->getValue();
            if($availabilityMessageValue != null)
            {
                $availabilityMessageArray =  explode(",",$availabilityMessageValue);
                if(!empty($availabilityMessageArray) && is_array($availabilityMessageArray) && isset($availabilityMessageArray[1]))
                {
                    $string = "ships by ";
                    $availableDate = str_replace($string, "", $availabilityMessageArray[1]);
                    $availableDate = $this->timezone->date($availableDate)->format('d-m-Y');
                }
            }
        }
        return $availableDate;
    }
    public function getAvailabilityMessage($product)
    {
        /**
         * @var $product \Magento\Catalog\Api\Data\ProductInterface
         */
        $availabilityMessage = "NA";

        if($product->getCustomAttribute('availability_message'))
        {
            if($product->getCustomAttribute('availability_message')->getValue() != null)
            {
                $availabilityMessage = $product->getCustomAttribute('availability_message')->getValue();
            }
        }
        return $availabilityMessage;
    }
    public function getAvailabilityFilter($product)
    {
        /**
         * @var $product \Magento\Catalog\Api\Data\ProductInterface
         */
        $availableFilter = "NA";

        if($product->getCustomAttribute('availability_filter'))
        {
            if($product->getCustomAttribute('availability_filter')->getValue() != null)
            {
                $availableFilter = $product->getCustomAttribute('availability_filter')->getValue();
            }
        }
        return $availableFilter;
    }
    public function getDecodeValue($searchValue)
    {
        return $this->urlDecoder->decode($searchValue);
    }
    public function getProductUrl($product, $additional = [])
    {
        return $product->getProductUrl();
    }

    public function getCustomerId()
    {
        $customerSession = $this->_customerSessionFactory->create();
        $id = $customerSession->getCustomer()->getId();
        return $this->_customerRepositoryInterface->getById($id);
    }

    /**
     * Check if the customer can purchase the given product.
     * @return bool
     */
    public function canPurchaseProduct($customer,$product): bool
    {

        $canPurchaseAttributes = [
            'can_purchase_gl_fan',
            'can_purchase_gl',
            'can_purchase_vc_studio',
            'can_purchase_vc_arch',
            'can_purchase_vc_modern',
            'can_purchase_vc_fan',
            'can_purchase_vc_signature',
        ];

        $reportingBrand = $this->purchasePermission->getReportingBrandValue($product->getId());
        $this->logger->notice(json_encode($reportingBrand));
        $attributeMap = $this->purchasePermission->getAttributeMap();
        $this->logger->notice(json_encode($attributeMap));

        foreach ($canPurchaseAttributes as $attribute) {
            $customAttribute = $customer->getCustomAttribute($attribute);


            if ($customAttribute) {
                $attributeValue = strtolower($customAttribute->getValue());
                if ($attributeValue == 0) {
                    $allowedValue = $attributeMap[$attribute] ?? null;

                    if ($reportingBrand === $allowedValue) {
                        return false;
                    }
                }
            }
        }
        $this->logger->notice("Attribute Value for Attribute". json_encode($customAttribute));

        return true;

    }
    /**
     * Construct dynamic product URL for configurable products
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return string
     */
    public function getDynamicProductUrl($product)
    {
        $configurableProductUrl = '';

        // Check if the product is a simple product
        if ($product->getTypeId() === \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE) {
            $parentIds = $this->productTypeConfigurable->getParentIdsByChild($product->getId());

            if (!empty($parentIds)) {
                $configurableProduct = $this->productRepository->getById($parentIds[0]);
                $configurableProductUrl = $this->getUrl('catalog/product/view', [
                    'id' => $configurableProduct->getId(),
                    'child_id' => $product->getId(),
                ]);
            }
        }

        return $configurableProductUrl ; 
    }
}
