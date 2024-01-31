<?php
namespace Kcs\CreatePacjson\Block\Catalog\Product\View;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\AbstractProduct;

class ProductAttribute extends \Magento\Framework\View\Element\Template
{
    protected $registry;
    protected $attributeGroupCollection;
    protected $productAttributeCollection;
    protected $attribute;
    protected $attributeRepository;

    public function __construct(
        Context $context,
        array $data,
        \Magento\Framework\Registry $registry,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory $attributeGroupCollection,
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $productAttributeCollection,
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute,
        \Magento\Catalog\Model\Product\Attribute\Repository $attributeRepository
        )
    {
        $this->registry = $registry;
        $this->attributeGroupCollection = $attributeGroupCollection;
        $this->productAttributeCollection = $productAttributeCollection;
        $this->attribute = $attribute;
        $this->attributeRepository = $attributeRepository;
        parent::__construct($context, $data);
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

        return $attributeCollection;
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
            $attribute_options[$attributeValue['frontend_label']] = $this->getMultiselectlist($product, 'catalog_product', $attributeValue['attribute_code']); // product attribute options collection
        }
        return $attribute_options;
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
        foreach ($arr as $arr_v) {
            $new_arr[] = array('label' => $arr_v);
        }
        return $new_arr;
    }
}
