<?php

namespace Rysun\DataTransfer\Model;

use Rysun\DataTransfer\Model\ResourceModel\ProductAccessory as ProductAccessoryResourceModel;
use Magento\Framework\Model\AbstractModel;

class ProductAccessory extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ProductAccessoryResourceModel::class);
    }
}
