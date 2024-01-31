<?php


namespace Lyonscg\AddressRestrictions\Model\ResourceModel;
use Lyonscg\AddressRestrictions\Model\AddressDeleteLog as AddressDeleteLogModel;


class AddressDeleteLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('address_delete_log', AddressDeleteLogModel::LOG_ID);
    }
}
