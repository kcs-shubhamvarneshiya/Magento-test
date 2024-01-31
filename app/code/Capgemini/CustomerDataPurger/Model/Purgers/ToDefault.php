<?php


namespace Capgemini\CustomerDataPurger\Model\Purgers;


use Capgemini\CustomerDataPurger\Model\AbstractPurger;
use Exception;

class ToDefault extends AbstractPurger
{
    const MAIN_QUERY_PATTERN = 'UPDATE %s SET %s=DEFAULT WHERE %s IN (%s)';
    const PURGING_STRATEGY = 'REPLACE WITH A DEFAULT VALUE';

    /**
     * @inheritdoc
     */
    protected function performMainQuery($tableName, $tableInfo)
    {
        $columnNames = $tableInfo['columns'];
        $rangeStringTemplate = str_repeat('?,', count($tableInfo['ids_for_where_condition']) - 1) . '?';
        $setCommandTemplate = str_repeat('%s=DEFAULT, ', count($columnNames) - 1) . '%s=DEFAULT';
        $updatedQueryPattern = str_replace('%s=DEFAULT', $setCommandTemplate, self::MAIN_QUERY_PATTERN);
        $valuesForQueryPattern = array_merge([$tableName], $columnNames, [$tableInfo['identity_column']], [$rangeStringTemplate]);
        $query = vsprintf($updatedQueryPattern, $valuesForQueryPattern);
        try {
            $this->connection->query($query, $tableInfo['ids_for_where_condition']);
        } catch (Exception $exception) {
            $this->handleCritical($exception->getMessage());
        }
    }
}
