<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Created By : Nirav Modi
 */
namespace Rysun\DataTransfer\Model\ResourceModel\ProductPricing;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Rysun\DataTransfer\Model\ProductPricing', 'Rysun\DataTransfer\Model\ResourceModel\ProductPricing');
    }
}
