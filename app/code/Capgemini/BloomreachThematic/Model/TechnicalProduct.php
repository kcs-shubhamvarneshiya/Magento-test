<?php

namespace Capgemini\BloomreachThematic\Model;

use Magento\Catalog\Model\Product as Orig;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

class TechnicalProduct extends Orig
{
    const IS_THEMATIC_PRODUCT_DATA_KEY = 'is_thematic_product';

    public function getProductUrl($useSid = null, $withSelection = false)
    {
        $url = $this->getData('br_product_url');

        if ($withSelection === true && $this->getTypeId() === Configurable::TYPE_CODE) {
            $baseChild = $this->getTypeInstance()->getUsedProducts($this)[0] ?? null;
            if ($baseChild !== null) {
                $url .= '?selected_product=' . $baseChild->getId();
            }
        }

        return $url;
    }

    public function getDesignerLabel()
    {
        $designer = $this->getData('designer');

        return is_numeric($designer) ? $this->getAttributeText('designer') : $designer;
    }

    protected function _construct()
    {
        $this->setData(self::IS_THEMATIC_PRODUCT_DATA_KEY, true);
        parent::_construct();
    }
}
