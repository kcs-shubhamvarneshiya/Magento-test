<?php

namespace Capgemini\BloomreachThematic\Block\Product;

use Magento\Catalog\Block\Product\Image;
use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

class ListProduct extends \Capgemini\WholesalePrice\Block\Product\ListProduct
{
    /**
     * @param Product $product
     * @param string $imageId
     * @param array $attributes
     * @return Image
     */
    public function getImage($product, $imageId, $attributes = []): Image
    {
        $parent = parent::getImage($product, $imageId, $attributes);
        if ($product->getTypeId() === Configurable::TYPE_CODE) {
            $simples = $product->getData('variants');
            $brImageUrl = $simples[0]['sku_swatch_images'][0];
        } else {
            $brImageUrl = $product->getData('br_product_image');
            $brImageUrl = is_array($brImageUrl) ? $brImageUrl[0] : $brImageUrl;
        }
        $parent->setData('image_url', $brImageUrl);

        return $parent;
    }
}
