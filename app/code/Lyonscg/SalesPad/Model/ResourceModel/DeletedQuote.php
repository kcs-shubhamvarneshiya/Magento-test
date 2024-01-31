<?php

namespace Lyonscg\SalesPad\Model\ResourceModel;

class DeletedQuote extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('deleted_quotes', 'deleted_id');
    }
}
