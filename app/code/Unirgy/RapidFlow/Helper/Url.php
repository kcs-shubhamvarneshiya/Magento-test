<?php
/**
 * Created by pp
 * @project magento2
 */

namespace Unirgy\RapidFlow\Helper;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\UrlRewrite\Model\UrlPersistInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Unirgy\RapidFlow\Model\ResourceModel\Catalog\Product as RfProduct;

/**
 * Class Url
 * Helper class to generate product URL rewrites
 * @see \Magento\CatalogUrlRewrite\Observer\ProductProcessUrlRewriteSavingObserver
 * @package Unirgy\RapidFlow\Helper
 */
class Url extends AbstractHelper
{
    /**
     * @var array
     */
    protected $vitalForGenerationFields = [
        'sku',
        'url_key',
        'url_path',
        'name',
        'store_id',
        'store_ids',
        'visibility'
    ];

    /**
     * @var UrlPersistInterface
     */
    protected $_urlPersist;

    /**
     * @var ProductUrlRewriteGenerator
     */
    protected $_generator;

    /**
     * @var ProductFactory
     */
    protected $_catalogProductFactory;

    /**
     * @var array
     */
    protected $_productsToUpdate = [];

    /**
     * @var Data
     */
    private $rfHelper;

    protected $_storeManager;

    /**
     * Url constructor.
     * @param Context $context
     * @param ProductFactory $catalogFactory
     * @param ProductUrlRewriteGenerator $productUrlRewriteGenerator
     * @param UrlPersistInterface $urlPersist
     * @param Data $rfHelper
     */
    public function __construct(
        Context $context,
        ProductFactory $catalogFactory,
        ProductUrlRewriteGenerator $productUrlRewriteGenerator,
        UrlPersistInterface $urlPersist,
        Data $rfHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->_catalogProductFactory = $catalogFactory;
        $this->_urlPersist = $urlPersist;
        $this->_generator = $productUrlRewriteGenerator;
        $this->_storeManager = $storeManager;

        parent::__construct($context);
        $this->rfHelper = $rfHelper;
    }

    /**
     * @param $productId
     * @param array $productData
     */
    public function addProductIdForRewriteUpdate($productId, array $productData)
    {
        $this->_productsToUpdate[$productId] = $productData;
    }

    /**
     * @param int|null $storeId
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function updateProductsUrlRewrites($storeId = null)
    {
        $productIds = array_keys($this->_productsToUpdate);
        if (!empty($productIds)) {
            if (null === $storeId || $storeId == 0) {
                $this->attachStoreIds($this->_productsToUpdate);
            }
            foreach ($this->_productsToUpdate as $productId => $productData) {
                $this->refreshProductRewrite($productId, $productData);
                /*
                if ($productData['store_id'] !== 0) {
                    $productData['store_id'] = 0;
                    $this->refreshProductRewrite($productId, $productData);
                }
                */
            }
        }
    }

    protected function attachStoreIds(&$productsData)
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->_catalogProductFactory->create();
        $productIds = array_keys($productsData);
        /** @var \Magento\Catalog\Model\ResourceModel\Product $res */
        $res = $product->getResource();
        $read = $res->getConnection();
        $prodWsSql = $read->select()->from($res->getTable('catalog_product_website'))
            ->where('website_id<>0 AND product_id IN (?)', $productIds);
        if ($__prodWs = $read->fetchAll($prodWsSql)) {
            $prodStores = $prodWs = [];
            foreach ($__prodWs as $__pws) {
                $__pId = $__pws['product_id'];
                if (empty($prodWs[$__pId])) {
                    $prodWs[$__pId] = [];
                }
                $prodWs[$__pId][] = $__pws['website_id'];
            }
            foreach ($prodWs as $__pId=>$__pwsIds) {
               if (empty($prodStores[$__pId])) {
                    $prodStores[$__pId] = [];
                }
                foreach ($this->_storeManager->getStores() as $store) {
                    if (in_array($store->getWebsiteId(), $__pwsIds)) {
                        $prodStores[$__pId][] = $store->getId();
                    }
                }
            }
            foreach ($productsData as $productId => &$productData) {
                if (!empty($prodStores[$productId])) {
                    $productData['store_ids'] = $prodStores[$productId];
                }
            }
            unset($productData);
        }
    }

    /**
     * @param $productId
     * @param array $productData
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function refreshProductRewrite($productId, array $productData = [])
    {
        /** @var Product $product */
        $product = $this->_catalogProductFactory->create();
//        $field = 'entity_id';
//        if($this->rfHelper->hasMageFeature(RfProduct::ROW_ID)){
//            $field = RfProduct::ROW_ID;
//        }
        $product->setId($productId);
        $product->setData('save_rewrites_history', true);
        foreach ($this->vitalForGenerationFields as $field) {
            if (isset($productData[$field])) {
                $product->setData($field, $productData[$field]);
            }
        }

        if ($this->_storeManager->isSingleStoreMode()) {
            $defWid = $this->_storeManager->getDefaultStoreView()->getWebsiteId();
            $product->setData('store_ids', $this->_storeManager->getWebsite($defWid)->getStoreIds());
        }

        /*
        $this->_urlPersist->deleteByData(
            [
                UrlRewrite::ENTITY_ID => $product->getId(),
                UrlRewrite::ENTITY_TYPE => ProductUrlRewriteGenerator::ENTITY_TYPE,
                UrlRewrite::REDIRECT_TYPE => 0,
                UrlRewrite::STORE_ID => $product->getStoreId()
            ]
        );
        */

        $this->_urlPersist->replace($this->_generator->generate($product));
    }
}
