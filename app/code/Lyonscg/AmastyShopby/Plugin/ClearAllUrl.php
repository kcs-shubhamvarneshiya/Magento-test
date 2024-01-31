<?php

namespace Lyonscg\AmastyShopby\Plugin;

use Magento\LayeredNavigation\Block\Navigation;

class ClearAllUrl
{
    public function afterGetClearUrl(Navigation $subject, $result)
    {
        $parts = explode('?', $result);

        if (strpos($parts[0], '/catalogsearch/result/') !== false) {
            // The string '/catalogsearch/result/' is found in the URL
            return $result;
        } else {
            return $parts[0];
        }
        if (strpos($parts[1], 'darkmode') !== false) {
            // The string 'darkmode' is found in the URL
            return $result;
        }
    }
}
