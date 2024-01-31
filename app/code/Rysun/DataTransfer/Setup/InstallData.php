<?php

namespace Rysun\DataTransfer\Setup;

use Psr\Log\LoggerInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory;
use Magento\Eav\Api\Data\AttributeGroupInterfaceFactory;
use Magento\Eav\Api\AttributeGroupRepositoryInterface;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory as AttributeGroupCollectionFactory;
use Magento\Framework\App\State;
use Magento\Eav\Api\Data\AttributeInterface;
use Amasty\ShopbyBase\Api\Data\FilterSettingRepositoryInterface;

use Rysun\DataTransfer\Helper\Data;



class InstallData implements InstallDataInterface
{
    protected $logger;

    protected $fileFactory;
    protected $csvProcessor;
    protected $directoryList;
    protected $resourceConnection;
    protected $_attributeSetCollection;
    protected $helper;

    const ATTRIBUTE_IMPORT_PATH = 'archi_import/general/attribute_import_path';



    /**
     * EAV setup factory
     *
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    protected $eavSetupFactory;

    protected $setup;

    /**
     * Attribute set factory
     *
     * @var SetFactory
     */
    protected $attributeSetFactory;

    /**
     * @var AttributeGroupInterfaceFactory
     */
    private $attributeGroupFactory;

    /**
     * @var AttributeGroupRepositoryInterface
     */
    private $attributeGroupRepository;

    private $attributeGroupCollectionFactory;

    protected $_eavSetup;

    protected $_option;


    /**
     * @var \Magento\Catalog\Api\ProductAttributeRepositoryInterface
     */
    protected $attributeRepository;

    protected $attributeRepositoryInterface;

    /**
     * @var array
     */
    protected $attributeValues;

    /**
     * @var \Magento\Eav\Model\Entity\Attribute\Source\TableFactory
     */
    protected $tableFactory;

    /**
     * @var \Magento\Eav\Api\AttributeOptionManagementInterface
     */
    protected $attributeOptionManagement;

    /**
     * @var \Magento\Eav\Api\Data\AttributeOptionLabelInterfaceFactory
     */
    protected $optionLabelFactory;

    /**
     * @var \Magento\Eav\Api\Data\AttributeOptionInterfaceFactory
     */
    protected $optionFactory;

    protected $attributeOptionInterface;

    protected $_attributeRepository;

    protected $_attributeOptionManagement;

    protected $attributeOptionFactory;

    protected $_attributeOptionLabel;

    protected $storeRepository;

    protected $appState;

    private $_objectManager;

    /**
     * @var FilterSettingRepositoryInterface
     */
    private $filterSettingRepository;

    public function __construct(
        LoggerInterface                                            $logger,
        \Magento\Framework\App\Response\Http\FileFactory           $fileFactory,
        \Magento\Framework\File\Csv                                $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList            $directoryList,
        ResourceConnection                                         $resourceConnection,
        EavSetupFactory                                            $eavSetupFactory,
        SetFactory                                                 $attributeSetFactory,
        CollectionFactory                                          $attributeSetCollection,
        AttributeGroupInterfaceFactory                             $attributeGroupFactory,
        AttributeGroupRepositoryInterface                          $attributeGroupRepository,
        AttributeGroupCollectionFactory                            $attributeGroupCollectionFactory,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface   $attributeRepositoryInterface,
        \Magento\Eav\Model\Entity\Attribute\Source\TableFactory    $tableFactory,
        \Magento\Eav\Api\Data\AttributeOptionLabelInterfaceFactory $optionLabelFactory,
        \Magento\Eav\Api\Data\AttributeOptionInterfaceFactory      $optionFactory,
        \Magento\Eav\Api\Data\AttributeOptionInterface             $attributeOptionInterface,
        \Magento\Eav\Model\AttributeRepository                     $attributeRepository,
        \Magento\Eav\Api\AttributeOptionManagementInterface        $attributeOptionManagement,
        \Magento\Eav\Model\Entity\Attribute\OptionFactory          $attributeOptionFactory,
        \Magento\Eav\Api\Data\AttributeOptionLabelInterface        $attributeOptionLabel,
        \Magento\Eav\Model\Entity\Attribute\Option                 $option,
        \Magento\Store\Api\StoreRepositoryInterface                $storeRepository,
        State                                                      $appState,
        \Magento\Framework\ObjectManagerInterface                  $objectmanager,
        FilterSettingRepositoryInterface                           $filterSettingRepository,
        Data                        $helper

    )
    {
        $this->logger = $logger;
        $this->fileFactory = $fileFactory;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        $this->resourceConnection = $resourceConnection;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->_attributeSetCollection = $attributeSetCollection;
        $this->attributeGroupFactory = $attributeGroupFactory;
        $this->attributeGroupRepository = $attributeGroupRepository;
        $this->attributeGroupCollectionFactory = $attributeGroupCollectionFactory;
        $this->attributeOptionInterface = $attributeOptionInterface;
        $this->attributeRepository = $attributeRepository;
        $this->attributeRepositoryInterface = $attributeRepositoryInterface;
        $this->tableFactory = $tableFactory;
        $this->attributeOptionManagement = $attributeOptionManagement;
        $this->optionLabelFactory = $optionLabelFactory;
        $this->optionFactory = $optionFactory;
        $this->_option = $option;
        $this->_attributeRepository = $attributeRepository;
        $this->_option = $option;
        $this->attributeOptionFactory = $attributeOptionFactory;
        $this->_attributeOptionLabel = $attributeOptionLabel;
        $this->storeRepository = $storeRepository;
        $this->appState = $appState;
        $this->_objectManager = $objectmanager;
        $this->resourceConnection = $resourceConnection;
        $this->filterSettingRepository = $filterSettingRepository;
        $this->helper = $helper;

    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context, $run_from_cron = "0")
    {
        if ($run_from_cron == "1") {
            $setup->startSetup();
            $this->_eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

            try {

                //$fileName = '04_attribute.csv';
                //$filePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR)
                //    . "/urapidflow/import/" . $fileName;

                $filePath = $this->helper->getConfigValue(SELF::ATTRIBUTE_IMPORT_PATH);
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

                $v = 'EAS';
                if (!empty(${$v . $error}) && ${$v . $error} != "") {
                    echo $fileName . " file is not Imported, errors found : \n" . ${$v . $error};
                    // Need to send an email because this script runs through CRON Job
                    //mail("nirav.modi@kcsitglobal.com", $fileName." file is not Imported through CornJob, errors found.", $err);
                    exit;
                }
                if (!empty($EAS_data) && !${$v . $empty_value_found}) {

                    foreach ($EAS_data as $i_eas => $row_eas) {

                        $this->createNewAttributeSet($row_eas);
                    }
                    //exit('outside for');
                }
                //exit('outside');

                $v = 'EAG';
                if (!empty(${$v . $error}) && ${$v . $error} != "") {
                    echo $fileName . " file is not Imported, errors found : \n" . ${$v . $error};
                    // Need to send an email because this script runs through CRON Job
                    //mail("nirav.modi@kcsitglobal.com", $fileName." file is not Imported through CornJob, errors found.", $err);
                    exit;
                }
                if (!empty($EAG_data) && !${$v . $empty_value_found}) {

                    foreach ($EAG_data as $i_eag => $row_eag) {

                        $this->createNewAttributeGroup($row_eag);
                    }
                }

                $v = 'EA';
                if (!empty(${$v . $error}) && ${$v . $error} != "") {
                    echo $fileName . " file is not Imported, errors found : \n" . ${$v . $error};
                    // Need to send an email because this script runs through CRON Job
                    //mail("nirav.modi@kcsitglobal.com", $fileName." file is not Imported through CornJob, errors found.", $err);
                    exit;
                }
                //print_r($EA_data);

                if (!empty($EA_data) && !${$v . $empty_value_found}) {
                    foreach ($EA_data as $i_ea => $row_ea_loop) {
                        //$row_ea = $row_ea_loop;
                        if ($row_ea_loop['csv_action'] == 'C') {
                            $row_atr = $row_ea_loop;
                            //global $EAX_data, $EAXP_data, $EASI_data, $EAO_data, $EAOL_data;

                            $row_eax = array();
                            if (!empty($EAX_data)) {
                                $row_eax = $this->searchForRow('attribute_code', $row_atr['attribute_code'], $EAX_data); // Only one record
                            }

                            $row_eaxp = array();
                            if (!empty($EAXP_data)) {
                                $row_eaxp = $this->searchForRow('attribute_code', $row_atr['attribute_code'], $EAXP_data); // Only one record
                            }

                            $row_easi = array();
                            if (!empty($EASI_data)) {
                                $row_easi = $this->searchForRow('attribute_code', $row_atr['attribute_code'], $EASI_data); // Only one record
                            }

                            $row_eao = array();
                            if (!empty($EAO_data)) {
                                $row_eao = $this->searchForRow('attribute_code', $row_atr['attribute_code'], $EAO_data); // Multiple records
                            }

                            $row_eaol = array();
                            if (!empty($EAOL_data)) {
                                $row_eaol = $this->searchForRow('attribute_code', $row_atr['attribute_code'], $EAOL_data); // Multiple records
                            }
                            //global $this->_eavSetup;

                            $attribute_obj = $this->getAttribute($row_atr['attribute_code']);
                            if (is_object($attribute_obj)) {
                                $attribute_id = $attribute_obj->getAttributeId();
                            } else {
                                $attribute_id = false;
                            }
                            if (!$attribute_id) {
                                $label = $this->sanitizeValue($row_atr['frontend_label']);
                                $this->_eavSetup->addAttribute(
                                    \Magento\Catalog\Model\Product::ENTITY,
                                    $row_atr['attribute_code'],
                                    [
                                        'type' => $row_atr['backend_type'],
                                        'backend' => $row_eax[0]['backend_model'],
                                        'frontend' => $row_eax[0]['frontend_model'],
                                        'label' => $label,
                                        'input' => $row_atr['frontend_input'],
                                        'class' => '',
                                        'source' => $row_eax[0]['source_model'],
                                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL, // can also use \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE, // scope of the attribute (global, store, website)
                                        'visible' => $row_eaxp[0]['is_visible'],
                                        'required' => $row_atr['is_required'],
                                        'is_html_allowed_on_front' => $row_eaxp[0]['is_html_allowed_on_front'],
                                        'is_used_for_price_rules' => $row_eaxp[0]['is_used_for_price_rules'],
                                        'is_filterable_in_search' => $row_eaxp[0]['is_filterable_in_search'],
                                        'is_visible_in_advanced_search' => $row_eaxp[0]['is_visible_in_advanced_search'],
                                        'is_wysiwyg_enabled' => $row_eaxp[0]['is_wysiwyg_enabled'],
                                        'used_for_sort_by' => $row_eaxp[0]['used_for_sort_by'],
                                        'user_defined' => true,
                                        'default' => $row_eax[0]['default_value'],
                                        'searchable' => $row_eaxp[0]['is_searchable'],
                                        'filterable' => $row_eaxp[0]['is_filterable'],
                                        'comparable' => $row_eaxp[0]['is_comparable'],
                                        'visible_on_front' => $row_eaxp[0]['is_visible_on_front'],
                                        'used_in_product_listing' => $row_eaxp[0]['used_in_product_listing'],
                                        'unique' => $row_atr['is_unique'],
                                        'apply_to' => $row_eaxp[0]['apply_to'],
                                        'attribute_set' => $row_easi[0]['set_name'], // assigning the attribute to the attribute set "MyCustomAttributeSet"
                                        'group' => $row_easi[0]['group_name']
                                        //'option' => $attr_option
                                    ]
                                );


                                if ($row_atr['frontend_input'] == "multiselect") {

                                    $this->updateAmastyMultiselect($row_atr['attribute_code']);
                                    $attr_option = array();
                                    foreach ($row_eao as $row_eao_k => $row_eao_v) {
                                        if ($row_eao_v['csv_action'] == 'C') {
                                            $option_id = $this->createOrUpdateAttributeOption($row_eao_v);
                                        } else if ($row_eao_v['csv_action'] == 'U' && $row_eao_v['sql_serv_id'] !== "") {
                                            // Find attribute option by sql_serv_id, and replace it's value

                                        }
                                    }
                                }


                            }
                        } else if ($row_ea_loop['csv_action'] == 'U') {

                            // Require to update some of the things
                            $row_atr = $row_ea_loop;
                            //global $EAX_data, $EAXP_data, $EASI_data, $EAO_data, $EAOL_data;
                            $row_eax = array();
                            if (!empty($EAX_data)) {
                                $row_eax = $this->searchForRow('attribute_code', $row_atr['attribute_code'], $EAX_data); // Only one record
                            }

                            $row_eaxp = array();
                            if (!empty($EAXP_data)) {
                                $row_eaxp = $this->searchForRow('attribute_code', $row_atr['attribute_code'], $EAXP_data); // Only one record
                            }

                            $row_easi = array();
                            if (!empty($EASI_data)) {
                                $row_easi = $this->searchForRow('attribute_code', $row_atr['attribute_code'], $EASI_data); // Only one record
                            }

                            $row_eao = array();
                            if (!empty($EAO_data)) {
                                $row_eao = $this->searchForRow('attribute_code', $row_atr['attribute_code'], $EAO_data); // Multiple records
                            }

                            $row_eaol = array();
                            if (!empty($EAOL_data)) {
                                $row_eaol = $this->searchForRow('attribute_code', $row_atr['attribute_code'], $EAOL_data); // Multiple records
                            }


                            $attribute_obj = $this->getAttribute($row_atr['attribute_code']);
                            if (is_object($attribute_obj)) {
                                $attribute_id = $attribute_obj->getAttributeId();
                            } else {
                                $attribute_id = false;
                            }

                            if ($attribute_id) {
                                $this->_eavSetup->updateAttribute(
                                    \Magento\Catalog\Model\Product::ENTITY,
                                    $row_atr['attribute_code'],
                                    [
                                        'frontend_label' => $row_atr['frontend_label']
                                    ]
                                );


                                if ($row_atr['frontend_input'] == "multiselect") {

                                    $this->updateAmastyMultiselect($row_atr['attribute_code']);
                                    $attr_option = array();
                                    foreach ($row_eao as $row_eao_k => $row_eao_v) {
                                        if ($row_eao_v['csv_action'] == 'U') {
                                            echo "CAlled inside if condition of check csv_action...";
                                            $option_id = $this->updateAttributeOption($row_eao_v);
                                        } else if ($row_eao_v['csv_action'] == 'U' && $row_eao_v['sql_serv_id'] !== "") {
                                            // Find attribute option by sql_serv_id, and replace it's value
                                        }
                                    }
                                }
                            }
                        } else if ($row_ea_loop['csv_action'] == 'D' && $row_ea_loop['sql_serv_id'] !== '') {

                            //echo $row_ea_loop['attribute_code'];
                            $attribute_obj = $this->getAttribute($row_ea_loop['attribute_code']);

                            if (is_object($attribute_obj)) {
                                $attribute_id = $attribute_obj->getAttributeId();
                                //print_r($attribute_id->getData('sql_serv_id'));

                            } else {
                                $attribute_id = false;
                            }

                            if ($attribute_id) {
                                //exit('coming here');
                                $newSetup = $this->eavSetupFactory->create(['setup' => $setup]);
                                //print_r($newSetup->getAttributeId(\Magento\Catalog\Model\Product::ENTITY, $row_ea_loop['attribute_code']));
                                //exit('coming');
                                if ($newSetup->getAttributeId(\Magento\Catalog\Model\Product::ENTITY, $row_ea_loop['attribute_code'])) {
                                    $newSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, $row_ea_loop['attribute_code']);
                                }


                            }
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
            $setup->endSetup();
        }
    }

    public function updateAttributeOption($row_eao)
    {
        $connection = $this->resourceConnection->getConnection();
        $table = $connection->getTableName('eav_attribute_option_value');

        // Does it already exist?
        //$optionId = $this->getOptionId($row_eao);

        if ($row_eao['sql_serv_id'] !== "") {
            // If no, add it.

            // Fetch EAOL array with multiple lines
            global $EAOL_data;
            $optionNameSanitized = $this->sanitizeValue($row_eao['option_name']);
            $row_eaol = $this->searchForRow('option_name', $optionNameSanitized, $EAOL_data);
            $optionLabels = array();
            $query = array();
            foreach ($row_eaol as $row_eaol_k => $row_eaol_v) {
                if ($row_eaol_v['csv_action'] == "U" && $row_eaol_v['sql_serv_id'] != "") {

                    if (strlen($row_eaol_v['option_label']) < 1) {
                        return 'Label for ' . $row_eao['attribute_code'] . ' must not be empty.';
                    }

                    $store_id = $this->getStoreIdByCode($row_eaol_v['store']);

                    $optionLabelSanitized = $this->sanitizeValue($row_eaol_v['option_label']);

                    $data = ["value" => $optionLabelSanitized];
                    $where = ['sql_serv_id = ?' => $row['sql_serv_id']];

                    $connection->update($table, $data, $where);


                    //$query = "UPDATE " . $table . " SET value = '".$optionLabelSanitized."' WHERE sql_serv_id = '".$row_eaol_v['sql_serv_id']."';";
                    $connection->query($query);
                }
            }
        }
    }

    public function createNewAttributeSet($row_set)
    {
        $newAttrSetId = $this->getAttrSetId($row_set['set_name']);

        if ($newAttrSetId == "") {
            /**
             * Create a New Attribute Set
             */
            //global $this->_eavSetup;
            $attributeSet = $this->attributeSetFactory->create();
            $entityTypeId = $this->_eavSetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $attributeSetId = $this->_eavSetup->getDefaultAttributeSetId($entityTypeId); // default attribute set

            $data = [
                'attribute_set_name' => $row_set['set_name'],
                'entity_type_id' => $entityTypeId,
                'sort_order' => $row_set['sort_order'],
            ];
            $attributeSet->setData($data);
            $attributeSet->validate();
            $attributeSet->save();
            $attributeSet->initFromSkeleton($attributeSetId);
            $attributeSet->save();
            return true;
        }
        return;
    }

    public function getAttrSetId($attrSetName)
    {
        $attributeSet = $this->_attributeSetCollection->create()->addFieldToSelect(
            '*'
        )->addFieldToFilter(
            'attribute_set_name',
            $attrSetName
        );
        $attributeSetId = null;
        foreach ($attributeSet as $attr):
            $attributeSetId = $attr->getAttributeSetId();
        endforeach;
        return $attributeSetId;
    }

    public function createNewAttributeGroup($row_grp)
    {
        $attributeSetId = $this->getAttrSetId($row_grp['set_name']);
        $groupCollection = $this->attributeGroupCollectionFactory->create()
            ->setAttributeSetFilter($attributeSetId)
            ->load();
        $group_exist_flg = false;
        foreach ($groupCollection as $group) {
            if ($group->getAttributeGroupName() == $row_grp['group_name']) {

                $group_exist_flg = true;
                break;
            } else {
                $group_exist_flg = false;
            }
        }
        if (!$group_exist_flg) {
            $attributeGroup = $this->attributeGroupFactory->create();
            $attributeGroup->setAttributeSetId($this->getAttrSetId($row_grp['set_name']));
            $attributeGroup->setAttributeGroupName($row_grp['group_name']);
            $this->attributeGroupRepository->save($attributeGroup);
            return true;
        }
        return;
    }

    function searchForRow($fild, $value, $array)
    {
        $return_arr = array();
        foreach ($array as $key => $val) {
            if ($fild == "option_name") {
                $sanitized = $this->sanitizeValue($val[$fild]);
                if ($sanitized == $value) {
                    $return_arr[] = $val;
                }
            } else {
                if ($val[$fild] == $value) {
                    $return_arr[] = $val;
                }
            }
        }
        return $return_arr;
    }

    /**
     * Get attribute by code.
     *
     * @param string $attributeCode
     * @return \Magento\Catalog\Api\Data\ProductAttributeInterface
     */
    public function getAttribute($attributeCode)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $attributeFactory = $objectManager->get(\Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory::class);
        $attribute = $attributeFactory->create();
        $attribute->loadByCode(\Magento\Catalog\Model\Product::ENTITY, $attributeCode);

        if ($attribute->getId()) {
            return $attribute;
        } else {
            return false;
        }
    }

    /**
     * Find or create a matching attribute option
     *
     * @param string $attributeCode Attribute the option should exist in
     * @param string $label Label to find or add
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    //public function createOrGetId($attributeCode, $label)
    public function createOrUpdateAttributeOption($row_eao)
    {
        $connection = $this->resourceConnection->getConnection();
        $table = $connection->getTableName('eav_attribute_option_value');
        //echo $table;exit;

        // Does it already exist?
        //$optionId = $this->getOptionId($attributeCode, $label);
        $optionId = $this->getOptionId($row_eao);

        /*
        if(str_contains($row_eao['option_name'],"&quote;")){
            echo ' Option ID found or not:- ';
            print_r($optionId);
            print_r($row_eao);
            //exit('coming here');
        }
        */


        if (!$optionId) {
            // If no, add it.

            // Fetch EAOL array with multiple lines
            global $EAOL_data;
            $optionNameSanitized = $this->sanitizeValue($row_eao['option_name']);
            $row_eaol = $this->searchForRow('option_name', $optionNameSanitized, $EAOL_data);
            $optionLabels = array();
            $query = array();
            foreach ($row_eaol as $row_eaol_k => $row_eaol_v) {
                if ($row_eaol_v['csv_action'] == "C") {

                    if (strlen($row_eaol_v['option_label']) < 1) {
                        return 'Label for ' . $row_eao['attribute_code'] . ' must not be empty.';
                    }

                    $store_id = $this->getStoreIdByCode($row_eaol_v['store']);

                    global ${"optionLabel_" . $store_id};
                    /** @var \Magento\Eav\Model\Entity\Attribute\OptionLabel $optionLabel */
                    ${"optionLabel_" . $store_id} = $this->optionLabelFactory->create();
                    ${"optionLabel_" . $store_id}->setStoreId($store_id);
                    $optionNameSanitized = $this->sanitizeValue($row_eaol_v['option_name']);
                    ${"optionLabel_" . $store_id}->setLabel($optionNameSanitized);
                    $optionLabels[] = ${"optionLabel_" . $store_id};


                    $optionLabelSanitized = $this->sanitizeValue($row_eaol_v['option_label']);

                    $data = ["sql_serv_id" => $row_eaol_v['sql_serv_id']];
                    $where = ['store_id = ?' => $store_id, 'value = ?' => $optionLabelSanitized, 'option_id = ?' => '#optionId#'];

                    $clubArray = [];
                    $clubArray['data'] = $data;
                    $clubArray['where'] = $where;
                    //$connection->update($tableName, $data, $where);
                    $query[] = $clubArray;
                    //$query[] = "UPDATE " . $table . " SET sql_serv_id = '".$row_eaol_v['sql_serv_id']."' WHERE store_id = '".$store_id."' and value = '".$optionLabelSanitized."' and option_id = '#optionId#';";
                    //echo " - ".$query[count($query)-1]." - ";
                }

            }


            $optionNameSanitized = $this->sanitizeValue($row_eao['option_name']);
            $option = $this->optionFactory->create();
            $option->setLabel($optionNameSanitized);
            $option->setStoreLabels($optionLabels);
            $option->setSortOrder($row_eao['sort_order']);
            $option->setIsDefault(false);


            //if(str_contains($row_eaol_v['option_name'],"&quote;")){
            // echo ' Option ID found or not:- |||||||||||  ';
            //print_r($optionNameSanitized);
            //print_r($row_eaol_v);
            //print_r($optionLabels);
            //echo '  |||||||||||||  ';
            //exit('coming here');
            //}


            $this->attributeOptionManagement->add(
                \Magento\Catalog\Model\Product::ENTITY,
                $row_eao['attribute_code'],
                $option
            );

            $optionId = $this->getOptionId($row_eao, true);
            foreach ($query as $query_k => $query_v) {

                if (isset($query_v['where']) && is_array($query_v['where'])) {
                    foreach ($query_v['where'] as $value) {
                        $qry = str_replace("#optionId#", $optionId, $value);
                    }

                }

                $connection->update($table, $query_v['data'], $query_v['where']);
                //$connection->query($qry);
            }

            $table = $connection->getTableName('eav_attribute_option');

            $data = ["sql_serv_id" => $row_eaol_v['sql_serv_id']];
            $where = ['option_id = ?' => $optionId];


            $connection->update($table, $data, $where);

            //$query_main_tbl = "UPDATE " . $table . " SET sql_serv_id = '".$row_eao['sql_serv_id']."' WHERE option_id = '".$optionId."';";
            //$connection->query($query_main_tbl);
        }
    }

    /**
     * Find the ID of an option matching $label, if any.
     *
     * @param string $attributeCode Attribute code
     * @param string $label Label to find
     * @param bool $force If true, will fetch the options even if they're already cached.
     * @return int|false
     */
    //public function getOptionId($attributeCode, $label, $sql_serv_id, $force = false)
    public function getOptionId($row_eao, $force = false)
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute */
        $attribute = $this->getAttribute($row_eao['attribute_code']);
        // Build option array if necessary
        if ($force === true || !isset($this->attributeValues[$attribute->getAttributeId()])) {
            $this->attributeValues[$attribute->getAttributeId()] = [];

            // We have to generate a new sourceModel instance each time through to prevent it from
            // referencing its _options cache. No other way to get it to pick up newly-added values.

            /** @var \Magento\Eav\Model\Entity\Attribute\Source\Table $sourceModel */
            $sourceModel = $this->tableFactory->create();
            $sourceModel->setAttribute($attribute);
            foreach ($sourceModel->getAllOptions() as $option) {

                // new code added for possible fix Ishan
                $optionNameSanitized = $this->sanitizeValue($option['label']);
                $optionValueSanitized = $this->sanitizeValue($option['value']);
                //org $this->attributeValues[ $attribute->getAttributeId() ][ $option['label'] ] = $option['value'];
                $this->attributeValues[$attribute->getAttributeId()][$optionNameSanitized] = $optionValueSanitized;
            }

        }

        // Return option ID if exists
        $optionNameSanitized = $this->sanitizeValue($row_eao['option_name']);
        if (isset($this->attributeValues[$attribute->getAttributeId()][$optionNameSanitized])) {
            return $this->attributeValues[$attribute->getAttributeId()][$optionNameSanitized];
        }
        // Return false if does not exist
        return false;
    }

    public function getStoreIdByCode($store_code)
    {
        $store = $this->storeRepository->get($store_code);
        return $store->getId();
    }

    /**
     * Add an option to the attribute
     *
     * @param string $attributeCode
     * @param string $label
     * @return void
     * @throws LocalizedException
     */
    //public function addOptionToAttribute($attributeCode, $label)
    public function addOptionToAttribute($row_eao_v)
    {
        //$this->appState->setAreaCode('adminhtml'); // Set the area code to 'adminhtml' if not already set

        // Load the attribute by its code
        $attribute = $this->attributeRepository->get('catalog_product', $row_eao_v['attribute_code']);
        // Create a new attribute option
        //$option = $this->attributeOptionFactory->create();
        $option = $this->optionFactory->create();
        $optionNameSanitized = $this->sanitizeValue($row_eao_v['option_name']);
        $option->setLabel($optionNameSanitized);
        $option->setStoreLabels([$optionNameSanitized]); // Set the label for the default store
        $option->setSortOrder(0); // Set the sort order for the option
        // Add the option to the attribute
        $this->attributeOptionManagement->add(
            'catalog_product',
            $row_eao_v['attribute_code'],
            $option
        );

        /*$this->attributeOptionManagement->add(
            \Magento\Catalog\Model\Product::ENTITY,
            $this->getAttribute($row_eao['attribute_code'])->getAttributeId(),
            $option
        );*/

        // Clear attribute values cache
        $attribute->unsetData('option');
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

    public function updateAmastyMultiselect($attributeCode)
    {

        $filter = $this->filterSettingRepository->getByAttributeCode($attributeCode);
        $filter->setIsMultiselect(true);
        $filter->setIsUseAndLogic(true);
        $this->filterSettingRepository->save($filter);
    }
}
