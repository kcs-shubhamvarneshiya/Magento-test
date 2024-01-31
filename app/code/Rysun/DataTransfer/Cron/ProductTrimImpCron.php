<?php

namespace Rysun\DataTransfer\Cron;

use Psr\Log\LoggerInterface;
use Magento\Framework\App\ResourceConnection;
use Rysun\DataTransfer\Model\ProductTrimFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Rysun\DataTransfer\Helper\Data;


class ProductTrimImpCron
{

    protected $logger;

    protected $fileFactory;
    protected $csvProcessor;
    protected $directoryList;
    protected $resourceConnection;
    protected $productTrim;
    protected $timezoneInterface;
    protected $productCollection;
    protected $helper;

    const CROSS_PRODUCT_IMPORT_PATH = 'archi_import/general/cross_product_import_path';


    public function __construct(
        LoggerInterface                                  $logger,
        //\Kcs\Pacjson\Model\PacjsonFactory $Pacjson,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\File\Csv                      $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList  $directoryList,
        ResourceConnection                               $resourceConnection,
        ProductTrimFactory                               $productTrim,
        TimezoneInterface                                $timezoneInterface,
        CollectionFactory                                $productCollection,
        Data                        $helper
    )
    {
        $this->logger = $logger;
        //$this->Pacjson = $Pacjson;
        $this->fileFactory = $fileFactory;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        $this->resourceConnection = $resourceConnection;
        $this->productTrim = $productTrim;
        $this->timezoneInterface = $timezoneInterface;
        $this->productCollection = $productCollection;
        $this->helper = $helper;

    }

    /**
     * Write to system.log
     *
     * @return void
     */
    public function execute()
    {

        try {
            $model = $this->productTrim->create();
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORES;

            //$fileName = '08_cross_product.csv';
            //$filePath = "var/urapidflow/import/" . $fileName;

            $filePath = $this->helper->getConfigValue(SELF::CROSS_PRODUCT_IMPORT_PATH);
            $csv = array_map("str_getcsv", file($filePath, FILE_SKIP_EMPTY_LINES));
    
            $keys = array_shift($csv);


            // If NASM IDs exist truncate table
            // File uploaded will have IDs already in the database, not just new ones
            if (!empty($model)) {
                //$connection = $model->getResource()->getConnection();
                $tableName = $model->getResource()->getMainTable();
                //$connection->truncateTable($tableName);
            }

            foreach ($csv as $i => $row) {
                $err = '';
                if (count($row) > 0) {
                    if (count($row) !== count($keys)) {
                        $empty_value_found = true;
                        $err .= "<br>Row " . $i . "\'s length does not match the header length: " . implode(', ', $row);
                    } else {
                        //$rows[] = array_combine($headers, $encoded_row);\
                        $csv[$i] = array_combine($keys, $row);
                        if (empty($csv[$i]['pname'])
                            || empty($csv[$i]['csv_action'])
                            || empty($csv[$i]['option_combination_json'])
                            || empty($csv[$i]['status'])
                            || empty($csv[$i]['sql_serv_id'])
                            || empty($csv[$i]['sql_serv_prod_id'])
                        ) {
                            $empty_value_found = true;
                            $err .= "<br>Row " . $i . " hase empty field please check.";
                        }
                    }
                }
            }

            foreach ($csv as $i => $row) {

                if ($row['csv_action'] == 'C') {
                    $connection = $this->resourceConnection->getConnection();
                    $dateCreated = (new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
                    $dateUpdated = (new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);

                    $tableName = $model->getResource()->getMainTable();

                    $trimProductId = $this->getRealIdBySqlId($row['trim_pid']);
                    $productId = $this->getRealIdBySqlId($row['pid']);


                    $data = array('product_id' => $productId,
                        'trim_product_id' => $trimProductId,
                        'product_type' => $row['link_type_sort'],
                        'is_active' => $row['is_active'],
                        'date_created' => $dateCreated,
                        'date_last_updated' => $dateUpdated,
                        'sql_serv_id' => $row['sql_serv_id']);
                    $connection->insert($tableName, $data);


                } else if ($row['csv_action'] == 'U' && $row['sql_serv_id'] !== '') {

                    $connection = $this->resourceConnection->getConnection();
                    // $table is table name
                    $table = $connection->getTableName($tableName);

                    $productId = $row['pid'];
                    $trimProductId = $row['trim_pid'];

                    $query = "UPDATE $table SET product_id = \"" . $productId . "\", trim_product_id = \"" . $trimProductId . "\", product_type = \"" . $row['link_type_sort'] . "\", is_active = " . $row['is_active'] . " WHERE sql_serv_id = \"" . $row['sql_serv_id'] . "\";";
                    $connection->query($query);

                } else if ($row['csv_action'] == 'D' && $row['pid'] !== '') {

                    $connection = $this->resourceConnection->getConnection();
                    // $table is table name
                    $table = $connection->getTableName($tableName);
                    $query = "DELETE FROM $table WHERE sql_serv_id = \"" . $row['sql_serv_id'] . "\";";
                    $connection->query($query);

                }
            }
            //}
        } catch (Exception $e) {

            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/nasmlog.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);
            $error_message = "Unable to read csv file. Error: " . $e->getMessage() . '. See exception.log for full error log.';

            $this->messageManager->addError($error_message);
            $logger->info($e);
        }

    }

    public function getRealIdBySqlId($sqlServerId)
    {

        //$this->productCollection
        $collection = $this->productCollection->create();
        $collection->addAttributeToSelect('sql_serv_id');
        //$collection->addAttributeToSelect('sku');
        $collection->addAttributeToFilter('sql_serv_id', ['eq' => $sqlServerId]);
        if ($collection->getSize()) {
            $data = $collection->getFirstItem();
            $id = $data->getId();
            return $id;
        }
        return null;


    }

}
