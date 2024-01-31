<?php

namespace Rysun\DataTransfer\Block\Product;

use Magento\Catalog\Block\Product\Image;
use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

class ListProduct extends \Capgemini\WholesalePrice\Block\Product\ListProduct
{
    /**
     * @param Product $product
     * @param string $imageId
     * @param array $attributes
     * @return Image
     */
    public function getAttributeOptionLabels($attributeCode, $collectionValue)
    {
            $attributeOptionArr = [];
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                /* Create a new product object */
                $attributeRepository = $objectManager->create('\Magento\Eav\Api\AttributeRepositoryInterface');
                $attribute = $attributeRepository->get('4', $attributeCode);


                foreach($collectionValue as $optionId){
               
                    $attributeOptionArr[$attributeCode][] =  $attribute->getSource()->getOptionText($optionId);
                }
               

        return $attributeOptionArr;
    }
}
