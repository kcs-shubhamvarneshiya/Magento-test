<?php

namespace Lyonscg\SalesPad\Model\ResourceModel\Session;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Lyonscg\SalesPad\Model\Session::class,
            \Lyonscg\SalesPad\Model\ResourceModel\Session::class
        );
    }

    public function addActiveFilter()
    {
        $this->addFieldToFilter('active', 1);
        return $this;
    }

    public function addApiUrlFilter($apiUrl)
    {
        $this->addFieldToFilter('api_url', $apiUrl);
        return $this;
    }
}
