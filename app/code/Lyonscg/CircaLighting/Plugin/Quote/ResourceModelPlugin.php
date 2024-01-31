<?php

namespace Lyonscg\CircaLighting\Plugin\Quote;

class ResourceModelPlugin
{
    public function afterGetReservedOrderId(\Magento\Quote\Model\ResourceModel\Quote $subject, $result)
    {
        $matches = [];
        if (preg_match('/^(\D)(\d+)$/', $result, $matches) === 1) {
            if (count($matches) === 3) {
                $prefix = $matches[1];
                $number = $matches[2];
                // strip zeros after prefix
                $result = $prefix . strval(intval($number));
            }
        }
        return $result;
    }
}
