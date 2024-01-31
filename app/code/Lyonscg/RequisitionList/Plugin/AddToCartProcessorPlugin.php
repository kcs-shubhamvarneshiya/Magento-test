<?php

namespace Lyonscg\RequisitionList\Plugin;

use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\RequisitionList\Model\AddToCartProcessorInterface;

class AddToCartProcessorPlugin
{
    /**
     * @var \Magento\Quote\Model\Quote\Item\CartItemOptionsProcessor
     */
    private $cartItemOptionProcessor;

    /**
     * @param \Magento\Quote\Model\Quote\Item\CartItemOptionsProcessor $cartItemOptionProcessor
     */
    public function __construct(
        \Magento\Quote\Model\Quote\Item\CartItemOptionsProcessor $cartItemOptionProcessor
    ) {
        $this->cartItemOptionProcessor = $cartItemOptionProcessor;
    }

    public function aroundExecute(
        AddToCartProcessorInterface $subject,
        callable $proceed,
        CartInterface $cart,
        CartItemInterface $cartItem
    ) {
        $product = $cartItem->getData('product');
        $productOptions = $this->cartItemOptionProcessor->getBuyRequest($product->getTypeId(), $cartItem);
        $quoteItem = $cart->addProduct($product, $productOptions);
        $quoteItem->setExtensionAttributes($cartItem->getExtensionAttributes());
        $quoteItem->setSidemark($cartItem->getSidemark());
        $quoteItem->setCommentsLineItem($cartItem->getCommentsLineItem());
    }
}
