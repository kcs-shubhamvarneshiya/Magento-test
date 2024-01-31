<?php

namespace Rysun\DataTransfer\Cron;

use Psr\Log\LoggerInterface;
use Magento\Framework\App\ResourceConnection;
use Rysun\DataTransfer\Model\ProductTrimFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Tax\Api\Data\TaxClassKeyInterface;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory as EavCollection;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Api\CategoryLinkManagementInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Rysun\ArchiCollection\Model\ArchiCollectionFactory;
use Rysun\ArchiCollection\Model\ArchiPlatformFactory;
use Rysun\AttributeRange\Model\AttributeRange;

use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
//use Magento\Catalog\Model\Product\Attribute\Source\Status;

#use Magento\Catalog\Model\ProductFactory;
use Magento\ConfigurableProduct\Helper\Product\Options\Factory as OptionFactory;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

use Rysun\DataTransfer\Helper\Data;



class ProductConfigImp
{

    protected $logger;

    protected $fileFactory;
    protected $csvProcessor;
    protected $directoryList;
    protected $resourceConnection;
    protected $productTrim;
    protected $timezoneInterface;
    protected $productFactory;
    protected $eavConfig;
    protected $appState;
    protected $productRepository;
    protected $productCollection;
    protected $taxClassManagementInterface;
    protected $taxClassKeyDataObjectFactory;
    protected $attributeSetCollection;
    protected $visibility;
    protected $categoryLinkManagement;
    protected $categoryFactory;
    protected $attributeRangeCollection;
	protected $helper;
    protected $productApiFactory;
    protected $productApiRepository;



    protected $collectionPlatform;
    protected $platformModel;

	const PRODUCT_IMPORT_PATH = 'archi_import/general/product_import_path';

    public function __construct(
        LoggerInterface                                   $logger,
        //\Kcs\Pacjson\Model\PacjsonFactory $Pacjson,
        \Magento\Framework\App\Response\Http\FileFactory  $fileFactory,
        \Magento\Framework\File\Csv                       $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList   $directoryList,
        ResourceConnection                                $resourceConnection,
        ProductTrimFactory                                $productTrim,
        TimezoneInterface                                 $timezoneInterface,
        ProductFactory                                    $productFactory,
        \Magento\Eav\Model\Config                         $eavConfig,
        \Magento\Framework\App\State                      $state,
        ProductRepository                                 $productRepository,
        CollectionFactory                                 $productCollection,
        \Magento\Tax\Api\TaxClassManagementInterface      $taxClassManagementInterface,
        \Magento\Tax\Api\Data\TaxClassKeyInterfaceFactory $taxClassKeyDataObjectFactory,
        EavCollection                                     $attributeSetCollection,
        Visibility                                        $visibility,
        CategoryLinkManagementInterface                   $categoryLinkManagement,
        CategoryFactory                                   $categoryFactory,
        ArchiCollectionFactory                            $collectionPlatform,
        ArchiPlatformFactory                              $platformModel,
        AttributeRange                                    $attributeRangeCollection,
		Data                        $helper,
        ProductInterfaceFactory $productApiFactory,
        ProductRepositoryInterface $productApiRepository,
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
        $this->productFactory = $productFactory;
        $this->productApiFactory = $productApiFactory;
        $this->eavConfig = $eavConfig;
        $this->state = $state;
        $this->productRepository = $productRepository;
        $this->productCollection = $productCollection;
        $this->taxClassManagementInterface = $taxClassManagementInterface;
        $this->taxClassKeyDataObjectFactory = $taxClassKeyDataObjectFactory;
        $this->attributeSetCollection = $attributeSetCollection;
        $this->visibility = $visibility;
        $this->categoryLinkManagement = $categoryLinkManagement;
        $this->categoryFactory = $categoryFactory;
        $this->collectionPlatform = $collectionPlatform;
        $this->platformModel = $platformModel;
        $this->attributeRangeCollection = $attributeRangeCollection;
		$this->helper = $helper;
        $this->productApiRepository = $productApiRepository;
    }

    /**
     * Write to system.log
     *
     * @return void
     */
    public function execute()
    {


        try {
            $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);
            //Create Simple product from CSV
            $row = [];

            
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORES;
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

			$filePath = $this->helper->getConfigValue(SELF::PRODUCT_IMPORT_PATH);
            $csv = array_map("str_getcsv", file($filePath, FILE_SKIP_EMPTY_LINES));

            $keys = array_shift($csv);

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
            $countProduct = 0;

            //echo '<pre>';
            //print_r($csv);
            //exit('hello');
            foreach ($csv as $i => $row) {

                if (!isset($row['csv_action'])) {
                    continue;
                }

                if ($row['csv_action'] == 'C') {

                    //Create Simple product from CSV

                    
                    //if($attribute == 'url_key'){

                    //Cross check if sku-urlKey exist or not
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $urlRewriteModel = $objectManager->create('\Magento\UrlRewrite\Model\UrlRewrite');
                    $UrlRewriteCollection = $urlRewriteModel->getCollection()->addFieldToFilter(['request_path'],[ ['like' => '%'.$row['url_key'].'%']]);
                    if(count($UrlRewriteCollection) > 0){
                        $row['url_key'] = $row['url_key'].'-arch';
                    }        
            
                   //}

                    $urlKey = $row['url_key'];
                    $sqlServId = $row['sql_serv_id'];
                    $row['url_key'] = $row['url_key']."-simple";
                    $row['test_attribute'] = 'Sample';
                    $row['sql_serv_id'] =  $row['sql_serv_id']."_config";
                    $simpleProductData = $this->createSimpleProduct($row,'simple');

                    

                    // Code for creating configurable product start
                    
                    $row['url_key'] = $urlKey;
                    $row['sql_serv_id'] =  $sqlServId;
                    
                    $configurableProductData = $this->createSimpleProduct($row,'configurable');

                
                    $productId = $configurableProductData->getId();



                    // assign simple product ids

                    $associatedProductIds = array($simpleProductData->getId());

                    try{
                    $configurable_product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId); // Load Configurable Product
                        $configurable_product->setAssociatedProductIds($associatedProductIds); // Setting Associated Products
                        $configurable_product->setCanSaveConfigurableAttributes(true);
                        $configurable_product->save();
                    } catch (Exception $e) {
                        echo "<pre>";
                        print_r($e->getMessage());
                        exit;
                    }
                    
                    // Code for creating configurable product end
                    

                    // $simpleProduct = $this->productApiFactory->create();
                    // $temp = $simpleProduct->load($simpleProductData->getId());
                    // $temp->setVisibility(Visibility::VISIBILITY_BOTH); // Set product visibility
                    // $temp->setStatus(Status::STATUS_ENABLED); // Set product status
                    // $this->productApiRepository->save($temp);

                    // $configurableProduct = $this->productApiFactory->create();
                    // $temp1 = $configurableProduct->load($configurableProductData->getId());
                    // $temp1->setVisibility(Visibility::VISIBILITY_BOTH); // Set product visibility
                    // $temp1->setStatus(Status::STATUS_ENABLED); // Set product status
                    // $this->productApiRepository->save($temp1);

                    /*
                    $associatedProducts[] = [
                        'id' => $simpleProductData->getId(),
                        'qty' => $row['stock.qty'], // Set the initial quantity for the associated product
                        'attribute_ids' => [3541], // Replace with the attribute IDs relevant to your configuration
                    ];

                    $configurableAttributesData = [
                        [
                            'attribute_id' => 3541, // Replace with the attribute ID for your configurable attribute
                            'code' => 'test_attribute', // Attribute code
                            'label' => 'Sample',
                            'position' => 0,
                            'values' => [], // Add values if needed
                        ],
                        // Add more attributes if necessary
                    ];

                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $configurableProduct = $objectManager->create(\Magento\Catalog\Model\Product::class)->load($configurableProductData->getId());
                    $configurableProduct->setTypeId("configurable");
                    $configurableProduct->setConfigurableAttributesData($configurableAttributesData);
                    $configurableProduct->setConfigurableProductsData($associatedProducts);
                    $configurableProduct->setCanSaveConfigurableAttributes(true);
                    $configurableProduct->save();
                    ///END
                    
                    */
                   
                

                } else if ($row['csv_action'] == 'U' && $row['sql_serv_id'] !== '') {
                    $this->updateSimpleProduct($row);
                } else if ($row['csv_action'] == 'D') {


                    $sqlServerId = $row['sql_serv_id'];
                    //find Product Id
                    $productSku = $this->getProductSkuBySqlServerId($sqlServerId);

                    if ($productSku) {
                        $this->productRepository->deleteById($productSku);
                    } else {
                        echo 'Product not found!';
                    }

                } else if ($row['csv_action'] == 'E') {

                    $product = $this->productFactory->create();

                    //Get product ID by sql serv ID

                    $id = $this->getProductIdBySqlServerId($row['sql_serv_id']);
                    $product->load($id);
                    if ($product->getId()) {
                        $product->setStoreId(0);
                        $product->setCollectionId(trim($row['collection_id']));
                        $product->setPlatformId(trim($row['platform_id']));
                        $product->save();
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

    /**
     * Find magento product id from sql server id value
     */
    public function getProductSkuBySqlServerId($sqlServerId)
    {

        //$this->productCollection
        $collection = $this->productCollection->create();
        $collection->addAttributeToSelect('sql_serv_id');
        $collection->addAttributeToSelect('sku');
        $collection->addAttributeToFilter('sql_serv_id', ['eq' => $sqlServerId]);
        if ($collection->getSize()) {
            $data = $collection->getFirstItem();
            $sku = $data->getSku();
            return $sku;
        }
        return null;
    }

    /**
     * Find magento product id from sql server id value
     */
    public function getProductIdBySqlServerId($sqlServerId)
    {

        $collection = $this->productCollection->create();
        $collection->addAttributeToSelect('sql_serv_id');
        $collection->addAttributeToSelect('sku');
        $collection->addAttributeToFilter('sql_serv_id', ['eq' => $sqlServerId]);
        if ($collection->getSize()) {
            $data = $collection->getFirstItem();
            $id = $data->getId();
            return $id;
        }
        return null;
    }


    /*
    * Create simple product from data

    */
    public function createSimpleProduct($productData,$productType)
    {

        try {
        $attributeSetId = 4;
        $status = 1;
        $visibility = 4;
        $websiteId = [1,11,12];
        $taxClassId = 0;
        $productType = $productType;

        $product = $this->productFactory->create();
        //$product = $this->productApiFactory->create();
        //Product Factory object

        $sku = $productData['sku'];
        if (strlen($sku) > 62) {
            if($productType == 'simple'){
                $sku = substr($productData['sku'], 0, 52);
            } else{
                $sku = substr($productData['sku'], 0, 62);
            }
           
        }
        if($productType == 'simple' && strlen($sku) > 52 ){
            $sku = substr($productData['sku'], 0, 52);
        }

        if($productType == 'simple'){
            $sku =  $sku."-simple";
       }

       //print_r($sku);
       //exit;
        // set your sku
        $product->setSku($sku);
        $product->setName($this->sanitizeValue($productData['name'])); // set your Product Name of Product

        //Get Attribute Set ID by value
        //$attributeSetId = $this->getAttributeSetIdByName($productData['product.attribute_set']);
        $attributeSetId = $this->getAttributeSetIdByName($productData['product.attribute_set']);
        $product->setAttributeSetId($attributeSetId); // set attribute id


        //Get Attribute Set ID by value
        $statusId = $this->getStatus($productData['status']);
        //echo 'status:- '.$statusId.' simple';
        $product->setStatus($statusId); // status enabled/disabled 1/0

        $product->setWeight($productData['weight']); // set weight of product

        //Get Visibility ID based on Label
        $visibilityId = $this->getVisibility($productData['visibility']);
        if($productType == 'configurable'){
            $product->setVisibility($visibilityId);
        } else{
            $product->setVisibility(1); // visibility of product (Not Visible Individually (1) / Catalog (2)/ Search (3)/ Catalog, Search(4))
        }
        

        $product->setWebsiteIds($websiteId);

        //Get Tax Class ID by value
        $taxClassId = $this->getTaxClassId($productData['tax_class_id']);
        $product->setTaxClassId($taxClassId); // Tax class ID
        $product->setTypeId($productType); // type of product (simple/virtual/downloadable/configurable)
        $product->setPrice($productData['price.final']); // set price of product
        //$product->setStoreId(1);
        $product->setStockData(
            array(
                'use_config_manage_stock' => 0,
                'manage_stock' => 1,
                'is_in_stock' => 1,
                'qty' => $productData['stock.qty']
            )
        );
        $product->setFootNotes(trim($productData['product_footnote']));
        $product->setIsPdp(trim($productData['is_pdp']));
        $product->setIsArchitechData(true);
        

        $excludeIndex = ['sku', 'name', 'weight', 'visibility', 'price'];

        $categoryIds = [];
        foreach ($productData as $attributeKey => $columValue) {

            if (!empty($columValue)) {
                if (in_array($attributeKey, $excludeIndex)) {
                    //$product->setData($attributeKey, $columValue);
                    $exculded[] = $attributeKey;
                    continue;
                } else {
                    $included[] = $attributeKey;
                    if (!empty($columValue)) {
                        $columValue = $this->sanitizeValue($columValue);

                        if ($attributeKey == 'category.ids' && !empty($columValue)) {
                            $categoryIds = $this->validateCatId($columValue);

                        } else if ($attributeKey == 'collection_id' && !empty($columValue)) {
                            $collectionSqlServId = $this->getCollectionSqlServId($columValue);
                            $this->setAttribute($product, $attributeKey, $collectionSqlServId);

                        } else if ($attributeKey == 'platform_id' && !empty($columValue)) {
                            $platformSqlServId = $this->getPlatformSqlServId($columValue);
                            $this->setAttribute($product, $attributeKey, $platformSqlServId);
                        } else if (!str_contains($attributeKey, '.')) {
                            $this->setAttribute($product, $attributeKey, $columValue);
                        }

                    }
                }
            }

        }

      
        $mageCatId = [];
        foreach ($categoryIds as $cat) {

            $mageCategory = $this->findMagentoIdBySqlServId($cat);
            if ($mageCategory) {
                $mageCatId[] = $mageCategory->getId();
            }

        }

        // Set Range attributes values
        $this->setRangeAttributeValue($product, $productData);

       



            if($productType == 'configurable'){
                // super attribute 
            //$size_attr_id = $product->getResource()->getAttribute('size')->getId();
            $color_attr_id = $product->getResource()->getAttribute('test_attribute')->getId();

            $product->getTypeInstance()->setUsedProductAttributeIds(array($color_attr_id), $product); //attribute ID of attribute 'size_general' in my store

            $configurableAttributesData = $product->getTypeInstance()->getConfigurableAttributesAsArray($product);
            $product->setCanSaveConfigurableAttributes(true);
            $product->setConfigurableAttributesData($configurableAttributesData);
            $configurableProductsData = array();
            $product->setConfigurableProductsData($configurableProductsData);

            }

            //$product->setVisibility(Visibility::VISIBILITY_BOTH); // Set product visibility
            //$product->setStatus(Status::STATUS_ENABLED); // Set product status

            
            //$this->productRepository->save($product);
           // print_r($product->debug());
            $product->save();
           // exit;


            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $productRepository = $objectManager->create('Magento\Catalog\Api\ProductRepositoryInterface');
            $loadProd = $productRepository->getById($product->getId());
            $loadProd->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
            $productRepository->save($loadProd);
            
        } catch (\Exception $e) {
            echo $e->getMessage();
            //print_r($product->getData('sql_serv_id'));
            //echo 'Erro in creating product' . " =>" . $e->getMessage() . " || ";
            return true;
        }

        if (count($mageCatId) && $product->getId()) {
            $this->updateCategory($product, $mageCatId);
        }

        return $product;
    }

    public function getRangeAttributeListOptions()
    {

        $rangeAttributeList = $this->attributeRangeCollection->getCollection();

        $listOfRangeAttribute = [];
        foreach ($rangeAttributeList as $rangeAttribute) {

            $rangeData['range'] = $rangeAttribute['attribute_range_desc'];
            $rangeData['min'] = $rangeAttribute['min_value'];
            $rangeData['max'] = $rangeAttribute['max_value'];
            $listOfRangeAttribute[$rangeAttribute['attribute_code']][] = $rangeData;
        }

        return $listOfRangeAttribute;
    }

    public function setRangeAttributeValue(&$product, $productData)
    {

        ## GET Range attribute list

        $listOfRangeAttribute = $this->getRangeAttributeListOptions();


        foreach ($listOfRangeAttribute as $key => $rangeAtttribute) {

            if (empty($product->getAttributeText($key))) {
                continue;
            }
            $finalOptionValues = [];
            $newAttribute = $key . "_range";
            $isMultiArray = false;
            foreach ($rangeAtttribute as $rangeValue) {


                $productValue = $product->getAttributeText($key);
                $actualValue = intval($productValue);

                //if($productData[$key]){
                if (strpos($productData[$key], '||') === true) {

                    $valueArray = explode('||', $productData[$key]);

                    foreach ($valueArray as $option) {
                        $optionSanitized = $this->sanitizeValue($option);

                        if ($optionSanitized == $rangeValue['range']) {
                            $finalOptionValues[] = $rangeValue['range'];
                            $isMultiArray = true;
                        }

                    }

                } else if ($productData[$key] == $rangeValue['range']) {

                    $finalOptionValues[] = $this->getOptionIdByLabel($product, $newAttribute, $rangeValue['range']);
                } else if ($actualValue >= intval($rangeValue['min']) && $actualValue <= intval($rangeValue['max'])) {

                    $finalOptionValues[] = $this->getOptionIdByLabel($product, $newAttribute, $rangeValue['range']);
                }
            }

            if ($isMultiArray) {

                $columValue = implode('||', $finalOptionValues);
            } else {

                $columValue = implode(',', $finalOptionValues);
            }

            $this->setAttribute($product, $newAttribute, $columValue);

        }
    }

    public function getOptionIdByLabel($product, $attributeCode, $optionLabel)
    {
        //$product = $this->productFactory->create();
        $isAttributeExist = $product->getResource()->getAttribute($attributeCode);
        $optionId = '';
        if ($isAttributeExist && $isAttributeExist->usesSource()) {
            $optionId = $isAttributeExist->getSource()->getOptionId($optionLabel);
        }
        return $optionId;
    }

    /* Get Label by option id */
    public function getOptionLabelByValue($product, $attributeCode, $optionId)
    {
        //$product = $this->productFactory->create();
        $isAttributeExist = $product->getResource()->getAttribute($attributeCode);
        $optionText = '';
        if ($isAttributeExist && $isAttributeExist->usesSource()) {
            $optionText = $isAttributeExist->getSource()->getOptionText($optionId);
        }
        return $optionText;
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

    /**
     * Get Product visibility Id from label
     */
    public function getVisibility($visibiltyLabel)
    {

        $visibiltyLabel = 'Catalog, Search';
        $options = $this->visibility->getOptionArray();

        $key = array_search($visibiltyLabel, $options);
        if ($key !== NULL) {
            return $key;

        }

        return 1; // by default set value 'Catalog,Serach'

    }

    /**
     * Get Status ID based on Status Label
     * 1 - Enabled, 2 - Disabled
     */
    public function getStatus($statusLabel)
    {

        if ($statusLabel == 'Enabled') {
            return 1;
        }

        return 2;
    }

    /**
     * Get Attribute Set ID based on attribute name
     */
    public function getAttributeSetIdByName($attributeSetName)
    {

        $attributeSetCollection = $this->attributeSetCollection->create()
            ->addFieldToSelect('attribute_set_id')
            ->addFieldToFilter('attribute_set_name', $attributeSetName)
            ->getFirstItem()
            ->toArray();

        $attributeSetId = (int)$attributeSetCollection['attribute_set_id'];
        return $attributeSetId;

    }


    /**
     * Get TaxClass ID based on TaxClass name
     */
    public function getTaxClassId($className)
    {
        $taxClassId = $this->taxClassManagementInterface->getTaxClassId(
            $this->taxClassKeyDataObjectFactory->create()
                ->setType(TaxClassKeyInterface::TYPE_NAME)
                ->setValue($className)
        );
        return $taxClassId;
    }

    /**
     * set individiual product attribute values
     */
    public function setAttribute(&$product, $attributeCode, $attributeValue)
    {

        //$attr = $product->getResource()->getAttribute($attributeCode);
        $attr = $product->getResource()->getAttribute($attributeCode);
        //$result = $attr->debug();
        if (!$attr) {
            return;
        }
        $type = $attr->getFrontendInput();
        $attributeData = [];

        switch ($type) {
            case 'text':
                $attributeData[] = $this->setTextAttribute($product, $attributeCode, $attributeValue);
                break;
            case 'textarea':
                $attributeData[] = $this->setTextareaAttribute($product, $attributeCode, $attributeValue);
                break;
            case 'select':
                $attributeData[] = $this->setDropdownAttribute($product, $attributeCode, $attributeValue);
                break;
            case 'multiselect':
                $attributeData[] = $this->setMultiSelectAttribute($product, $attributeCode, $attributeValue);
                break;
            default:
        }

        return true;


    }

    /**
     * set Single Dropdown value to product
     */
    public function setDropdownAttribute(&$product, $attribute, $attributeValue)
    {

        $attr = $product->getResource()->getAttribute($attribute);
        $avid = $attr->setStoreId(0)->getSource()->getOptionId($attributeValue);

        $product->setData($attribute, $avid);
        return true;
    }

    /**
     * Set Multiselect attribute value
     */
    public function setMultiSelectAttribute(&$product, $attribute, $attributeValue)
    {

        $attr = $product->getResource()->getAttribute($attribute);
        $valueArray = explode('||', $attributeValue);
        foreach ($valueArray as $option) {
            $option = ltrim($option);
            $avid[] = $attr->setStoreId(0)->getSource()->getOptionId($option);
        }

        $avidData = implode(',', $avid);

        $product->setData($attribute, $avidData);
        return true;

    }

    /**
     * Set Textarea attribute value
     */
    public function setTextareaAttribute(&$product, $attribute, $attributeValue)
    {

        $product->setData($attribute, $attributeValue);
        return true;

    }

    /**
     * Set Text attribute
     */
    public function setTextAttribute(&$product, $attribute, $attributeValue)
    {
        

        $product->setData($attribute, $attributeValue);
        return true;

    }

    public function updateSimpleProduct($productData)
    {

        $product = $this->productFactory->create();

        //Get product ID by sql serv ID

        $id = $this->getProductIdBySqlServerId($productData['sql_serv_id']);
        $product->load($id);
        $product->setStoreId(0);
        if (!$id) {
            return 'Product Not Found!';
        }

        $categoryIds = [];
        foreach ($productData as $attributeKey => $columValue) {

            if (!empty($columValue)) {
                $columValue = $this->sanitizeValue($columValue);

                if ($attributeKey != 'sql_serv_id') {
                    if ($attributeKey == "category.ids") {
                        $categoryIds = $this->validateCatId($columValue);

                    } else {
                        $this->setAttribute($product, $attributeKey, $columValue);
                    }
                }
            }

        }
        $product->save();

        $mageCatId = [];
        foreach ($categoryIds as $cat) {

            $mageCategory = $this->findMagentoIdBySqlServId($cat);
            if ($mageCategory) {
                $mageCatId[] = $mageCategory->getId();
            }


        }

    }

    public function validateCatId($categoryId)
    {

        //$catId = str_replace(';',",",$categoryId);
        $catId = explode(';', $categoryId);
        return $catId;

    }

    public function getCollectionSqlServId($id)
    {
        $collectionProd = $this->collectionPlatform->create();
        $col = $collectionProd->getCollection()
            ->addFieldToFilter('sql_serv_id', $id)
            ->getFirstItem();

        if ($col->getArchiCollectionId()) {
            return $col->getArchiCollectionId();
        }

        return '';
    }

    public function getPlatformSqlServId($id)
    {
        $collectionProd = $this->platformModel->create();
        $col = $collectionProd->getCollection()
            ->addFieldToFilter('sql_serv_id', $id)
            ->getFirstItem();

        if ($col->getArchiPlatformId()) {
            return $col->getArchiPlatformId();
        }

        return '';
    }


    public function updateCategory(&$product, $categoryIds)
    {
        $this->categoryLinkManagement->assignProductToCategories(
            $product->getSku(),
            $categoryIds
        );
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
