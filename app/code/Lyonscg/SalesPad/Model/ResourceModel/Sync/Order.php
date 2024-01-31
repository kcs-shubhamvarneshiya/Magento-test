<?php

namespace Lyonscg\SalesPad\Model\ResourceModel\Sync;

class Order extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('salespad_order_sync', 'sync_id');
    }
}
