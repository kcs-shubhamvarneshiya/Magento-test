<?php

namespace Lyonscg\SalesPad\Model;

use Magento\Framework\Model\AbstractModel;

class Session extends AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Lyonscg\SalesPad\Model\ResourceModel\Session::class);
    }
}
