<?php

namespace Rysun\DataTransfer\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductTrim extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('xref_product_trim', 'product_trim_id');
    }
}