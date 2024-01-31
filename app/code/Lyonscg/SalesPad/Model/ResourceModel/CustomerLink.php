<?php

namespace Lyonscg\SalesPad\Model\ResourceModel;

class CustomerLink extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('salespad_customer_link', 'link_id');
    }
}
