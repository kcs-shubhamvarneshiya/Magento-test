<?php


namespace Capgemini\CustomerDataPurger\Model\Purgers;


use Capgemini\CustomerDataPurger\Model\AbstractPurger;
use Exception;

class ToPlaceholder extends AbstractPurger
{

    const MAIN_QUERY_PATTERN = 'UPDATE %s SET %s=? WHERE %s IN (%s)';
    const PURGING_STRATEGY = 'REPLACE WITH A PLACEHOLDER';

    /**
     * @inheritdoc
     */
    protected function performMainQuery($tableName, $tableInfo)
    {
        $columns = $tableInfo['columns'];
        $rangeStringTemplate = str_repeat('?,', count($tableInfo['ids_for_where_condition']) - 1) . '?';
        $setCommandTemplate = str_repeat('%s=?, ', count($columns) - 1) . '%s=?';
        $updatedQueryPattern = str_replace('%s=?', $setCommandTemplate, self::MAIN_QUERY_PATTERN);
        $columnNames = array_keys($columns);
        $placeholders = array_values($columns);
        $valuesForQueryPattern = array_merge([$tableName], $columnNames, [$tableInfo['identity_column']], [$rangeStringTemplate]);
        $query = vsprintf($updatedQueryPattern, $valuesForQueryPattern);
        try {
            $this->connection->query($query, array_merge($placeholders, $tableInfo['ids_for_where_condition']));
        } catch (Exception $exception) {
            $this->handleCritical($exception->getMessage());
        }
    }
}
