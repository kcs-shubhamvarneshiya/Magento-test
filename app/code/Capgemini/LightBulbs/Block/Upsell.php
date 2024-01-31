<?php
/**
 * Lyonscg_LightBulbs
 *
 * @category  Lyons
 * @package   Lyonscg_LightBulbs
 * @author    Logan Montgomery<logan.montgomery@capgemini.com>
 * @author    Tanya Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */
namespace Capgemini\LightBulbs\Block;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Pricing\Render;
use Magento\Framework\Url\Helper\Data as UrlHelper;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Class Upsell
 * @package Capgemini\LightBulbs\Block
 */
class Upsell extends \Magento\Catalog\Block\Product\AbstractProduct
{
    const BULB_SKU = 'bulb_sku';

    const BULB_QTY = 'bulb_qty';

    protected $_template = 'Capgemini_LightBulbs::upsell.phtml';
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $json;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var Product[]
     */
    protected $products = null;

    protected $upSells = null;

    /**
     * @var UrlHelper
     */
    protected $urlHelper;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @var \Capgemini\DataLayer\Helper\Data
     */
    protected $dataLayerHelper;

    /**
     * @var array
     */
    protected $usedSimpleProducts = [];

    /**
     * @var array
     */
    protected $bulbToUsedSimpleId = [];

    /**
     * @var int
     */
    protected $cheapestBulbSimpleId;

    /**
     * @var \Capgemini\LightBulbs\Helper\Data
     */
    protected $bulbHelper;

    /**
     * Upsell constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Serialize\Serializer\Json $json
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param UrlHelper $urlHelper
     * @param \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Capgemini\DataLayer\Helper\Data $dataLayerHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        UrlHelper $urlHelper,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Capgemini\DataLayer\Helper\Data $dataLayerHelper,
        \Capgemini\LightBulbs\Helper\Data $bulbHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->json = $json;
        $this->productRepository = $productRepository;
        $this->urlHelper = $urlHelper;
        $this->imageBuilder = $imageBuilder;
        $this->priceCurrency = $priceCurrency;
        $this->dataLayerHelper = $dataLayerHelper;
        $this->bulbHelper = $bulbHelper;
    }

    /**
     * @return mixed
     */
    public function getMainProduct()
    {
        $item = $this->getLastOrderedItem();
        $product = $item->getProduct();
        return $product;
    }

    /**
     * @return Product[]
     */
    public function getProducts()
    {
        //Disable bulbs for wholesale users
        if (!$this->bulbHelper->canSellBulbs()) {
            return [];
        }
        if ($this->products === null) {
            $simples = $this->getUsedSimpleProducts();
            $cheapestBulbPrice = PHP_INT_MAX;
            foreach ($simples as $simple) {
                $bulbSku = $simple->getData(self::BULB_SKU);
                if (!empty($bulbSku)) {
                    try {
                        // use collection with salability filter instead?
                        $productBulb = $this->productRepository->get($bulbSku);
                        if ($productBulb->isSaleable()) {
                            $simpleId = $simple->getId();
                            $this->products[$simpleId] = [$productBulb];
                            $this->bulbToUsedSimpleId[$productBulb->getId()] = $simpleId;
                            $price = $productBulb->getFinalPrice();

                            if ($price < $cheapestBulbPrice) {
                                $cheapestBulbPrice = $price;
                                $this->cheapestBulbSimpleId = $simpleId;
                            }
                        }
                    } catch (\Exception $e) {
                        // do nothing, product not found so we don't try and show it
                    }
                }
            }
        }

        return $this->products;
    }

    public function getParentUsedSimple($product)
    {
        if ($this->getProducts()) {
            $simpleId = $this->bulbToUsedSimpleId[$product->getId()] ?? null;

            return $this->getUsedSimpleProducts($simpleId);
        }

        return null;
    }

    public function getCheapestBulbSimpleId()
    {
        if (!empty($this->getProducts())) {

            return $this->cheapestBulbSimpleId;
        }

        return null;
    }


    /**
     * @param QuoteItem $item
     * @return ProductInterface|null
     */
    public function getSimpleProductFromQuoteItem(QuoteItem $item)
    {
        if ($option = $item->getOptionByCode('simple_product')) {

            return $this->getUsedSimpleProducts($option->getValue());
        }

        if ($option = $item->getOptionByCode('info_buyRequest')) {
            $buyRequestData = $this->json->unserialize($option->getValue());

            if (isset($buyRequestData['selected_configurable_option'])) {

                return $this->getUsedSimpleProducts($buyRequestData['selected_configurable_option']);
            }
        }

        return null;
    }

    /**
     * Get post parameters
     *
     * @param Product $product
     * @return array
     */
    public function getAddToCartPostParams(Product $product)
    {
        $url = $this->getAddToCartUrl($product, ['_escape' => false]);
        return [
            'action' => $url,
            'data' => [
                'product' => (int) $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }

    /**
     * Retrieve url for direct adding product to cart
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param array $additional
     * @return string
     */
    public function getAddToCartUrl($product, $additional = [])
    {
        $addUrlKey = \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED;
        $addUrlValue = $this->_urlBuilder->getUrl('*/*/*', ['_use_rewrite' => true, '_current' => true]);
        $additional[$addUrlKey] = $this->urlHelper->getEncodedUrl($addUrlValue);

        return $this->_cartHelper->getAddUrl($product, $additional);
    }

    /**
     * @param Product $product
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function getProductImage(Product $product)
    {
        return $this->imageBuilder->create($product, 'product_page_image_small');
    }

    /**
     * Specifies that price rendering should be done for the list of products.
     * (rendering happens in the scope of product list, but not single product)
     *
     * @return Render
     */
    protected function getPriceRender()
    {
        return $this->getLayout()->getBlock('product.price.render.default')
            ->setData('is_product_list', true);
    }

    /**
     * TODO - return number of products required to fulfill the lightbulb upsell
     * @param Product $product
     * @return int
     */
    public function getProductQty(Product $product)
    {
        $parent = $this->getParentUsedSimple($product);
        return (!empty($qty = intval($parent->getData(self::BULB_QTY))) ? $qty : 1);
    }

    /**
     * @param Product $product
     * @param int $multipleTo
     * @return float
     */
    public function getProductPriceMultipleTo(Product $product, $multipleTo = 1)
    {
        $qty = $this->getProductQty($product) * $multipleTo;
        $total = $product->getFinalPrice($qty) * $qty;
        return $this->priceCurrency->format($total, false);
    }

    /**
     * @param AbstractItem $item
     * @return string
     */
    public function getItemPrice(AbstractItem $item)
    {
        $unitPrice = $item->getPrice();
        $totalPrice = $unitPrice * $item->getQtyToAdd();
        return $this->priceCurrency->format($totalPrice);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFormkey()
    {
        $formKey = $this->getLayout()
            ->createBlock(\Magento\Framework\View\Element\FormKey::class);
        return $formKey->toHtml();
    }

    /**
     * @return string
     */
    public function getCartUrl()
    {
        return $this->getUrl('checkout/cart');
    }

    public function getDataLayerJson()
    {
        $product = $this->getMainProduct();
        if (!$product) {
            return '';
        }
        $data = [
            'event'    => 'lightBulbBundle',
            'id'       => $product->getSku(),
            'name'     => $product->getName(),
            'category' => $this->dataLayerHelper->getProductcategories($product)
        ];
        return $this->json->serialize($data);
    }

    public function getPreparedBulbsData()
    {
        $bulbsData = [];
        foreach ($this->getProducts() as $simpleId => $products) {
            foreach ($products as $product) {
                $bulbsData[$simpleId][] = [
                    'qty'   => $this->getProductQty($product),
                    'sku'   => $product->getSku(),
                    'id'    => $product->getId(),
                    'price' => $this->getProductPriceMultipleTo($product)
                ];
            }
        }

        return $this->json->serialize($bulbsData);
    }

    /**
     * @return ProductInterface[]|ProductInterface
     */
    protected function getUsedSimpleProducts($productId = false)
    {
        if (empty($this->usedSimpleProducts)) {
            $mainProduct = $this->getMainProduct();

            if ($mainProduct->getType() === 'simple') {
                $this->usedSimpleProducts = [$mainProduct->getId() => $mainProduct];
            } else {
                $instance = $mainProduct->getTypeInstance();
                if (method_exists($instance, 'getUsedProducts')) {
                    foreach ($instance->getUsedProducts($mainProduct) as $usedSimpleProduct) {
                        $this->usedSimpleProducts[$usedSimpleProduct->getId()] = $usedSimpleProduct;
                    }
                }
            }
        }

        return $productId === false ? $this->usedSimpleProducts : ($this->usedSimpleProducts[$productId] ?? null);
    }
}
