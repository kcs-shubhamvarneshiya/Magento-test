<?php

namespace Rysun\DataTransfer\Cron;

use Psr\Log\LoggerInterface;
use Magento\Framework\App\ResourceConnection;
use Rysun\DataTransfer\Helper\Data;



class PricingImpCron
{
    protected $logger;

    protected $fileFactory;
    protected $csvProcessor;
    protected $directoryList;
    protected $resourceConnection;
    protected $helper;

    const PRICING_IMPORT_PATH = 'archi_import/general/category_import_path';



    public function __construct(
        LoggerInterface                                  $logger,
        \Rysun\DataTransfer\Model\ProductPricingFactory  $ProductPricing,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\File\Csv                      $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList  $directoryList,
        ResourceConnection                               $resourceConnection,
        Data                        $helper

    )
    {
        $this->logger = $logger;
        $this->ProductPricing = $ProductPricing;
        $this->fileFactory = $fileFactory;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        $this->resourceConnection = $resourceConnection;
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
            $model = $this->ProductPricing->create();

            //$fileName = '08_product_price.csv';
            //$filePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR)
            //    . "/urapidflow/import/" . $fileName;
            
            $filePath = $this->helper->getConfigValue(SELF::PRICING_IMPORT_PATH);

          
            $csv = array_map("str_getcsv", file($filePath, FILE_SKIP_EMPTY_LINES));

            $keys = array_shift($csv);


            // File uploaded will have IDs already in the database, not just new ones
            if (!empty($model)) {
                //$connection = $model->getResource()->getConnection();
                $tableName = $model->getResource()->getMainTable();
                //$connection->truncateTable($tableName);
            }
            $empty_value_found = false;
            $err = "";
            foreach ($csv as $i => $row) {
                if (count($row) > 0) {
                    // Check for discrepancies between the amount of headers and the amount of rows
                    if (count($row) !== count($keys)) {
                        $empty_value_found = true;
                        $err .= "<br>Row " . $i . "\'s length does not match the header length: " . implode(', ', $row);
                    } else {
                        //$rows[] = array_combine($headers, $encoded_row);\
                        $csv[$i] = array_combine($keys, $row);
                        if (empty($csv[$i]['product_sku'])
                            || empty($csv[$i]['price'])
                        ) {
                            $empty_value_found = true;
                            $err .= "<br>Row " . $i . " hase empty field please check.";
                        }
                    }
                }
            }
            if ($err != "") {
                echo $fileName . " file is not Imported, errors found : \n" . $err;
                // Need to send an email because this script runs through CRON Job
                exit;
            }

            if (!$empty_value_found) {
                foreach ($csv as $i => $row) {

                    // PENDING : FETCH SQL SERVER PROD ID from Product's Attribute field and Add it in pid column HERE

                    if ($row['csv_action'] == 'C') {
                        $data = array('sql_serv_id' => $row['sql_serv_id'], 'sql_serv_prod_id' => $row['sql_serv_prod_id'], 'product_sku' => $row['product_sku'], 'price' => $row['price']);
                        $model->setData($data);
                        $model->save();
                    } else if ($row['csv_action'] == 'U' && $row['sql_serv_id'] !== '') {
                        $connection = $this->resourceConnection->getConnection();
                        $table = $connection->getTableName($tableName);
                        $query = "UPDATE $table SET sql_serv_prod_id = \"" . $row['sql_serv_prod_id'] . "\", product_sku = \"" . $row['product_sku'] . "\", price = \"" . $row['price'] . "\" WHERE sql_serv_id = \"" . $row['sql_serv_id'] . "\";";
                        $connection->query($query);
                    } else if ($row['csv_action'] == 'D' && $row['sql_serv_id'] !== '') {
                        $connection = $this->resourceConnection->getConnection();
                        $table = $connection->getTableName($tableName);
                        $query = "DELETE FROM $table WHERE sql_serv_id = \"" . $row['sql_serv_id'] . "\";";
                        $connection->query($query);
                    }
                }
            }
        } catch (Exception $e) {

            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/nasmlog.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);
            $error_message = "Unable to read csv file. Error: " . $e->getMessage() . '. See exception.log for full error log.';

            $this->messageManager->addError($error_message);
            $logger->info($e);
        }
    }
}
