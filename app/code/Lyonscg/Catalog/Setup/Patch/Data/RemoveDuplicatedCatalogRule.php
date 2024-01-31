<?php
/**
 * Patch File to create url rewrites for categories that were absent
 *
 * @category  Lyons
 * @package   Lyonscg_Catalog
 * @author    Tanya Mamchik <tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */
namespace Lyonscg\Catalog\Setup\Patch\Data;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Psr\Log\LoggerInterface;

class RemoveDuplicatedCatalogRule implements DataPatchInterface
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;
    /**
     * @var string
     */
    private $logger;

    /**
     * RemoveDuplicatedCatalogRule constructor.
     * @param ResourceConnection $resourceConnection
     * @param LoggerInterface $logger
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        LoggerInterface $logger
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        try {
            $connection = $this->resourceConnection->getConnection();
            $table = $connection->getTableName('catalogrule');
            $query = "DELETE FROM " . $table . " WHERE rule_id = 121 AND row_id = 796";
            $connection->query($query);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getVersion()
    {
        return '1.0.0';
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }
}
