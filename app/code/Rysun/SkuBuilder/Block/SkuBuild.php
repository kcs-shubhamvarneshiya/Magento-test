<?php

namespace Rysun\SkuBuilder\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollection;
use Magento\Eav\Model\Config;
use Magento\Framework\Registry;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\Product;
use Rysun\SkuBuilder\Helper\Data;
use Rysun\DataTransfer\Model\ProductTrimFactory;

use Kcs\Pacjson\Model\PacjsonFactory;

class SkuBuild extends \Magento\Framework\View\Element\Template
{

    protected $sample;
    protected $registry;
    protected $attributeGroupCollection;
    protected $productAttributeCollection;
    protected $_eavConfig;
    protected $helper;
    protected $productTrimModel;
    protected $productModel;
    protected $pacjson;


    const POSITION_ATTRIBUTE_IDENTIFICATION = '_arch_option_position';
    const ATTRIBUTE_TRIM_LABEL = '_position';
    const ATTRIBUTE_OPTION_SEARCH = '_arch_option';
    
    public function __construct(
        Context $context,
        CollectionFactory $attributeGroupCollection,
        AttributeCollection $productAttributeCollection,
        Config $eavConfig,
        Data $helper,
        ProductTrimFactory $productTrimModel,
        ProductFactory $productModel,
        PacjsonFactory $pacjson,

        Registry $registry)
    {
        $this->sample = 'true';
        $this->registry = $registry;
        $this->attributeGroupCollection = $attributeGroupCollection;
        $this->productAttributeCollection = $productAttributeCollection;
        $this->_eavConfig = $eavConfig;
        $this->helper = $helper;
        $this->productTrimModel = $productTrimModel;
        $this->productModel = $productModel;
        $this->pacjson = $pacjson;

        parent::__construct($context);
    }
    
    public function getOutput(){
        return $this->sample;
    }

    /**
     * return Current product
     */
    public function getCurrentProduct()
    {
            return $this->registry->registry('current_product');
    }


    /**
     * return Current product
     */
    public function getCurrentProductId($productId = null)
    {
         //return null;
         if(!$productId){
            $product = $this->getCurrentProduct();
            return $product->getSqlServId();
        } else {
            $product = $this->productModel->create()->load($productId);
            return $product->getSqlServId();
        }
        
        //return $productId;
    }

    public function getProductSqlServId($productId){
        $product = $this->getCurrentProduct($productId);
    }


    /** 
     * return product name
     */
    public function getProductName(){

        $product = $this->getCurrentProduct();
        return $product->getName();
    }

    public function loadProductData($productId){

        return $this->productModel->create()->load($productId);
    }

    public function getProductAttributeList($productId = null){
        //return null;
        if(!$productId){
            $product = $this->getCurrentProduct();
        } else{
            $product = $this->loadProductData($productId);
        }
        //$product = $this->getCurrentProduct();
        $attributes = $product->getAttributes();

        $attributeOptionList = [];

        foreach($attributes as $attributeItem){

            $attrCode = $attributeItem->getName();

            //check if attribute having _position value
            if(!$this->checkAttribute($attrCode)){
                continue;
            }

            $productPositionValue = $product->getData($attrCode);

            if(empty($productPositionValue)){
                continue;
            }

            $optionAttrCode = $this->findActualOptionAttribute($attrCode);


            if( !$optionAttrCode  ){
                continue;
            }

                if(!$optionAttrCode->getId()){
                    
                    continue;
                }

                
                //$productAttrValue = $product->getData($optionAttrCode);
                $attributeOptions = [];
                if(is_array($product->getAttributeText($optionAttrCode->getName()))){
                    $values = $product->getAttributeText($optionAttrCode->getName());
                    foreach($values  as $value){
                        $attributeOptions[] = $value;
                    }
                } else {
                    if(!empty($product->getAttributeText($optionAttrCode->getName()))){
                        $attributeOptions[] = $product->getAttributeText($optionAttrCode->getName());
                    }
                }
                

                $attributeOptionData = [];
                $attributeOptionData['label'] = $optionAttrCode->getFrontendLabel();
                $attributeOptionData['sku'] = $product->getId();
                $attributeOptionData['options'] = $attributeOptions;
                $attributeOptionData['type'] = $optionAttrCode->getFrontendInput();


                //Append to final array

                $attributeOptionList[$productPositionValue] = $attributeOptionData;

                //check if product has attribute _option value


        }

        ksort($attributeOptionList);
        return $attributeOptionList;
    }

    /**
     * Find option attribute based on _arch_position attribute code
     */
    public function findActualOptionAttribute($attributeCode){

        $variable = substr($attributeCode, 0, strrpos($attributeCode, SELF::ATTRIBUTE_TRIM_LABEL));


        $optionAttribute = trim($variable);

        $actualOption = $this->isProductAttributeExists($optionAttribute);

        return $actualOption;

    }

    /**
     * Returns true if attribute exists and false if it doesn't exist
     *
     * @param string $field
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function isProductAttributeExists($field)
    {
        $attr = $this->_eavConfig->getAttribute(Product::ENTITY, $field);
 
        if($attr->getId()){
            return $attr;
        }
        return false;

    }


    /**
     * check if attribute is having _arch_position
     * return boolean
     */
    public function checkAttribute($attributeCode){
        
        if(strpos($attributeCode, SELF::POSITION_ATTRIBUTE_IDENTIFICATION) !== false){
            return true;
        }
        return false;
    }

    public function getProductAttribute() {

        $product = $this->getCurrentProduct();

        $attributeSetId = $product->getAttributeSetId();
        $groupCollection = $this->attributeGroupCollection->create()
            ->setAttributeSetFilter($attributeSetId)
            ->load(); // product attribute group collection

        
        $attributeCollection = [];
        $attributePositionCollection = [];
        $db_attribute_combination = [];
        foreach ($groupCollection as $group) {

            if ($group->getAttributeGroupName() == 'Attribute Options') {
                $groupAttributesCollection = $this->productAttributeCollection->create()
                    ->setAttributeGroupFilter($group->getId())
                    ->addVisibleFilter()
                    ->load(); // product attribute collection

                foreach ($groupAttributesCollection->getItems() as $attribute) {
                    $attributeCollection[] = $attribute->getData();
                    //$db_attribute_combination[] = html_entity_decode($attribute->getAttributeCode());
                }
            }
            if ($group->getAttributeGroupName() == 'Attribute Options Position') {
                $groupAttributesCollection = $this->productAttributeCollection->create()
                    ->setAttributeGroupFilter($group->getId())
                    ->addVisibleFilter()
                    ->load(); // product attribute collection

                foreach ($groupAttributesCollection->getItems() as $attribute) {
                    $attributePositionCollection[] = $attribute->getData();
                    //$db_attribute_combination_position[] = html_entity_decode($attribute->getAttributeCode()."_position");
                }
            }
        }

        return $attributeCollection;
        //return [$attributeCollection, $attributePositionCollection];

    }

    /**
     * Get trim products ID
     */
    public function getTrimProducts(){


        $product = $this->getCurrentProduct();

        $result = $this->productTrimModel->create();
        $collection = $result->getCollection();
        $collection->addFieldToFilter('product_id',$product->getId());

        $trimProductId = [];
        if(count($collection) > 0){
            foreach($collection as $product){
                $trimProductId[] = $product->getTrimProductId();
            }
        }
        $trimProducts = array_unique($trimProductId);
        return $trimProducts;

    }



    /**
     * Get product exceptons rules from import tables
     */
    public function getExceptionRules($productId = null){


      //$productSqlId = $this->findProductSqlServeId($productId);
        //echo $productId.' ||';
       $productSqlId = $this->getCurrentProductId($productId);
        //$productSqlId = 1;
       
        //echo $productSqlId;
        //exit('coming here');
        $collection = $this->pacjson->create();
       
       $collection = $collection->getCollection();
       $collection->addFieldToFilter('sql_serv_prod_id',$productSqlId);
       
        $exceptionRule = [];
       foreach($collection as $item){
       //$splitData = explode("||",$item['option_combination_json']);

        $exceptionRule[] = $item['option_combination_json'];
       }



       $finalarray = [];
       foreach($exceptionRule as $rule){
           $subArray = explode("||",str_replace(":","-",$rule));
           $finalarray[] = $subArray;
       }

       //echo '<pre>';
       //print_r($finalarray);
       //exit('here');
       return $finalarray;
    }


}