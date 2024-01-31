define([
    'jquery',
    'Magento_ConfigurableProduct/js/configurable-utils'
], function ($, configurableUtils) {
    'use strict';

    function getBaseProductData() {
        var product = {
            'category': window.productPageData['category'],
            'brand': window.productPageData['brand'],
            'style': window.productPageData['style'],
            'name': window.productPageData['name'],
            'id': window.productPageData['sku']
        };
        return product;
    }

    function getSelectedProductData() {
        var ecommerce = {
            'ecommerce': {
                'detail': {
                    'products': {
                    }
                }
            },
            'event': '',
            'pageType': 'product_detail_page'
        };
        var product = this.getBaseProductData();
        var elem = configurableUtils.getSelectedSwatch();
        if (elem) {
            product.id = $(elem).data('product-sku');
            product.name = $(elem).data('product-name');
            product.style = $(elem).data('product-style');
        }
        ecommerce.ecommerce.detail.products = [product];
        return ecommerce;
    }

    return {
        getBaseProductData: getBaseProductData,
        getSelectedProductData: getSelectedProductData

    };
});
