<?php

namespace Rysun\DataTransfer\Cron;

use Psr\Log\LoggerInterface;
use Magento\Framework\App\ResourceConnection;
use Rysun\DataTransfer\Helper\Data;




class FeatureImpCron
{
    protected $logger;

    protected $fileFactory;
    protected $csvProcessor;
    protected $directoryList;
    protected $resourceConnection;
    protected $helper;

    const FEATURE_IMPORT_PATH = 'archi_import/general/category_import_path';

    public function __construct(
        LoggerInterface                                  $logger,
        \Rysun\DataTransfer\Model\FeatureFactory         $Feature,
        \Rysun\DataTransfer\Model\ProductFeatureFactory  $ProductFeature,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\File\Csv                      $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList  $directoryList,
        ResourceConnection                               $resourceConnection,
        Data                        $helper
    )
    {
        $this->logger = $logger;
        $this->Feature = $Feature;
        $this->ProductFeature = $ProductFeature;
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

        $error = "error";
        try {

            $model = $this->Feature->create();

            //$fileName = '09_product_feature.csv';
            //$filePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR)
            //    . "/urapidflow/import/" . $fileName;
            
            $filePath = $this->helper->getConfigValue(SELF::FEATURE_IMPORT_PATH);
            $csv = array_map("str_getcsv", file($filePath, FILE_SKIP_EMPTY_LINES));


            foreach ($csv as $i => $row) {
                if (count($row) < 2) {
                    unset($csv[$i]);
                }
            }
            $keys = array();
            foreach ($csv as $i => $row) {
                $key = "";
                if (str_contains($row[0], '##')) {
                    $key = str_replace("##", "", $row[0]);
                    global $keys;
                    $keys[] = $key;
                    global ${$key};
                    ${$key} = $row;
                } else {
                    $arrstr = '_arr';
                    $arr = $keys[count($keys) - 1];
                    global ${$arr . $arrstr};
                    ${$arr . $arrstr}[] = $row;
                }
            }

            // File uploaded will have IDs already in the database, not just new ones

            foreach ($keys as $k => $v) {
                $keys_coll = ${$v};
                $arrstr = '_arr';
                global $csv_coll;
                $csv_coll = ${$v . $arrstr};
                global $empty_value_found;
                $empty_value_found = "empty_arr_found";
                global $error;
                $error = "error";
                global ${$v . $empty_value_found};
                ${$v . $empty_value_found} = false;
                global ${$v . $error};
                ${$v . $error} = "";
                $csv_coll_combined = "_data";
                global ${$v . $csv_coll_combined};
                ${$v . $csv_coll_combined} = array();
                foreach ($csv_coll as $i_coll => $row_coll) {
                    if (count($row_coll) > 0) {
                        // Check for discrepancies between the amount of headers and the amount of rows
                        if (count($row_coll) !== count($keys_coll)) {
                            //global ${$v.$empty_value_found};
                            ${$v . $empty_value_found} = true;
                            //global ${$v.$error};
                            ${$v . $error} .= "<br>Row " . $i_coll . "\'s length does not match the header length: " . implode(', ', $row_coll);
                        } else {
                            //$rows[] = array_combine($headers, $encoded_row);\
                            ${$v . $csv_coll_combined}[$i_coll] = array_combine($keys_coll, $row_coll);

                        }
                    }
                }
            }

            $v = 'FT';
            if (!empty(${$v . $error}) && ${$v . $error} != "") {
                echo $fileName . " file is not Imported, errors found : \n" . ${$v . $error};
                // Need to send an email because this script runs through CRON Job
                exit;
            }

            if (!empty($FT_data) && !${$v . $empty_value_found}) {
                foreach ($FT_data as $i_ft => $row_ft) {
                    //$this->createNewAttributeSet($row_eas);
                    if ($row_ft['csv_action'] == 'C') {
                        $data = array('sql_serv_id' => $row_ft['sql_serv_id'], 'feature_desc' => $row_ft['feature_desc'], 'is_active' => $row_ft['is_active']);
                        $model->setData($data);
                        $model->save();
                    } else if ($row_ft['csv_action'] == 'U' && $row_ft['sql_serv_id'] !== '') {
                        $connection = $this->resourceConnection->getConnection();
                        $table = $connection->getTableName('rysun_features');
                        $query = "UPDATE $table SET feature_desc = \"" . $row_ft['feature_desc'] . "\", is_active = \"" . $row_ft['is_active'] . "\" WHERE sql_serv_id = \"" . $row_ft['sql_serv_id'] . "\";";
                        $connection->query($query);
                    } else if ($row_ft['csv_action'] == 'D' && $row_ft['sql_serv_id'] !== '') {
                        $connection = $this->resourceConnection->getConnection();
                        $table = $connection->getTableName('rysun_features');
                        $query = "DELETE FROM $table WHERE sql_serv_id = \"" . $row_ft['sql_serv_id'] . "\";";
                        $connection->query($query);
                    }
                }
            }

            $model_pft = $this->ProductFeature->create();
            $v = 'PFT';
            if (!empty(${$v . $error}) && ${$v . $error} != "") {
                echo $fileName . " file is not Imported, errors found : \n" . ${$v . $error};
                // Need to send an email because this script runs through CRON Job
                exit;
            }
            if (!empty($PFT_data) && !${$v . $empty_value_found}) {
                foreach ($PFT_data as $i_pft => $row_pft) {
                    //$this->createNewAttributeSet($row_eas);
                    if ($row_pft['csv_action'] == 'C') {
                        $data = array('sql_serv_id' => $row_pft['sql_serv_id'], 'sql_serv_prod_id' => $row_pft['sql_serv_prod_id'], 'feature_id' => $row_pft['feature_id'], 'sort_order' => $row_pft['sort_order'], 'is_active' => $row_pft['is_active']);
                        $model_pft->setData($data);
                        $model_pft->save();
                    } else if ($row_pft['csv_action'] == 'U' && $row_pft['sql_serv_id'] !== '') {
                        $connection = $this->resourceConnection->getConnection();
                        $table = $connection->getTableName('rysun_product_features');
                        $query = "UPDATE $table SET sql_serv_prod_id = \"" . $row_pft['sql_serv_prod_id'] . "\", feature_id = \"" . $row_pft['feature_id'] . "\", sort_order = \"" . $row_pft['sort_order'] . "\", is_active = \"" . $row_pft['is_active'] . "\" WHERE sql_serv_id = \"" . $row_pft['sql_serv_id'] . "\";";
                        $connection->query($query);
                    } else if ($row_pft['csv_action'] == 'D' && $row_pft['sql_serv_id'] !== '') {
                        $connection = $this->resourceConnection->getConnection();
                        $table = $connection->getTableName('rysun_product_features');
                        $query = "DELETE FROM $table WHERE sql_serv_id = \"" . $row_pft['sql_serv_id'] . "\";";
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
