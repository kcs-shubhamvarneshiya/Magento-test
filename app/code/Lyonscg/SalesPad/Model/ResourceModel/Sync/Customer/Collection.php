<?php

namespace Lyonscg\SalesPad\Model\ResourceModel\Sync\Customer;

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
            \Lyonscg\SalesPad\Model\Sync\Customer::class,
            \Lyonscg\SalesPad\Model\ResourceModel\Sync\Customer::class
        );
    }

    /**
     * @return int[]
     */
    public function getCustomerIds($limit)
    {
        $limit = intval($limit);
        $idsSelect = clone $this->getSelect();
        $idsSelect->reset(\Magento\Framework\DB\Select::ORDER);
        $idsSelect->reset(\Magento\Framework\DB\Select::LIMIT_COUNT);
        $idsSelect->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET);
        $idsSelect->reset(\Magento\Framework\DB\Select::COLUMNS);

        if ($limit && $limit > 0) {
            $idsSelect->limit($limit);
        }

        // possibly add sorting so we can prioritize older entries

        $idsSelect->columns(\Lyonscg\SalesPad\Model\Sync\Customer::CUSTOMER_ID, 'main_table');
        return $this->getConnection()->fetchCol($idsSelect, $this->_bindParams);
    }
}
