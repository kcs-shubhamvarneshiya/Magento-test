<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Rysun\DataTransfer\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper {

    public function getConfigValue($scopePath) {
    
        return $this->scopeConfig->getValue($scopePath,ScopeInterface::SCOPE_STORE);
    
    }

}

