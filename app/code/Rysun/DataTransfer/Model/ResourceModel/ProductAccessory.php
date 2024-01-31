<?php

namespace Rysun\DataTransfer\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductAccessory extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('xref_product_accessory', 'product_accessory_id');
    }
}