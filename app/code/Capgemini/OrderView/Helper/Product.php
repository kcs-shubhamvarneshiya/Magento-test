<?php

namespace Capgemini\OrderView\Helper;

use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;

class Product extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $imageBuilder;

    protected $productRepository;

    protected $productFactory;

    public function __construct(
        Context $context,
        ImageBuilder $imageBuilder,
        ProductRepositoryInterface $productRepository,
        ProductInterfaceFactory $productFactory
    ) {
        parent::__construct($context);
        $this->imageBuilder = $imageBuilder;
        $this->productRepository = $productRepository;
        $this->productFactory = $productFactory;
    }

    /**
     * @param $product
     * @param null|string $template
     * @return \Magento\Catalog\Block\Product\Image|string
     */
    public function getProductImage($product, $template = null)
    {
        if ($product instanceof \Magento\Catalog\Model\Product) {
            $image = $this->imageBuilder->create($product, 'cart_page_product_thumbnail');
            if (!empty($template)) {
                $image->setTemplate($template);
            }
            return $image->toHtml();
        } else {
            return '';
        }
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderItemInterface $orderItem
     * @param null|string $template
     * @return \Magento\Catalog\Block\Product\Image|string
     */
    public function getOrderItemImage($orderItem, $template = null)
    {
        $childItems = $orderItem->getChildrenItems();
        if (!empty($childItems)) {
            foreach ($childItems as $childItem) {
                return $this->getProductImage($childItem->getProduct(), $template);
            }
        } else {
            return $this->getProductImage($orderItem->getProduct(), $template);
        }
    }

    public function getProduct($sku)
    {
        try {
            return $this->productRepository->get($sku);
        } catch (NoSuchEntityException $e) {
            $product = $this->productFactory->create();
            $product->setSku($sku);
            return $product;
        }
    }

    /**
     * @param $wishlistItem \Magento\Wishlist\Model\Item
     */
    public function getWishlistItemProduct($wishlistItem)
    {
        $simple = $wishlistItem->getOptionByCode('simple_product');
        if ($simple === null) {
            return $wishlistItem->getProduct();
        }
        try {
            return $this->productRepository->getById($simple->getValue());
        } catch (NoSuchEntityException $e) {
            return $wishlistItem->getProduct();
        }
    }
}
