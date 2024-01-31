<?php

namespace Capgemini\Configurable\ViewModel;

use Capgemini\Configurable\Block\Configurable\Child;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class AvailabilityInfo implements ArgumentInterface
{
    /**
     * @param ProductInterface $product
     * @param string $infoType
     * @return null
     */
    public function getAvailabilityFrontendInfo(ProductInterface $product, string $infoType)
    {
        /** @var Attribute $attribute */
        $attribute = $product
            ->getResource()
            ->getAttribute(Child::AVAILABILITY_ATTRIBUTE_CODE);

        /** @var Product $product*/

        switch ($infoType) {
            case 'label':

                return $attribute->getStoreLabel();
            case 'value':

                return $attribute->getFrontend()->getValue($product);
            default:

                return null;
        }
    }
}
