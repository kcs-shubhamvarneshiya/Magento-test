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
 * Class Accessories
 * @package Lyonscg\Catalog\Block\Product\ProductList
 */
class Accessories extends \Lyonscg\Catalog\Block\Product\ProductList\Collection
{

  /**
   * Prepare data
   *
   * @return $this
   */
  protected function _prepareData()
  {
    $product = $this->catalogHelper->getChildProduct($this->getProduct());
    $skus = explode(',', $product->getAccessories() ?? '');
    $this->_itemCollection = $this->productCollectionFactory->create()
      ->addFieldToFilter('sku', ['in' => $skus])
      ->addStoreFilter();
    if (!empty($limit = $this->configHelper->getAccessoriesLimit())) {
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
}
