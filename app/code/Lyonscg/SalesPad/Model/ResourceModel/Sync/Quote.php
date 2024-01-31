<?php

namespace Lyonscg\SalesPad\Model\ResourceModel\Sync;

class Quote extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('salespad_quote_sync', 'sync_id');
    }
}
