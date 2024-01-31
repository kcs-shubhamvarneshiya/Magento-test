<?php


namespace Lyonscg\CircaLighting\Plugin;

use Magento\Quote\Api\Data\CartItemExtensionFactory;
use Magento\Quote\Api\Data\CartSearchResultsInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\CartRepositoryInterface;

class QuoteRepositoryPlugin
{
    /**
     * @var CartItemExtensionFactory
     */
    protected $itemExtensionFactory;

    /**
     * QuoteRepositoryPlugin constructor.
     * @param CartItemExtensionFactory $itemExtensionFactory
     */
    public function __construct(
        CartItemExtensionFactory $itemExtensionFactory
    ) {
        $this->itemExtensionFactory = $itemExtensionFactory;
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param $order
     * @param int $id
     * @return CartInterface
     */
    public function afterGet(CartRepositoryInterface $subject, CartInterface $quote)
    {
        return $this->addExtensionAttributesToItems($quote);
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param CartSearchResultsInterface $results
     * @return CartSearchResultsInterface
     */
    public function afterGetList(CartRepositoryInterface $subject, CartSearchResultsInterface $results)
    {
        $orders = [];
        foreach ($results->getItems() as $order) {
            $orders[] = $this->afterGet($subject, $order);
        }
        $results->setItems($orders);
        return $results;
    }

    /**
     * @param CartInterface $quote
     * @return CartInterface
     */
    private function addExtensionAttributesToItems(CartInterface $quote)
    {
        $quoteItems = $quote->getItems();
        if (null !== $quoteItems) {
            /** @var \Magento\Sales\Api\Data\OrderItemInterface $quoteItem */
            foreach ($quoteItems as $quoteItem) {
                $sidemark = $quoteItem->getSidemark();
                $comments = $quoteItem->getCommentsLineItem();
                $extensionAttributes = $quoteItem->getExtensionAttributes();
                $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->itemExtensionFactory->create();
                $extensionAttributes->setSidemark($sidemark);
                $extensionAttributes->setCommentsLineItem($comments);
                $quoteItem->setExtensionAttributes($extensionAttributes);
            }
        }
        return $quote;
    }
}
