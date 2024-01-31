<?php

namespace Rysun\DataTransfer\Model\ResourceModel\ProductAccessory;

use Rysun\DataTransfer\Model\ProductAccessory as ProductAccessoryModel;
use Rysun\DataTransfer\Model\ResourceModel\ProductAccessory as ProductAccessoryResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            ProductAccessoryModel::class,
            ProductAccessoryResourceModel::class
        );
    }
}