<?php

namespace Rysun\DataTransfer\Cron;

use Psr\Log\LoggerInterface;
use Magento\Framework\App\ResourceConnection;
use Rysun\DataTransfer\Helper\Data;




class ProductExceptionImpCron
{
    protected $logger;

    protected $fileFactory;
    protected $csvProcessor;
    protected $directoryList;
    protected $resourceConnection;
    protected $helper;

    const PRODUCT_EXCEPTION_IMPORT_PATH = 'archi_import/general/product_exception_import_path';

    public function __construct(
        LoggerInterface                                  $logger,
        \Kcs\Pacjson\Model\PacjsonFactory                $Pacjson,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\File\Csv                      $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList  $directoryList,
        ResourceConnection                               $resourceConnection,
        Data                        $helper
    )
    {
        $this->logger = $logger;
        $this->Pacjson = $Pacjson;
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
        //$this->logger->info('Cron Works');

        try {
            $model = $this->Pacjson->create();

            //$fileName = '06_exception.csv';
            //$filePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR)
            //    . "/urapidflow/import/" . $fileName;
            
            $filePath = $this->helper->getConfigValue(SELF::PRODUCT_EXCEPTION_IMPORT_PATH);
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
            if ($err != "") {
                echo $fileName . " file is not Imported, errors found : \n" . $err;
                exit;
            }

            if (!$empty_value_found) {
                foreach ($csv as $i => $row) {

                    // PENDING : FETCH SQL SERVER PROD ID from Product's Attribute field and Add it in pid column HERE

                    //sanitize value
                    $pName = $this->sanitizeValue($row['pname']);
                    $attributeCombination = $this->sanitizeValue($row['attribute_combination']);
                    $optionCombinationJson = $this->sanitizeValue($row['option_combination_json']);

                    if ($row['csv_action'] == 'C') {
                        $data = array('pid' => $row['pid'], 'pname' => $pName, 'attribute_combination' => $attributeCombination, 'option_combination_json' => $optionCombinationJson, 'status' => $row['status'], 'sql_serv_id' => $row['sql_serv_id'], 'sql_serv_prod_id' => $row['sql_serv_prod_id']);
                        $model->setData($data);
                        $model->save();
                    } else if ($row['csv_action'] == 'U' && $row['sql_serv_id'] !== '') {
                        $connection = $this->resourceConnection->getConnection();
                        $table = $connection->getTableName($tableName);
                        $query = "UPDATE $table SET pid = \"" . $row['pid'] . "\", pname = \"" . $pName . "\", attribute_combination = \"" . $attributeCombination . "\", option_combination_json = \"" . $optionCombinationJson . "\", status = \"" . $row['status'] . "\" WHERE sql_serv_id = \"" . $row['sql_serv_id'] . "\";";
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
            "&bsol;" => "\\",
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
