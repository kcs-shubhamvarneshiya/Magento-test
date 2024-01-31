<?php

namespace Capgemini\BloomreachProductRecommendations\Block\Widget\Recommendation\Default;

use Capgemini\BloomreachProductRecommendations\Block\Widget\Recommendation;
use Capgemini\BloomreachThematic\Helper\Converter;
use Capgemini\BloomreachThematic\Model\TechnicalProduct;
use Exception;
use Lyonscg\Catalog\Block\Product\ProductList;
use Lyonscg\Catalog\Helper\Config as ConfigHelper;
use Lyonscg\Catalog\Helper\Data as CatalogHelper;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Checkout\Model\ResourceModel\Cart;
use Magento\Checkout\Model\Session;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Module\Manager;
use Capgemini\BloomreachThematic\Model\TechnicalProductFactory;
use Magento\Framework\Pricing\Render;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Customer\Model\Context as CustomerModelContext;
use Magento\Store\Model\ScopeInterface;

class Collection extends ProductList
{
    protected $_template = 'widget/recommendation/default/collection.phtml';
    private TechnicalProductFactory $technicalProductFactory;
    private $items;
    private Converter $converter;
    private HttpContext $httpContext;
    private ProductRepositoryInterface $productRepository;


    public function __construct(
        Context $context,
        Cart $checkoutCart,
        Visibility $catalogProductVisibility,
        Session $checkoutSession,
        Manager $moduleManager,
        CollectionFactory $productCollectionFactory,
        CatalogHelper $catalogHelper,
        ConfigHelper $configHelper,
        TechnicalProductFactory $technicalProductFactory,
        Converter $converter,
        HttpContext $httpContext,
        ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $checkoutCart,
            $catalogProductVisibility,
            $checkoutSession,
            $moduleManager,
            $productCollectionFactory,
            $catalogHelper,
            $configHelper,
            $data
        );

        $this->technicalProductFactory = $technicalProductFactory;
        $this->converter = $converter;
        $this->httpContext = $httpContext;
        $this->productRepository = $productRepository;
    }

    public function getItems()
    {
        if ($this->items === null) {
            $this->_prepareData();
        }

        return $this->items;
    }

    public function getDelegatedData($dataKey, $path)
    {
        $data = $this->getData($dataKey);

        if ($path === null) {

            return $data;
        }

        $path = explode('/', $path);
        $result = $data;

        foreach ($path as $key) {
            $result = $result[$key] ?? null;

            if (null === $result) {

                return null;
            }
        }

        return $result;
    }

    public function getProductUrl($product, $additional = [])
    {
        return $product->getProductUrl();
    }

    public function getImage($product, $imageId, $attributes = [])
    {
        $parent = parent::getImage($product, $imageId, $attributes);
        $brImageUrl = $product->getData('br_product_image');
        $brImageUrl = is_array($brImageUrl) ? $brImageUrl[0] : $brImageUrl;
        $parent->setData('image_url', $brImageUrl);

        return $parent;
    }

    public function getProductPrice(Product $product)
    {
        $product = $this->getPreparedChild($product);

        $priceRender = $this->getPriceRender();

        $price = '';
        if ($priceRender) {
            $price = $priceRender->render(
                FinalPrice::PRICE_CODE,
                $product,
                [
                    'include_container' => true,
                    'display_minimal_price' => true,
                    'zone' => Render::ZONE_ITEM_LIST,
                    'list_category_page' => true
                ]
            );
        }

        return $price;
    }

    public function getIsPixelEnabled()
    {
        return $this->getDelegatedData(Recommendation::DELEGATED_DATA_KEYS['OTHER'], 'is_core_pixel_enabled');
    }

    protected function _prepareData()
    {
        $docs = $this->getDelegatedData(Recommendation::DELEGATED_DATA_KEYS['API_RESPONSE'], 'response/docs') ?? [];

        if (empty($docs)) {
            $this->items = [];
            return;
        }

        if ($this->httpContext->getValue(CustomerModelContext::CONTEXT_AUTH)) {
            $this->prepareForLoggedIn($docs);
        } else {
            $this->prepareForNonLoggedIn($docs);
        }
    }

    protected function _beforeToHtml()
    {
        if ($this->items === null) {
            $this->_prepareData();
        }
        return $this;
    }

    protected function getPriceRender()
    {
        return $this->getLayout()->getBlock('product.price.render.default')
            ->setData('is_product_list', true);
    }

    private function getPreparedChild(Product $product)
    {
        $child = $this->catalogHelper->getChildProduct($product);
        try {
            if (!$this->catalogHelper->hasSalePrice($product)) {

                return $child;
            }
        } catch (Exception $exception) {

            return $child;
        }

        if ($product->isComposite()) {
            $child->setData(
                'final_price_model',
                $product->getPriceInfo()->getPrice('final_price')
            );
        }

        return $child;
    }

    private function prepareForNonLoggedIn(array $docs)
    {
        foreach ($docs as $doc) {
            $productData = $this->converter->convertProductData($doc);
            $productData[TechnicalProduct::IS_THEMATIC_PRODUCT_DATA_KEY] = true;
            $techProd = $this->technicalProductFactory->create();
            $techProd->setData($productData);
            $pseudoData = [
                'entity_id' => $productData['sku'],
                'row_id'    => $productData['sku']
            ];
            $techProd->addData($pseudoData);

            $variations = $techProd->getData('variants') ?? [];

            if (!empty($variations)) {
                $techProd->setData('type_id', Configurable::TYPE_CODE);
                $usedProducts = [];

                foreach ($variations as $variation) {
                    $variationData = $this->converter->convertProductData($variation);
                    $variationData[TechnicalProduct::IS_THEMATIC_PRODUCT_DATA_KEY] = true;
                    $variationData['name'] = $productData['name'];
                    $variationData['basecode'] = $productData['sku'];
                    $variationData[\Lyonscg\Catalog\Pricing\Render\Amount\FinalPrice::BASECODE_ATTRIBUTE] = 1;
                    $variation = $this->technicalProductFactory->create()->setData($variationData);
                    $variation->setId($variationData['sku']);
                    $usedProducts[] = $variation;
                }

                $techProd->setData('_cache_instance_products', $usedProducts);
            }

            $this->items[] = $techProd;
        }
    }

    private function prepareForLoggedIn(array $docs)
    {
        foreach ($docs as $doc) {
            $productData = $this->converter->convertProductData($doc);
            $productData[TechnicalProduct::IS_THEMATIC_PRODUCT_DATA_KEY] = true;
            unset($productData['price']);
            unset($productData['special_price']);

            try {
                $magentoProduct = $this->productRepository->get($productData['sku']);
            } catch (Exception $exception) {
                $this->_logger->error($exception->getMessage(), ['method' => __METHOD__]);

                continue;
            }

            $magentoProduct->addData($productData);
            $techProd = $this->technicalProductFactory->create();
            $techProd->setData($magentoProduct->getData());

            $variations = $techProd->getData('variants') ?? [];

            if (!empty($variations)) {
                $techProd->setData('type_id', Configurable::TYPE_CODE);
                $usedProducts = [];

                foreach ($variations as $variation) {
                    $variationData = $this->converter->convertProductData($variation);
                    $variationData[TechnicalProduct::IS_THEMATIC_PRODUCT_DATA_KEY] = true;
                    unset($variationData['price']);
                    unset($variationData['special_price']);

                    try {
                        $magentoVariation = $this->productRepository->get($variationData['sku']);
                    } catch (Exception $exception) {
                        $this->_logger->error($exception->getMessage(), ['method' => __METHOD__]);

                        continue;
                    }

                    $magentoVariation->addData($variationData);
                    $variation = $this->technicalProductFactory->create()->setData($magentoVariation->getData());
                    $usedProducts[] = $variation;
                }

                $techProd->setData('_cache_instance_products', $usedProducts);
            }

            $this->items[] = $techProd;
        }
    }
}
