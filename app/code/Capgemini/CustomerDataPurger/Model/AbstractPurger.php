<?php


namespace Capgemini\CustomerDataPurger\Model;


use Exception;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Logger\Monolog;

abstract class AbstractPurger
{
    const REFERENTIAL_PATH_DELIMITER = '|';
    const REFERENTIAL_PATH_PATTERN = '#^\[[a-z_,0-9]+\](\|[a-z_,0-9]+\[[a-z_,0-9]+,[a-z_,0-9]+\])*\|customer_entity\[[a-z_,0-9]+\]$#';
    const SELECT_QUERY_PATTERN = 'SELECT %s FROM %s WHERE %s IN (?)';
    const MAIN_QUERY_PATTERN = '';
    const PURGING_STRATEGY = '';

    /**
     * @var AdapterInterface
     */
    protected $connection;

    /**
     * @var Monolog
     */
    protected $logger;

    public function __construct(ResourceConnection $resourceConnection, Monolog $logger)
    {
        $this->connection = $resourceConnection->getConnection();
        $this->logger = $logger;
    }

    /**
     * Purge customer's data based on purge info
     *
     * @param DataObject $entityData
     * @param array $purgeInstruction
     * @return void
     * @throws Exception
     */
    public function purge(DataObject $entityData, array $purgeInstruction)
    {
        foreach ($purgeInstruction as $table => $tableInfo) {
            $tableName = $this->connection->getTableName($table);
            $referentialPath = $this->getPreparedReferentialPath($tableName, $tableInfo);
            $rowIdsString = $this->getRowIdsForWhereCondition($entityData, $referentialPath);

            if (empty($rowIdsString)) {
                $this->logger->notice('Capgemini_CustomerDataPurger: No matching rows to perform '
                    . static::PURGING_STRATEGY . ' purging strategy for ' . $tableName . ' table.');
            } else {
                $tableInfo['ids_for_where_condition'] = explode(',', $rowIdsString);
                $this->performMainQuery($tableName, $tableInfo);
            }
        }
    }

    /**
     * Log formatted message and throw Exception with it
     *
     * @param string $message
     * @throws Exception
     */
    protected function handleCritical(string $message)
    {
        $code = time();
        $message = 'Capgemini_CustomerDataPurger ' . $code . ': ' . $message;
        $this->logger->critical($message);
        throw new Exception($message, $code);
    }

    /**
     * Process purge instructions further for a given table
     *
     * @param string $tableName
     * @param array $tableInfo
     * @return mixed
     * @throws Exception
     */
    protected abstract function performMainQuery(string $tableName, array $tableInfo);

    /**
     * Perform validation of the referential path
     * and prepare it for being able
     * to be further processed in a more uniform manner
     *
     * @param string $tableName
     * @param array $tableInfo
     * @return false|string[]
     * @throws Exception
     */
    private function getPreparedReferentialPath(string $tableName, array $tableInfo)
    {
        if (!preg_match(self::REFERENTIAL_PATH_PATTERN, $tableInfo['referential_path'])) {
            $this->handleCritical(sprintf(
                'Purging for table %s is impossible because of incorrect referential path: %s',
                $tableName,
                $tableInfo['referential_path']
            ));
        }

        $firstPart = sprintf('%s[%s,', $tableName, $tableInfo['identity_column']);
        $secondPart = substr($tableInfo['referential_path'], 1);

        return explode(self::REFERENTIAL_PATH_DELIMITER, $firstPart . $secondPart);
    }

    /**
     * Get Ids for Rows that are going
     * to be deleted or updated
     *
     * @param DataObject $entityData
     * @param array $referentialPath
     * @return string
     * @throws Exception
     */
    private function getRowIdsForWhereCondition(DataObject $entityData, array $referentialPath): string
    {
        $linkingInfo = array_shift($referentialPath);
        $linkingInfo = explode('[', $linkingInfo);
        $tableName = $linkingInfo[0];
        $columnsString = rtrim($linkingInfo[1], ']');
        $columns = explode(',', $columnsString);
        $identityColumn = $columns[0];
        $conditionColumn = $columns[1] ?? '';

        if ($conditionColumn) {
            $query = sprintf(
                self::SELECT_QUERY_PATTERN,
                $identityColumn,
                $tableName,
                $conditionColumn
            );
            try {
                $result = $this->connection->fetchAll($query, $this->getRowIdsForWhereCondition($entityData, $referentialPath));
            } catch (Exception $exception) {
                $this->handleCritical($exception->getMessage());
            }
        } else {
            $customerDatumField = $this->resolveCustomerIdFieldName($identityColumn);

            return $entityData->getData($customerDatumField);
        }

        return implode(',', array_column($result, $identityColumn));
    }

    /**
     * Accord different names for customer ID field
     *
     * @param string $name
     * @return string
     */
    private function resolveCustomerIdFieldName(string $name): string
    {
        if($name === 'entity_id') {
            $name = 'id';
        }

        return $name;
    }
}
