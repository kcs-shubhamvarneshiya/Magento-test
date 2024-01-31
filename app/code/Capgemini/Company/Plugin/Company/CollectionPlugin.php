<?php
namespace Capgemini\Company\Plugin\Company;

/**
 * Class CollectionPlugin
 * @package Capgemini\Company\Plugin\Company
 */
class CollectionPlugin
{
    /**
     * @param $subject
     * @param $result
     * @return mixed
     */
    public function afterJoinCustomerTable($subject, $result)
    {
        $columns = $result->getSelect()->getPart('columns');
        $columns[] = array('customer_grid_flat', 'sales_pad_customer_num', 'sales_pad_customer_num');
        $result->getSelect()->setPart('columns', $columns);

        return $result;
    }
}
