<?php

namespace Rysun\DataTransfer\Model\ResourceModel\ProductTrim;

use Rysun\DataTransfer\Model\ProductTrim as ProductTrimModel;
use Rysun\DataTransfer\Model\ResourceModel\ProductTrim as HProductTrimResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            ProductTrimModel::class,
            HProductTrimResourceModel::class
        );
    }
}