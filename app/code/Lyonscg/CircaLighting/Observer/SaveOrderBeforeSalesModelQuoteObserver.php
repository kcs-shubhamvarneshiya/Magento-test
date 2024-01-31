<?php

namespace Lyonscg\CircaLighting\Observer;

use Magento\Framework\Event\ObserverInterface;

class SaveOrderBeforeSalesModelQuoteObserver implements ObserverInterface
{
    /**
     * List of attributes to copy over
     * @var string[]
     */
    private $_attributes = [
        'sidemark',
        'comments_line_item'
    ];

    /**
     * Copy attributes from quote items to order items
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /* @var $order \Magento\Sales\Model\Order */
        $order = $observer->getEvent()->getOrder();

        /* @var $quote \Magento\Quote\Model\Quote */
        $quote = $observer->getEvent()->getQuote();

        /**
         * @var $orderItem \Magento\Sales\Model\Order\Item
         */
        foreach ($order->getItems() as $orderItem) {
            $quoteItemId = $orderItem->getQuoteItemId();
            /** @var $quoteItem \Magento\Quote\Model\Quote\Item */
            $quoteItem = $quote->getItemById($quoteItemId);
            if (!$quoteItem) {
                continue;
            }
            foreach ($this->_attributes as $attribute) {
                $orderItem->setData($attribute, $quoteItem->getData($attribute));
            }
        }
    }
}
