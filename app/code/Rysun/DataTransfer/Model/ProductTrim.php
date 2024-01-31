<?php

namespace Rysun\DataTransfer\Model;

use Rysun\DataTransfer\Model\ResourceModel\ProductTrim as ProductTrimResourceModel;
use Magento\Framework\Model\AbstractModel;

class ProductTrim extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ProductTrimResourceModel::class);
    }
}
