<?php

namespace Capgemini\LightBulbs\Model\ProductLink\Converter;

use Magento\Catalog\Model\ProductLink\Converter\ConverterInterface;

class UpsellConverter implements ConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function convert(\Magento\Catalog\Model\Product $product)
    {
        return [
            'type' => $product->getTypeId(),
            'sku' => $product->getSku(),
            'position' => $product->getPosition(),
            'qty' => $product->getQty(),
        ];
    }
}
