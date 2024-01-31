<?php

namespace Lyonscg\SalesPad\Plugin;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartExtensionFactory;
use Magento\Quote\Api\Data\CartSearchResultsInterface;

class CartRepositoryPlugin
{
    /**
     * @var CartExtensionFactory
     */
    protected $extensionFactory;

    /**
     * CartRepositoryPlugin constructor.
     * @param CartExtensionFactory $extensionFactory
     */
    public function __construct(
        CartExtensionFactory $extensionFactory
    ) {
        $this->extensionFactory = $extensionFactory;
    }

    public function afterGet(CartRepositoryInterface $subject, CartInterface $cart)
    {
        $quoteDocNum = $cart->getSalespadQuoteNum();
        $extensionAttributes = $cart->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
        $extensionAttributes->setSalespadQuoteNum($quoteDocNum);
        $cart->setExtensionAttributes($extensionAttributes);
        return $cart;
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param CartSearchResultsInterface $results
     * @return CartSearchResultsInterface
     */
    public function afterGetList(CartRepositoryInterface $subject, CartSearchResultsInterface $results)
    {
        foreach ($results->getItems() as $cart) {
            $this->afterGet($subject, $cart);
        }
        return $results;
    }

}
