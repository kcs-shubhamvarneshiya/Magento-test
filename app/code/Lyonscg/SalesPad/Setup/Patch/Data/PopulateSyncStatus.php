<?php

namespace Lyonscg\SalesPad\Setup\Patch\Data;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class PopulateSyncStatus implements DataPatchInterface
{

    const TABLES = [
        'salespad_customer_sync',
        'salespad_order_sync',
        'salespad_quote_sync',
        'salespad_quote_item_sync'
    ];

    const UPDATE_QUERY_PATTERN = 'UPDATE %s SET status="error" WHERE failures IS NOT NULL OR sync_attempts>0';

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * PopulateSyncStatus constructor.
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public function apply()
    {
        $connection = $this->resourceConnection->getConnection();
        foreach (self::TABLES as $tableName) {
            $table = $connection->getTableName($tableName);
            $query = sprintf(self::UPDATE_QUERY_PATTERN, $table);
            $connection->query($query);
        }
    }
}
