<?php

namespace Capgemini\CustomHeight\Plugin;

class QuoteItemToOrderItemPlugin
{
    /**
     * List of attributes to copy over
     * @var string[]
     */
    private $_attributes = [
        "custom_height_price",
        "custom_height_value",
        "custom_height_cost"
    ];
    public function aroundConvert(\Magento\Quote\Model\Quote\Item\ToOrderItem $subject, callable $proceed, $quoteItem, $data)
    {

        $orderItem = $proceed($quoteItem, $data);

        foreach ($this->_attributes as $attribute) {
          $orderItem->setData($attribute, $quoteItem->getData($attribute));
        }

        return $orderItem;
    }
}
