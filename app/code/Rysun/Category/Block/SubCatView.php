<?php
    namespace Rysun\Category\Block;

    use Magento\Catalog\Block\Product\Context;
    use Magento\Catalog\Model\ProductFactory;
    use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

    class SubCatView extends \Magento\Framework\View\Element\Template {
        public $category;
        public $categoryRepository;
        public $frameworkRegistry;
        public $productFactory;
        public $productCollectionFactory;
        protected $attributeGroupCollection;
        protected $productAttributeCollection;
        protected $attribute;
        protected $attributeRepository;
        protected $config;

        public function __construct(
            Context $context,
            array $data,
            \Magento\Catalog\Model\Category $category,
            \Magento\Catalog\Model\CategoryRepository $categoryRepository,
            \Magento\Framework\Registry $frameworkRegistry,
            ProductFactory $productFactory,
            CollectionFactory $productCollectionFactory,
            \Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory $attributeGroupCollection,
            \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $productAttributeCollection,
            \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute,
            \Magento\Catalog\Model\Product\Attribute\Repository $attributeRepository,
            \Magento\Eav\Model\Config $config
        ) {
            $this->category = $category;
            $this->categoryRepository = $categoryRepository;
            $this->frameworkRegistry = $frameworkRegistry;
            $this->productFactory = $productFactory;
            $this->productCollectionFactory = $productCollectionFactory;

            $this->attributeGroupCollection = $attributeGroupCollection;
            $this->productAttributeCollection = $productAttributeCollection;
            $this->attribute = $attribute;
            $this->attributeRepository = $attributeRepository;
            $this->config = $config;

            parent::__construct($context, $data);
        }

        public function getCurrentCategory() {
            return $this->frameworkRegistry->registry('current_category');
        }

        public function getProductCollectionFactory () {
            return $this->productCollectionFactory->create();
        }

        public function getOptionLabelByOptionId ($attributeEntity = 'catalog_product', $attributeCode = '', $optionId = '') {
            $eavConfig = $this->config;
            $attribute = $eavConfig->getAttribute($attributeEntity, $attributeCode);
            $options = $attribute->getSource()->getAllOptions();

            $optionLabel = '';
            foreach ($options as $option) {
                if ($option['value'] == $optionId) {
                    $optionLabel = $option['label'];
                    break;
                }
            }

            return $optionLabel;
        }

        public function getAttributes() {
            //$product = $observer->getEvent()->getData('product');
            $product = $this->getCurrentProduct();
            //echo $product->getId();exit;
            $attributeSetId = $product->getAttributeSetId();
            $groupCollection = $this->attributeGroupCollection->create()
                ->setAttributeSetFilter($attributeSetId)
                ->load(); // product attribute group collection
            $attributeCollection = [];
            $attributePositionCollection = [];
            $db_attribute_combination = [];
            foreach ($groupCollection as $group) {
                //$attributeGroupCollection[] = $group->getData();
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
    
            //echo "<pre>";
            //print_r([$attributeCollection, $attributePositionCollection]);
            //exit;
            return [$attributeCollection, $attributePositionCollection];
            //echo "<pre>";
            //print_r($all_attribute_options);
            //print_r($attribute_options);exit;
        }
    
        public function getAllAttributeOptions($attributeCollection) {
            foreach($attributeCollection as $attributeValue) {
                //print_r($attributeValue);exit;
                $all_attribute_options[$attributeValue['frontend_label']] = $this->getAttributeOption($attributeValue['attribute_id']);
            }
            return $all_attribute_options;
        }
    
        public function getSelectedAttributeOptions($attributeCollection) {
            $product = $this->getCurrentProduct();
            $attribute_options = [];
            foreach($attributeCollection as $attributeValue) {
                //print_r($attributeValue);exit;
                $multilist = $this->getMultiselectlist($product, 'catalog_product', $attributeValue['attribute_code']);
                $textval = $this->getTextval($product, 'catalog_product', $attributeValue['attribute_code']."_position");
                if ($multilist && $textval) {
                    $attribute_options[$attributeValue['frontend_label']]['value'] = $multilist; // product attribute options collection
                    $attribute_options[$attributeValue['frontend_label']]['position'] = $textval; // product attribute options collection
                }
                $multilist = "";
                $textval = "";
            }
            //echo "<pre>";
            //print_r($attribute_options);exit;
    
            // === Sorting of Array ===
            $columns = array_column($attribute_options, 'position');
            array_multisort($columns, SORT_ASC, $attribute_options);
            
            $ao = array();
            foreach ($attribute_options as $attribute_options_k => $attribute_options_v) {
                $ao[$attribute_options_k] = $attribute_options_v['value'];
            }
            return $ao;
        }
    
        public function getCurrentProduct()
        {
            return $this->registry->registry('current_product');
        }
    
        public function getAttributeOption($attributeId)
        {
            $attributeModel = $this->attribute->load($attributeId);
            $attributeCode = $attributeModel->getAttributeCode();
            //$this->_logger->log('DEBUG','debuglog1234 '.$attributeCode);
            //$options = $this->attributeRepository->get($attributeCode)->getOptions();
            if ($attributeModel->usesSource()) {
                $options = $attributeModel->getSource()->getAllOptions();
            }
            return $options;
        }
    
        public function getMultiselectlist($product, $resourcename, $attributename) {
            $arr = explode(", ", $product->getResource()->getAttribute($attributename)->getFrontend()->getValue($product));
            if($product->getResource()->getAttribute($attributename)->getFrontend()->getValue($product) != "") {
                //exit;
                foreach ($arr as $arr_v) {
                    $new_arr[] = array('label' => $arr_v);
                }
                return $new_arr;
            } else {
                return false;
            }
        }
    
        public function getTextval($product, $resourcename, $attributename) {
            if(is_object($product->getCustomAttribute($attributename))){
                return $product->getCustomAttribute($attributename)->getValue();
            } else {
                return false;
            }
        }
    }
?>