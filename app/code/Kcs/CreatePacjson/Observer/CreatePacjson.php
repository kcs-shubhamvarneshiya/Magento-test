<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Kcs\CreatePacjson\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class CreatePacjson
 */
class CreatePacjson implements ObserverInterface
{
    /**
     * @var \Magento\Catalog\Api\AttributeSetRepositoryInterface
     */
    protected $attributeSetRepository;
    protected $attributeGroupCollection;
    protected $productAttributeCollection;
    protected $attribute;
    protected $attributeRepository;
    protected $attributes;
    protected $eavConfig;
    protected $_dataRecord;
    protected $resourceConnection;
    protected $_logger;

    public function __construct(
        \Magento\Catalog\Api\AttributeSetRepositoryInterface $attributeSetRepository,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory $attributeGroupCollection,
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $productAttributeCollection,
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute,
        \Magento\Catalog\Model\Entity\AttributeFactory $attributes,
        \Magento\Catalog\Model\Product\Attribute\Repository $attributeRepository,
        \Magento\Eav\Model\Config $eavConfig,
        \Kcs\Pacjson\Model\PacjsonFactory $_dataRecord,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->attributeSetRepository = $attributeSetRepository;
        $this->attributeGroupCollection = $attributeGroupCollection;
        $this->productAttributeCollection = $productAttributeCollection;
        $this->attribute = $attribute;
        $this->attributeRepository = $attributeRepository;
        $this->eavConfig = $eavConfig;
        $this->_dataRecord = $_dataRecord;
        $this->resourceConnection = $resourceConnection;
        $this->_logger = $logger;
    }

    public function execute(Observer $observer)
    {
        $product = $observer->getEvent()->getData('product');
        $attributeSetId = $product->getAttributeSetId();
        $groupCollection = $this->attributeGroupCollection->create()
            ->setAttributeSetFilter($attributeSetId)
            ->load(); // product attribute group collection
        $attributeCollection = [];
        $db_attribute_combination = [];
        foreach ($groupCollection as $group) {
            $attributeGroupCollection[] = $group->getData();
            if ($group->getAttributeGroupName() == 'Attribute Options') {
                $groupAttributesCollection = $this->productAttributeCollection->create()
                    ->setAttributeGroupFilter($group->getId())
                    ->addVisibleFilter()
                    ->load(); // product attribute collection

                foreach ($groupAttributesCollection->getItems() as $attribute) {
                    $attributeCollection[] = $attribute->getData();
                    $db_attribute_combination[] = html_entity_decode($attribute->getAttributeCode());
                }
            }
        }
        $attribute_options = array();
        foreach($attributeCollection as $attributeValue) {
            $attribute_options[] = $this->getMultiselectlist($product, 'catalog_product', $attributeValue['attribute_code']); // product attribute options collection
        }
        //$level = 'DEBUG';
        //$this->_logger->log($level,'attribute_options', $attribute_options);
        
        /*$i = 0;
        $max = 0;
        $multiply_count = 1;
        foreach ($attribute_options as $attribute_options_k => $attribute_options_v) {
            $current_count = count($attribute_options_v);
            $count_array[] = $current_count;
            $multiply_count = $multiply_count * $current_count;
            if ($max <  $current_count) {
                $max = $current_count;
            }
            $i++;
        }

        //print "<pre>";
        //print_r($attribute_options);exit;

        $final_arr = array();
        for ($i = 0; $i < count($attribute_options); $i++) {
            $final_arr[$i] = array();
            for ($j = 0; $j < ($multiply_count / count($attribute_options[$i])); $j++) {
                if ((($j+1)%2) == 0 && (($i+1)%2) == 1) {

                    $final_arr[$i] = array_merge($final_arr[$i], array_reverse($attribute_options[$i]));
                } else {
                    $final_arr[$i] = array_merge($final_arr[$i], $attribute_options[$i]);
                }
            }
        }

        for ($i = 0; $i < count($final_arr); $i++) {
            for ($j = 0; $j < count($final_arr[$i]); $j++) {
                $transformed_final_array[$j][$i] = html_entity_decode($final_arr[$i][$j]);
            }
        }
        */

        $transformed_final_array = $this->array_combinations($attribute_options);
        $transformed_final_json = array();
        // just to conver elements to decode elements
        for ($i = 0; $i < count($transformed_final_array); $i++) {
            for ($j = 0; $j < count($transformed_final_array[$i]); $j++) {
                $transformed_final_array[$i][$j] = html_entity_decode($transformed_final_array[$i][$j]);
            }
        }
        
        for ($i = 0; $i < count($transformed_final_array); $i++) {
            $transformed_final_json[$i] = json_encode($transformed_final_array[$i], JSON_FORCE_OBJECT);
        }

        // Delete Old records
        // NOTE -----> Remain to put condition before creation of records that is that changed?
        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName('kcs_pacjson'); //gives table name with prefix
        $sql = "DELETE FROM " . $tableName . " WHERE pid = ".$product->getEntityId();
        $connection->query($sql);
        // Store array in datbase records
        $db_attribute_combination_json = json_encode($db_attribute_combination, JSON_FORCE_OBJECT);
        $db_option_combination_json_array = $transformed_final_json;
        foreach ($db_option_combination_json_array as $db_option_combination_json_array_v) {
            $model = $this->_dataRecord->create();
            $model->addData([
                "pid" => $product->getEntityId(),
                "pname" => $product->getName(),
                "attribute_combination" => $db_attribute_combination_json,
                "option_combination_json" => $db_option_combination_json_array_v,
                "status" => 1
                ]);
            $saveData = $model->save();
        }
        /*$this->_logger->log($level,'final_Arr', $final_arr);
        print_r($final_arr);
        exit;*/
    }

    function array_combinations($arrays) {
        $result = array();
        $arrays = array_values($arrays);
        $sizeIn = sizeof($arrays);
        $size = $sizeIn > 0 ? 1 : 0;
        foreach ($arrays as $array)
            $size = $size * sizeof($array);
        for ($i = 0; $i < $size; $i ++) {
            $result[$i] = array();
            for ($j = 0; $j < $sizeIn; $j ++) {
                array_push($result[$i], current($arrays[$j]));
            }
            for ($j = ($sizeIn -1); $j >= 0; $j --) {
                if (next($arrays[$j]))
                    break;
                elseif (isset ($arrays[$j]))
                    reset($arrays[$j]);
            }
        }
        return $result;
    }

    public function getGroupCollection($productAttributeSetId) {
        $groupCollection = $this->_groupCollection->create();
        $groupCollection->addFieldToFilter('attribute_set_id', $productAttributeSetId);
        $groupCollection->addFieldToFilter('attribute_group_name', 'Attribute Options');
        $firstItem = $groupCollection->getFirstItem();
        return $firstItem->getData('attribute_group_id');
    }

    public function getAttributeOption($attributeId)
    {
        $attributeModel = $this->attribute->load($attributeId);
        $attributeCode = $attributeModel->getAttributeCode();
        //$this->_logger->log('DEBUG','debuglog1234 '.$attributeCode);
        $options = $this->attributeRepository->get($attributeCode)->getOptions();
        return $options;
    }

    public function getMultiselectlist($product, $resourcename, $attributename) {
        $arr = explode(", ", $product->getResource()->getAttribute($attributename)->getFrontend()->getValue($product));
        return $arr;
    }
}
