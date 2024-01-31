<?php
/**
 * Lyonscg_Catalog
 *
 * @category  Lyons
 * @package   Lyonscg_Catalog
 * @author    Logan Montgomery <logan.montgomery@capgemini.com>
 * @author    Tanya Mamchik<tanya.mamchik@capgemini.com>
 * @author    Yaroslav Protsko<yaroslav.protsko@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */

namespace Lyonscg\Catalog\Helper;

use Capgemini\BloomreachThematic\Model\TechnicalProduct;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Cache\FrontendInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const BASECODE_ATTRIBUTE = 'basecodedisplayimage';

    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @var FrontendInterface
     */
    private $cache;

    /**
     * @var ProductInterfaceFactory
     */
    private $productFactory;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var ProductInterface[]|Product[]|DataObject[]|null
     */
    private $baseChildProducts = null;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Data constructor.
     * @param Context $context
     * @param MetadataPool $metadataPool
     * @param FrontendInterface $cache
     * @param ProductInterfaceFactory $productFactory
     * @param ProductRepositoryInterface $productRepository
     * @param SerializerInterface $serializer
     * @param CustomerSession $customerSession
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        MetadataPool $metadataPool,
        FrontendInterface $cache,
        ProductInterfaceFactory $productFactory,
        ProductRepositoryInterface $productRepository,
        SerializerInterface $serializer,
        CustomerSession $customerSession,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->metadataPool = $metadataPool;
        $this->cache = $cache;
        $this->productFactory = $productFactory;
        $this->serializer = $serializer;
        $this->customerSession = $customerSession;
        $this->productRepository = $productRepository;
        $this->logger = $logger;
    }

    /**
     * @param Product $product
     * @return DataObject|ProductInterface|Product|mixed|null
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    protected function getBasecodeChildProduct(Product $product)
    {
        if (empty($this->baseChildProducts[$product->getId()])) {
            if ($product->getTypeId() !== Configurable::TYPE_CODE) {
                return null;
            }

            $cacheKey = $this->getCacheKey($product);
            $childProduct = $this->readBasecodeChildProductCacheData($cacheKey);
            if ($childProduct === null) {
                $childProduct = $this->setBasecodeChildProduct($product);
                $this->saveBasecodeChildProductCacheData($product, $childProduct, $cacheKey);
            }
            $this->baseChildProducts[$product->getId()] = $childProduct;
        }
        return $this->baseChildProducts[$product->getId()];
    }

    /**
     * @param Product $product
     * @return ProductInterface
     */
    protected function setBasecodeChildProduct($product)
    {
        /** @var Configurable $configType */
        $configType = $product->getTypeInstance();

        if ($product->getData(TechnicalProduct::IS_THEMATIC_PRODUCT_DATA_KEY)) {
            if (isset($configType->getUsedProducts($product)[0])) {
                return $configType->getUsedProducts($product)[0];
            }
        }

        $collection = $configType->getUsedProductCollection($product)
            ->setFlag('has_stock_status_filter', true)
            ->addAttributeToSelect(self::BASECODE_ATTRIBUTE)
            ->load()
            ->getItems();
        foreach ($collection as $childProduct) {
            /** @var Product $childProduct */
            if ($childProduct->getAttributeText(self::BASECODE_ATTRIBUTE)) {
                try {
                    return $this->productRepository->get($childProduct->getSku());
                } catch (\Exception $e) {
                }
            }
        }
        return null;
    }

    /**
     * @param Product $product
     * @return string
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCacheKey($product): string
    {
        $metadata = $this->metadataPool->getMetadata(ProductInterface::class);
        $keyParts = [
            'getBasecodeChildProduct',
            $product->getData($metadata->getLinkField()),
            $product->getStoreId(),
            $this->customerSession->getCustomerGroupId(),
        ];
        $cacheKey = sha1(implode('_', $keyParts));

        return $cacheKey;
    }

    /**
     * Read used products data from cache
     *
     * Looking for cache record stored under provided $cacheKey
     * In case data exists turns it
     *
     * @param string $cacheKey
     * @return ProductInterface|null
     */
    protected function readBasecodeChildProductCacheData($cacheKey)
    {
        $data = $this->cache->load($cacheKey);
        if (!$data) {
            return null;
        }

        $product = $this->serializer->unserialize($data);
        if (!$product) {
            return null;
        }
        $productItem = $this->productFactory->create();
        $productItem->setData($product);
        return $productItem;
    }

    /**
     * Save $baseProduct to cache record identified with provided $cacheKey
     *
     * Cached data will be tagged with combined list of product tags and data specific tags i.e. 'price' etc.
     *
     * @param Product $product
     * @param ProductInterface $baseProduct
     * @param string $cacheKey
     * @return bool
     */
    private function saveBasecodeChildProductCacheData(Product $product, $baseProduct, $cacheKey)
    {
        try {
            $metadata = $this->metadataPool->getMetadata(ProductInterface::class);
            $data = $this->serializer->serialize($baseProduct->getData());
            $tags = array_merge(
                $product->getIdentities(),
                [
                    Category::CACHE_TAG,
                    Product::CACHE_TAG,
                    'price',
                    Configurable::TYPE_CODE . '_' . $product->getData($metadata->getLinkField()),
                ]
            );
            $result = $this->cache->save($data, $cacheKey, $tags);
            return (bool)$result;
        } catch (\Exception $e) {
            return false;
        } catch (\Error $e) {
            return false;
        }
    }

    /**
     * Return the child product specified by the selected_product url parameter
     * @see CLMI-613
     * @param Product $product
     * @return bool|Product|ProductInterface
     */
    private function getSelectedChildProduct(Product $product)
    {
        $selectedProductId = $this->_getRequest()->getParam('selected_product', false);
        if ($selectedProductId === false) {
            $selectedProductId = $this->_getRequest()->getParam('selected_configurable_option', false);
            if ($selectedProductId === false) {
                return false;
            }
        }
        /** @var Configurable $configType */
        $configType = $product->getTypeInstance();
        $collection = $configType->getUsedProductCollection($product)
            ->setFlag('has_stock_status_filter', true)
            ->load()
            ->getItems();
        /** @var Product $childProduct */
        foreach ($collection as $childProduct) {
            if ($childProduct->getSku() == $selectedProductId) {
                try {
                    return $this->productRepository->get($childProduct->getSku());
                } catch (\Exception $e) {
                    return false;
                }
            }
        }
        return false;
    }

    /**
     * @param Product $product
     * @return ProductInterface|Product|DataObject
     */
    public function getChildProduct(Product $product)
    {
        // CLMI-613
        $child = $this->getSelectedChildProduct($product);
        if ($child !== false) {
            return $child;
        }
        $child = $this->getBasecodeChildProduct($product);
        return $child ?? $product;
    }

    /**
     * @param Product $product
     * @return bool
     * @throws \Exception
     */
    public function hasSalePrice(Product $product)
    {
        $test = 'test';
        $compareForNonComposite = function ($nonComposite) {
            $priceInfo = $nonComposite->getPriceInfo();
            $regularPrice = $priceInfo->getPrice('regular_price')->getAmount()->getValue() * 1;
            $finalPrice = $priceInfo->getPrice('final_price')->getAmount()->getValue() * 1;

            return $regularPrice - $finalPrice > 0;
        };

        if (!$product->isComposite()) {

            return $compareForNonComposite($product);
        }

        if ($product->getTypeId() === Configurable::TYPE_CODE) {
            /** @var Configurable $configType */
            $configType = $product->getTypeInstance();
            $children = $configType->getUsedProducts($product);
            foreach ($children as $child) {
                if ($compareForNonComposite($child)) {

                    return true;
                }
            }

            return false;
        }

        throw new \Exception('No method is determined to check sale price for the current product type');
    }
}
