<?php

namespace Capgemini\CustomHeight\Plugin;

use Magento\Checkout\CustomerData\ItemPool;
use Magento\Quote\Model\Quote\Item;

class CustomerDataItemPoolPlugin
{

    /**
     * @param ItemPool $subject
     * @param $result
     * @param Item $item
     */
    public function afterGetItemData(ItemPool $subject, $result, Item $item)
    {
        try {
            $result["custom_height_value"] = $item->getExtensionAttributes()->getCustomHeightValue();
        } catch (\Exception $e) {
            return $result;
        }
        return $result;
    }
}
