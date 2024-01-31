<?php

namespace Rysun\DataTransfer\Cron;

use Psr\Log\LoggerInterface;
use Magento\Framework\App\ResourceConnection;
use Rysun\ArchiCollection\Model\ArchiCollectionFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\CategoryFactory;
use Rysun\DataTransfer\Helper\Data;


class ProductCollectionImpCron
{

    protected $logger;

    protected $fileFactory;
    protected $csvProcessor;
    protected $directoryList;
    protected $resourceConnection;
    protected $collectionPlatform;
    protected $timezoneInterface;
    protected $productCollection;
    protected $categoryFactory;
    protected $helper;

    const PRODUCT_COLLECTION_IMPORT_PATH = 'archi_import/general/product_collection_import_path';


    public function __construct(
        LoggerInterface                                  $logger,
        //\Kcs\Pacjson\Model\PacjsonFactory $Pacjson,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\File\Csv                      $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList  $directoryList,
        ResourceConnection                               $resourceConnection,
        ArchiCollectionFactory                           $collectionPlatform,
        TimezoneInterface                                $timezoneInterface,
        CollectionFactory                                $productCollection,
        CategoryFactory                                  $categoryFactory,
        Data                        $helper
    )
    {
        $this->logger = $logger;
        //$this->Pacjson = $Pacjson;
        $this->fileFactory = $fileFactory;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        $this->resourceConnection = $resourceConnection;
        $this->collectionPlatform = $collectionPlatform;
        $this->timezoneInterface = $timezoneInterface;
        $this->productCollection = $productCollection;
        $this->categoryFactory = $categoryFactory;
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
            $model = $this->collectionPlatform->create();

            //$fileName = '02_collections.csv';
            //$filePath = "var/urapidflow/import/" . $fileName;

            $filePath = $this->helper->getConfigValue(SELF::PRODUCT_COLLECTION_IMPORT_PATH);

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
                //print_r($row);exit;
                if ($row['csv_action'] == 'C') {
                    $connection = $this->resourceConnection->getConnection();
                    $dateCreated = (new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
                    $dateUpdated = (new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);

                    $tableName = $model->getResource()->getMainTable();

                    $name = $this->sanitizeValue($row['collection_name']);
                    $desc = $this->sanitizeValue($row['collection_description']);
                    $secondDesc = $this->sanitizeValue($row['collection_description2']);
                    $webName = $this->sanitizeValue($row['web_name']);

                    $mageCatId = $this->findMagentoIdBySqlServId($row['category_id']);
                    $catId = ($mageCatId) ? $mageCatId->getId() : $row['category_id'];

                    $data = array('collection_name' => $name,
                        'collection_desc' => $desc,
                        'collection_desc_second' => $secondDesc,
                        'web_name' => $webName,
                        'sort_order' => $row['sort_order'],
                        'category_id' => $catId,
                        //'is_active' => $row['is_active'],
                        //'thumbnail_image' => $row['collection_image'],
                        'date_created' => $dateCreated,
                        'date_last_updated' => $dateUpdated,
                        'sql_serv_id' => $row['sql_serv_id']);
                    $connection->insert($tableName, $data);
                    echo ' Recorded Added || ';
                    //exit('comine here');

                } else if ($row['csv_action'] == 'U' && $row['sql_serv_id'] !== '') {

                    $connection = $this->resourceConnection->getConnection();
                    $tableName = $model->getResource()->getMainTable();
                    // $table is table name
                    $table = $connection->getTableName($tableName);

                    $name = $this->sanitizeValue($row['collection_name']);
                    $desc = $this->sanitizeValue($row['collection_description']);
                    $secondDesc = $this->sanitizeValue($row['collection_description2']);
                    $webName = $this->sanitizeValue($row['web_name']);
                    $mageCatId = $this->findMagentoIdBySqlServId($row['category_id']);
                    $catId = ($mageCatId) ? $mageCatId->getId() : $row['category_id'];


                    $data = [
                        "collection_name" => $name,
                        "collection_desc" => $desc,
                        "collection_desc_second" => $secondDesc,
                        "web_name" => $webName,
                        "category_id" => $catId,
                        'sort_order' => $row['sort_order'],
                        'thumbnail_image' => $row['collection_image'],
                        //'is_active' => $row['is_active']
                    ];
                    $where = ['sql_serv_id = ?' => $row['sql_serv_id']];

                    $connection->update($tableName, $data, $where);

                } else if ($row['csv_action'] == 'D' && $row['sql_serv_id'] !== '') {

                    $tableName = $model->getResource()->getMainTable();
                    $connection = $this->resourceConnection->getConnection();
                    // $table is table name
                    $table = $connection->getTableName($tableName);
                    $query = "DELETE FROM $table WHERE sql_serv_id = \"" . $row['sql_serv_id'] . "\";";
                    //exit;
                    $connection->query($query);
                    echo ' Recorded Deleted || ';
                    //exit;
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


    public function findMagentoIdBySqlServId($sqlServId)
    {

        $category = $this->categoryFactory->create();
        $cate = $category->getCollection()
            ->addAttributeToSelect('sql_serv_id')
            ->addAttributeToFilter('sql_serv_id', $sqlServId)
            ->getFirstItem();

        if ($cate->getId()) {
            return $cate;
        }

        return false;
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
                //echo ' matched ';
                //exit('coming here');
                $updatedString = str_replace($key, $specialString, $updatedString);
            }
        }

        return $updatedString;

    }

}
