<?php


namespace Lyonscg\SalesPad\Helper;


use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Data\Collection\AbstractDb;

class Data extends AbstractHelper
{
    /**
     * @param AbstractDb $collection
     * @param array $pairColumnNames
     * @return array
     */
    public static function fetchPairsFromCollection(AbstractDb $collection, array $pairColumnNames): array
    {
        $select = $collection
            ->getSelect()
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns($pairColumnNames);

        return $collection->getConnection()->fetchPairs($select);
    }
}
