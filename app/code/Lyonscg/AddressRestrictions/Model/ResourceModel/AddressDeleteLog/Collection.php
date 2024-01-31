<?php

namespace Lyonscg\AddressRestrictions\Model\ResourceModel\AddressDeleteLog;
use Lyonscg\AddressRestrictions\Model\AddressDeleteLog as AddressDeleteLogModel;
use Lyonscg\AddressRestrictions\Model\ResourceModel\AddressDeleteLog as AddressDeleteLogResource;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Initialize Collection
     */
    protected function _construct()
    {
        $this->_init(
            AddressDeleteLogModel::class,
            AddressDeleteLogResource::class
        );
    }

}
