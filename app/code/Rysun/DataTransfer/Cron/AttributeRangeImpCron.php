<?php

namespace Rysun\DataTransfer\Cron;

use Magento\Framework\Setup\ModuleDataSetupInterface;

//use Magento\Framework\Setup\ModuleContextInterface;

use Psr\Log\LoggerInterface;
use Magento\Framework\App\ResourceConnection;
use Rysun\AttributeRange\Model\AttributeRangeFactory;
use Rysun\AttributeRange\Model\AttributeRange;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\CategoryFactory;
use Amasty\ShopbyBase\Api\Data\FilterSettingRepositoryInterface;
use Rysun\DataTransfer\Helper\Data;



class AttributeRangeImpCron
{

    protected $logger;

    protected $fileFactory;
    protected $csvProcessor;
    protected $directoryList;
    protected $resourceConnection;
    protected $attributeRangeCollection;
    protected $timezoneInterface;
    protected $productCollection;
    protected $categoryFactory;
    protected $attributeRangeModel;
    protected $helper;

    const ATTRIBUTE_RANGE_IMPORT_PATH = 'archi_import/general/attribute_range_import_path';

    /**
     * @var FilterSettingRepositoryInterface
     */

    private $filterSettingRepository;


    public function __construct(
        LoggerInterface                                  $logger,
        //\Kcs\Pacjson\Model\PacjsonFactory $Pacjson,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\File\Csv                      $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList  $directoryList,
        ResourceConnection                               $resourceConnection,
        AttributeRangeFactory                            $attributeRangeCollection,
        AttributeRange                                   $attributeRangeModel,
        TimezoneInterface                                $timezoneInterface,
        CollectionFactory                                $productCollection,
        CategoryFactory                                  $categoryFactory,
        FilterSettingRepositoryInterface                 $filterSettingRepository,
        Data                        $helper
    )
    {
        $this->logger = $logger;
        //$this->Pacjson = $Pacjson;
        $this->fileFactory = $fileFactory;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        $this->resourceConnection = $resourceConnection;
        $this->attributeRangeCollection = $attributeRangeCollection;
        $this->timezoneInterface = $timezoneInterface;
        $this->productCollection = $productCollection;
        $this->categoryFactory = $categoryFactory;
        $this->attributeRangeModel = $attributeRangeModel;
        $this->filterSettingRepository = $filterSettingRepository;
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
            $model = $this->attributeRangeCollection->create();
            //$fileName = '05_attribute_range.csv';
            //$filePath = "var/urapidflow/import/" . $fileName;

            $filePath = $this->helper->getConfigValue(SELF::ATTRIBUTE_RANGE_IMPORT_PATH);
            $csv = array_map("str_getcsv", file($filePath, FILE_SKIP_EMPTY_LINES));

            $keys = array_shift($csv);


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

                    // Import Data into custom table
                    $connection = $this->resourceConnection->getConnection();
                    $dateCreated = (new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
                    $dateUpdated = (new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);

                    $tableName = $model->getResource()->getMainTable();
                    $rangeDesc = $this->sanitizeValue($row['attribute_range_desc']);
                    $data = array('attribute_range_desc' => $rangeDesc,
                        'min_value' => $row['min_value'],
                        'max_value' => $row['max_value'],
                        'attribute_code' => $row['attribute_code'],
                        'attribute_code_new' => $row['attribute_code'],
                        'sort_order' => $row['sort_order'],
                        'sql_serv_id' => $row['sql_serv_id']);
                    $connection->insert($tableName, $data);

                } else if ($row['csv_action'] == 'U' && $row['sql_serv_id'] !== '') {

                    $connection = $this->resourceConnection->getConnection();
                    $tableName = $model->getResource()->getMainTable();
                    // $table is table name
                    $table = $connection->getTableName($tableName);

                    $rangeDesc = $this->sanitizeValue($row['attribute_range_desc']);

                    $data = array('attribute_range_desc' => $rangeDesc,
                        'min_value' => $row['min_value'],
                        'max_value' => $row['max_value'],
                        'attribute_code' => $row['attribute_code'],
                        'attribute_code_new' => $row['attribute_code_new'],
                        'sort_order' => $row['sort_order']);
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

            //Creating new attribute with range Start

            $collectionRange = $this->attributeRangeModel->getCollection();
            $attributeOption = [];
            foreach ($collectionRange as $collectionItem) {
                //print_r($collectionItem->debug());
                $attributeOption[$collectionItem['attribute_code']][] = $collectionItem['attribute_range_desc'];
            }

            //If attribute not exist, then create a new one
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            $eavSetup = $objectManager->get('Magento\Eav\Setup\EavSetupFactory');
            $setup = $objectManager->get('Magento\Framework\Setup\ModuleDataSetupInterface');

            $eavSetup1 = $eavSetup->create(['setup' => $setup]);
            $productEntity = \Magento\Catalog\Model\Product::ENTITY;

            foreach ($attributeOption as $key => $value) {

                $eavConfig = $objectManager->get('\Magento\Eav\Model\Config');
                $attribute = $eavConfig->getAttribute('catalog_product', $key);
                $newAttributeCode = $key . "_range";
                $name = $attribute->getFrontendLabel();

                // ADD new attribute with range
                $eavSetup1->addAttribute(
                    $productEntity,
                    $newAttributeCode,
                    [
                        'type' => 'text',
                        'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                        'frontend' => '',
                        'label' => $name,
                        'input' => 'multiselect',
                        'class' => '',
                        'source' => '',
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL, // can also use \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE, // scope of the attribute (global, store, website)
                        'visible' => true,
                        'required' => false,
                        'is_html_allowed_on_front' => true,
                        'is_used_for_price_rules' => false,
                        'is_filterable_in_search' => true,
                        'is_visible_in_advanced_search' => false,
                        'is_wysiwyg_enabled' => false,
                        'used_for_sort_by' => false,
                        'user_defined' => true,
                        'default' => '',
                        'searchable' => false,
                        //'filterable' => $row_eaxp[0]['is_filterable'],
                        'filterable' => true,
                        'comparable' => false,
                        'visible_on_front' => true,
                        'used_in_product_listing' => true,
                        'unique' => false,
                        'apply_to' => '',
                        'attribute_set' => 'SKU Builder Set', // assigning the attribute to the attribute set "MyCustomAttributeSet"
                        'group' => 'Attribute Filters'
                    ]
                );

                $result = $this->updateAmastyMultiselect($newAttributeCode);

                // Adding attribute options START
                /**
                 * Add options if needed
                 */
                $data['options'] = $attributeOption[$key];
                if (isset($data['options'])) {
                    $options = [
                        'attribute_id' => $eavSetup1->getAttributeId($productEntity, $newAttributeCode),
                        'values' => $data['options']
                    ];
                    $eavSetup1->addAttributeOption($options);
                }
                // Adding options END

                // Updated original attribute to disable for filter visibility
                $eavConfig = $objectManager->get('\Magento\Eav\Model\Config');
                $attribute = $eavConfig->getAttribute('catalog_product', $key);
                $attribute->setIsFilterable(false);
                $attribute->save();
                //Code End for disable visibility of original code from filter

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

    public function updateAmastyMultiselect($attributeCode)
    {

        try {
            $filter = $this->filterSettingRepository->getByAttributeCode($attributeCode);
            $filter->setIsMultiselect(true);
            $filter->setIsUseAndLogic(true);
            $this->filterSettingRepository->save($filter);
            return true;
        } catch (\Exception) {
            return false;
        }
    }

}
