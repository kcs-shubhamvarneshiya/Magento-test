<?php

namespace Capgemini\ImportExport\Plugin;

use Magento\CatalogUrlRewrite\Observer\ProductProcessUrlRewriteSavingObserver;
use Magento\Framework\Event\Observer;

class UrlRewrite
{
    /**
     * We trigger URL rewrites regeneration in \Magento\CatalogUrlRewrite\Observer\ProductProcessUrlRewriteSavingObserver
     * by setting  is_changed_websites property of the product to true.
     *
     * @param ProductProcessUrlRewriteSavingObserver $subject
     * @param callable $proceed
     * @param Observer $observer
     * @return void
     */
    public function aroundExecute(ProductProcessUrlRewriteSavingObserver $subject, callable $proceed, Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();

        if ($product->getData('is_url_rewrites_check_regenerate') === true) {
            $product->setStoreId(0);
            $origValue = $product->getIsChangedWebsites();
            $product->setIsChangedWebsites(true);
            $proceed($observer);
            $product->setIsChangedWebsites($origValue);
        } else {
            $proceed($observer);
        }


    }
}
