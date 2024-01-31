<?php
/**
 * Lyonscg_Catalog
 *
 * @category  Lyons
 * @package   Lyonscg_Catalog
 * @author    Tanya Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */

namespace Lyonscg\Catalog\Block\Product\ProductList;

/**
 * Class Collection
 * @package Lyonscg\Catalog\Block\Product\ProductList
 */
class Collection extends \Lyonscg\Catalog\Block\Product\ProductList
{

    /**
     * Prepare data
     *
     * @return $this
     */
    protected function _prepareData()
    {
        $product = $this->catalogHelper->getChildProduct($this->getProduct());
        $this->_itemCollection = $this->productCollectionFactory->create()
            ->addAttributeToSelect('relatives')
            ->addFieldToFilter('relatives', ['eq' => $product->getRelatives()])
            ->addFieldToFilter('sku', ['neq' => $this->getProduct()->getSku()])
            ->addStoreFilter();
        if (!empty($limit = $this->configHelper->getCollectionLimit())) {
            $this->_itemCollection->setPage(1, $limit);
        }

        if ($this->moduleManager->isEnabled('Magento_Checkout')) {
            $this->_addProductAttributesAndPrices($this->_itemCollection);
        }
        $this->_itemCollection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $this->_itemCollection->load();
        foreach ($this->_itemCollection as $product) {
            $product->setDoNotUseCategoryId(true);
        }
        return $this;
    }

    /**
     * Before to html handler
     *
     * @return $this
     */
    protected function _beforeToHtml()
    {
        if ($this->_itemCollection === null) {
            $this->_prepareData();
        }
        return $this;
    }


}
