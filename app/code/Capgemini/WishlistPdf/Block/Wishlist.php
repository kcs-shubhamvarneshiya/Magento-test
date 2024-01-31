<?php
/**
 * Capgemini_WishlistPdf
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

namespace Capgemini\WishlistPdf\Block;

use Capgemini\WishlistPdf\Model\Source\PricingType;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Helper\Product\Configuration;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Configuration\Item\ItemInterface;
use Magento\Catalog\Model\Product\Configuration\Item\ItemResolverInterface;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Framework\View\Element\Template;

/**
 * Print button block
 *
 * @method \Magento\Wishlist\Model\Wishlist getWishlist()
 * @method setWishlist(\Magento\Wishlist\Model\Wishlist $wishlist)
 */
class Wishlist extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Image
     */
    protected $imageHelper;
    /**
     * @var ItemResolverInterface
     */
    protected $itemResolver;
    /**
     * @var PriceHelper
     */
    protected $priceHelper;
    /**
     * @var Configuration
     */
    protected $productOptionsHelper;

    /**
     * Constructor
     *
     * @param Image $imageHelper
     * @param ItemResolverInterface $itemResolver
     * @param PriceHelper $priceHelper
     * @param Configuration $productOptionsHelper
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Image $imageHelper,
        ItemResolverInterface $itemResolver,
        PriceHelper $priceHelper,
        Configuration $productOptionsHelper,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->imageHelper = $imageHelper;
        $this->itemResolver = $itemResolver;
        $this->priceHelper = $priceHelper;
        $this->productOptionsHelper = $productOptionsHelper;
    }

    /**
     * Get wishlist items
     *
     * @return \Magento\Framework\DataObject[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getItems()
    {
        return $this->getWishlist()->getItemCollection()->getItems();
    }

    /**
     * Get final product
     *
     * @param ItemInterface $product
     * @return Product
     */
    public function getFinalProduct(ItemInterface $item)
    {
        return $this->itemResolver->getFinalProduct($item);
    }

    /**
     * @param Product $product
     * @return string
     */
    public function getImageUrl(Product $product)
    {
        $url = $this->imageHelper->init($product, 'wishlist_thumbnail')->getUrl();
        return $url;
    }

    /**
     * Get selected pricing type
     *
     * @return int
     */
    public function getPricingType()
    {
        return (int)$this->_request->getParam('pricing_type');
    }

    /**
     * Get markup percent
     *
     * @return int
     */
    public function getMarckup()
    {
        return (int)$this->_request->getParam('percent');
    }

    /**
     * Get formatted product price according to selected pricing
     *
     * @param Product $product
     * @retrun string | null
     */
    public function getPrice(Product $product)
    {
        $pricingType = $this->getPricingType();
        switch ($pricingType) {
            case PricingType::WITH_PRICING:
                $price = $product->getPriceInfo()->getPrice('regular_price')->getValue();
                break;
            case PricingType::TRADE_PRICING:
                $price = $product->getPriceInfo()->getPrice('final_price')->getValue();
                break;
            case PricingType::WITH_MARKUP_PRICE:
                $price = $product->getPriceInfo()->getPrice('final_price')->getValue();
                $price += $price * $this->getMarckup() / 100;
                break;
            default:
                $price = null;
        }
        if ($price) {
            $price = $this->priceHelper->currency($price, true, false);
        }
        return $price;
    }

    /**
     * Get selected item options
     *
     * @param $item
     * @return array
     */
    public function getConfiguredOptions($item)
    {
        $options = $this->productOptionsHelper->getOptions($item);
        foreach ($options as $index => $option) {
            if (is_array($option) && array_key_exists('value', $option)) {
                if (!(array_key_exists('has_html', $option) && $option['has_html'] === true)) {
                    if (is_array($option['value'])) {
                        foreach ($option['value'] as $key => $value) {
                            $option['value'][$key] = $this->escapeHtml($value);
                        }
                    } else {
                        $option['value'] = $this->escapeHtml($option['value'], ["a"]);
                    }
                }
                $options[$index]['value'] = $option['value'];
            }
        }

        return $options;
    }

    /**
     * Get styles link HTML
     *
     * @return string
     */
    public function getStylesLink()
    {
        return '<link rel="stylesheet" type="text/css" href="'
            . $this->getViewFileUrl('Capgemini_WishlistPdf/css/wishlist-pdf.css'). '"/>';
    }
}
