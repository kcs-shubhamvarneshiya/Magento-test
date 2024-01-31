<?php


namespace Capgemini\CustomerDataPurger\Model\Purgers;

use Capgemini\CustomerDataPurger\Model\AbstractPurger;
use Exception;

class DelRecord extends AbstractPurger
{
    const MAIN_QUERY_PATTERN = 'DELETE FROM %s WHERE %s IN (%s)';
    const PURGING_STRATEGY = 'DELETE RECORD';

    /**
     * @inheritdoc
     */
    protected function performMainQuery($tableName, $tableInfo)
    {
        $rangeStringTemplate = str_repeat('?,', count($tableInfo['ids_for_where_condition']) - 1) . '?';
        $query = sprintf(self::MAIN_QUERY_PATTERN, $tableName, $tableInfo['identity_column'], $rangeStringTemplate);
        try {
            $this->connection->query($query, $tableInfo['ids_for_where_condition']);
        } catch (Exception $exception) {
            $this->handleCritical($exception->getMessage());
        }
    }
}
