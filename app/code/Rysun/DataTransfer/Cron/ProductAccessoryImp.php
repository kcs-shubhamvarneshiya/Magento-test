<?php

namespace Rysun\DataTransfer\Cron;

use Psr\Log\LoggerInterface;
use Magento\Framework\App\ResourceConnection;
use Rysun\DataTransfer\Model\ProductTrimFactory;
use Rysun\DataTransfer\Model\ProductAccessoryFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;


use Rysun\DataTransfer\Helper\Data;

class ProductAccessoryImp
{

    protected $logger;

    protected $fileFactory;
    protected $csvProcessor;
    protected $directoryList;
    protected $resourceConnection;
    protected $productTrim;
    protected $timezoneInterface;
    protected $productAccessory;
    protected $productCollection;
    protected $helper;

    const ACCESSORIES_IMPORT_PATH = 'archi_import/general/accessories_import_path';

    public function __construct(
        LoggerInterface                                  $logger,
        //\Kcs\Pacjson\Model\PacjsonFactory $Pacjson,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\File\Csv                      $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList  $directoryList,
        ResourceConnection                               $resourceConnection,
        ProductTrimFactory                               $productTrim,
        TimezoneInterface                                $timezoneInterface,
        ProductAccessoryFactory                          $productAccessory,
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
        $this->productAccessory = $productAccessory;
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
            //$uploadedCsvFilePath = $this->_scopeConfig->getValue("nasmsetting/nasmgeneral/nasm_certification_file_upload", $storeScope);

            //$fileName = '07_accessory.csv';
            //echo $filePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR)
            //$filePath = "var/urapidflow/import/" . $fileName;

            $filePath = $this->helper->getConfigValue(SELF::ACCESSORIES_IMPORT_PATH);
            $csv = array_map("str_getcsv", file($filePath, FILE_SKIP_EMPTY_LINES));

            //	print_r($csv);exit('here');
            $keys = array_shift($csv);
            //print_r($keys);exit('22');


            // If NASM IDs exist truncate table
            // File uploaded will have IDs already in the database, not just new ones
            if (!empty($model)) {
                //$connection = $model->getResource()->getConnection();
                $tableName = $model->getResource()->getMainTable();
                //$connection->truncateTable($tableName);
            }

            //echo "Called...";exit;
            foreach ($csv as $i => $row) {
                $err = '';

                if (count($row) > 0) {
                    if ($row[0] == '##AC') {
                        $keys = $row;
                    }
                    if ($row[0] == '##ACV') {
                        $keys = $row;
                    }
                    if ($row[0] == '##PA') {
                        $keys = $row;
                    }

                    if (count($row) !== count($keys)) {
                        $empty_value_found = true;
                        $err .= "<br>Row " . $i . "\'s length does not match the header length: " . implode(', ', $row);
                    } else {
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

                if (isset($row['##AC']) && $row['##AC'] == 'AC') {
                    echo ' Accessory Import';
                    $this->insertIntoAcc($row);
                } else if (isset($row['##ACV']) && $row['##ACV'] == 'ACV') {
                    //exit('ACV exit');
                    echo ' Accessory Value Import ';
                    $this->insertIntoAccVal($row);
                } else if (isset($row['##PA']) && $row['##PA'] == 'PA') {
                    //exit('PA exit');
                    echo ' Product Accessory Link ';
                    $this->insertIntoProdAcc($row);
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

        //echo 'Executed';
    }

    public function insertIntoAcc($row)
    {

        $tableName = 'tbl_accessory';

        if ($row['csv_action'] == 'C') {

            $connection = $this->resourceConnection->getConnection();
            $dateCreated = (new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
            $dateUpdated = (new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);

            $data = array('accessory_name' => $this->sanitizeValue($row['accessory_name']),
                'web_name' => $this->sanitizeValue($row['web_name']),
                'is_active' => $row['is_active'],
                'date_created' => $dateCreated,
                'date_last_updated' => $dateUpdated,
                'sql_serv_id' => $row['sql_serv_id']);
            $connection->insert($tableName, $data);

        } else if ($row['csv_action'] == 'U' && $row['sql_serv_id'] !== '') {

            $connection = $this->resourceConnection->getConnection();
            // $table is table name
            $table = $connection->getTableName($tableName);

            $accName = $this->sanitizeValue($row['accessory_name']);
            $webName = $this->sanitizeValue($row['web_name']);
            $data = ["accessory_name" => $accName, "web_name" => $webName, "is_active" => $row['is_active']];
            $where = ['sql_serv_id = ?' => $row['sql_serv_id']];

            $connection->update($tableName, $data, $where);

        } else if ($row['csv_action'] == 'D' && $row['sql_serv_id'] !== '') {

            $connection = $this->resourceConnection->getConnection();
            // $table is table name
            $table = $connection->getTableName($tableName);
            $query = "DELETE FROM $table WHERE sql_serv_id = \"" . $row['sql_serv_id'] . "\";";

            $connection->query($query);
        }

    }

    public function insertIntoAccVal($row)
    {

        $tableName = 'accessory_value';

        if ($row['csv_action'] == 'C') {

            $connection = $this->resourceConnection->getConnection();
            $dateCreated = (new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
            $dateUpdated = (new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);

            $accessoryId = $this->getAccessoryId($row['acc_id']);


            $data = array('accessory_id' => $accessoryId,
                'accessory_value_desc' => $this->sanitizeValue($row['acc_val_desc']),
                'item_number' => $this->sanitizeValue($row['item_number']),
                'is_active' => $row['is_active'],
                'date_created' => $dateCreated,
                'date_last_updated' => $dateUpdated,
                'sql_serv_id' => $row['sql_serv_id']);
            $connection->insert($tableName, $data);

        } else if ($row['csv_action'] == 'U' && $row['sql_serv_id'] !== '') {

            $connection = $this->resourceConnection->getConnection();
            // $table is table name
            $table = $connection->getTableName($tableName);

            $accessoryId = $this->getAccessoryId($row['acc_id']);
            //$accessoryId = $row['acc_id'];

            $accValDesc = $this->sanitizeValue($row['acc_val_desc']);
            $itemNumber = $this->sanitizeValue($row['item_number']);

            $data = ["accessory_id" => $accessoryId, "accessory_value_desc" => $accValDesc, "item_number" => $itemNumber, "is_active" => $row['is_active']];
            $where = ['sql_serv_id = ?' => $row['sql_serv_id']];

            $connection->update($tableName, $data, $where);

        } else if ($row['csv_action'] == 'D' && $row['sql_serv_id'] !== '') {

            $connection = $this->resourceConnection->getConnection();
            // $table is table name
            $table = $connection->getTableName($tableName);
            $query = "DELETE FROM $table WHERE sql_serv_id = \"" . $row['sql_serv_id'] . "\";";

            $connection->query($query);
        }

    }

    public function insertIntoProdAcc($row)
    {

        $tableName = 'tbl_product_accessory';

        if ($row['csv_action'] == 'C') {

            $connection = $this->resourceConnection->getConnection();
            $dateCreated = (new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
            $dateUpdated = (new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);

            $productId = $this->getRealIdBySqlId($row['pid']);
            $accessoryId = $this->getAccessoryId($row['acc_id']);

            $data = array('product_id' => $productId,
                'accessory_id' => $accessoryId,
                'sort_order' => $row['sort_order'],
                'is_active' => $row['is_active'],
                'date_created' => $dateCreated,
                'date_last_updated' => $dateUpdated,
                'sql_serv_id' => $row['sql_serv_id']);
            $connection->insert($tableName, $data);

        } else if ($row['csv_action'] == 'U' && $row['sql_serv_id'] !== '') {

            $connection = $this->resourceConnection->getConnection();

            $productId = $row['pid'];
            $accessoryId = $row['acc_id'];

            $query = "UPDATE $tableName SET product_id = \"" . $productId . "\", accessory_id = \"" . $accessoryId . "\", sort_order = \"" . $row['sort_order'] . "\", is_active = " . $row['is_active'] . " WHERE sql_serv_id = \"" . $row['sql_serv_id'] . "\";";
            $connection->query($query);
            //exit;
        } else if ($row['csv_action'] == 'D' && $row['sql_serv_id'] !== '') {

            $connection = $this->resourceConnection->getConnection();
            // $table is table name
            $table = $connection->getTableName($tableName);
            $query = "DELETE FROM $table WHERE sql_serv_id = \"" . $row['sql_serv_id'] . "\";";
            $connection->query($query);

        }

    }

    public function sanitizeValue($string)
    {

        $updatedString = $string;

        $specialCharacters = [
            "&quote;" => '"',
            "&excl;" => '!',
            "&quot;" => '"',
            "&num;" => '"',
            "&percnt;" => '%',
            "&amp;" => '&',
            "&apos;" => "'",
            "&lpar;" => '(',
            "&rpar;" => ')',
            "&ast;" => '*',
            "&comma;" => ',',
            "&period;" => '.',
            "&sol;" => '/',
            "&colon;" => ':',
            "&semi;" => ';',
            "&quest;" => '?',
            "&commat;" => '@',
            "&lbrack;" => '[',
            //"&bsol;" => "\\",
            "&rbrack;" => ']',
            "&Hat;" => '^',
            "&lowbar;" => '_',
            "&grave;" => '`',
            "&lbrace;" => '{',
            "&vert;" => '|',
            "&rbrace;" => '}'
        ];
        foreach ($specialCharacters as $key => $specialString) {

            if (str_contains($string, $key)) {
                $updatedString = str_replace($key, $specialString, $updatedString);
            }
        }

        return $updatedString;

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

    public function getAccessoryId($sqlServerId)
    {

        $accessoryId = 0;
        $tableName = 'tbl_accessory';
        $connection = $this->resourceConnection->getConnection();
        $table = $connection->getTableName($tableName);
        $select = $connection->select()
            ->from(
                ['c' => $table],
                ['*']
            )
            ->where(
                "c.sql_serv_id = :sql_serv_id"
            );
        $bind = ['sql_serv_id' => $sqlServerId];
        $result = $connection->fetchAll($select, $bind);

        if (count($result) > 0) {
            $accessoryId = $result[0]['accessory_id'];
        }

        return $accessoryId;
    }

}
