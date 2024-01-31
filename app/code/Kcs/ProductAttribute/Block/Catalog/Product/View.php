<?php
namespace Kcs\ProductAttribute\Block\Catalog\Product;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory;

class View extends \Magento\Framework\View\Element\Template
{
    public $collectionFactory;

    /** @var \Magento\Eav\Api\AttributeSetRepositoryInterface $attributeSet **/
    protected $attributeSet;

    protected $registry;

    protected $_productloader;
  protected $request;
  protected $_groupCollection;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory $_groupCollection,
        \Magento\Framework\App\Request\Http $request,
        CollectionFactory $collectionFactory,
        \Magento\Eav\Api\AttributeSetRepositoryInterface $attributeSet,
        \Magento\Framework\Registry $registry
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->attributeSet = $attributeSet;
        $this->registry = $registry;

        $this->_productloader = $_productloader;
        $this->request = $request;
        $this->_groupCollection = $_groupCollection;
        parent::__construct($context);
    }

    public function getGroupCollection() {
        $groupCollection = $this->collectionFactory->create();
        $attributeSetId = $this->getAttributeSetId();
        //echo $attributeSetId;exit;
        $groupCollection->addFieldToFilter('attribute_set_id', $attributeSetId);
        $groupCollection->addFieldToFilter('attribute_group_name', 'Custom Product Attribute');
        
        $firstItem = $groupCollection->getFirstItem();
        return $firstItem->getData('attribute_group_id');
        //exit;
    }

    public function getAttributeSetId() {
        $product = $this->getCurrentProduct();
        return $product->getAttributeSetId();
        //$attributeSetRepository = $this->attributeSet->get($product->getAttributeSetId());
        //return $attributeSetRepository->getAttributeSetName();
    }

    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }
    
    public function getAttributeGroupId($attributeSetId)
    {
         $groupCollection = $this->_groupCollection->create();
         $groupCollection->addFieldToFilter('attribute_set_id',$attributeSetId);
         $groupCollection->addFieldToFilter('attribute_group_name','Grid Attributes');
         
         
         return $groupCollection->getFirstItem();

    }
    //Get all attribute groups
    public function getAttributeGroups($attributeSetId)
    {
         $groupCollection = $this->_groupCollection->create();
         $groupCollection->addFieldToFilter('attribute_set_id',$attributeSetId);
         
         $groupCollection->setOrder('sort_order','ASC');
         return $groupCollection;

    }
//get attribute by groups
 public function getGroupAttributes($pro,$groupId, $productAttributes){
        $data=[];
        $no =__('No');
        //echo $pro->getId();
        //echo $groupId;
        //exit;
        foreach ($productAttributes as $attribute) {
            //echo "<pre>";
            //print_r($attribute->getFrontend()->getValue($pro));//exit;
          if ($attribute->isInGroup($pro->getAttributeSetId(), $groupId) && $attribute->getIsVisibleOnFront() ) {
              if($attribute->getFrontend()->getValue($pro) && $attribute->getFrontend()->getValue($pro)!='' && $attribute->getFrontend()->getValue($pro)!=$no){
                $data[]=$attribute;
              }
          }

        }
        //exit;
 
  return $data;
 }
}
