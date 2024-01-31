<?php

namespace Lyonscg\SalesPad\Model\ResourceModel\Api;

class ErrorLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('salespad_api_errors', 'log_id');
    }
}
