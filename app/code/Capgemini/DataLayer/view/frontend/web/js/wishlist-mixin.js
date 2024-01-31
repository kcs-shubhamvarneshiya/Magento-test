define([
    'jquery'
], function ($) {
    'use strict';

    var wishlistWidgetMixin = {

        _create: function () {
            this._super();
            var _this = this;

            if (!this.options.infoList) {
                this.element
                    .on('click', this.options.btnRemoveSelector, $.proxy(function (event) {
                        var ecommerce = _this.getProductData($(event.currentTarget));
                        window.dataLayer.push(ecommerce);
                    }, this))
            }
        },

        getProductData: function($element) {
            var $productItem = $element.closest('.product-item-info').find('.wishlist-item-data-layer');
            var ecommerce = {
                'ecommerce': {
                    'detail': {
                        'products': {
                        }
                    }
                },
                'event': 'Remove from wishlist',
                'pageType': 'My favorites',
                'currencyCode' : $productItem.data('product-currency')
            };

            var product = {
                'name': $productItem.data('product-name'),
                'id': $productItem.data('product-sku'),
                'price': $productItem.data('product-price'),
                'brand': $productItem.data('product-brand'),
                'style': $productItem.data('product-style')
            };
            ecommerce.ecommerce.detail.products = [product];
            return ecommerce;
        }

    };

    return function (targetWidget) {
        $.widget('mage.wishlist', targetWidget, wishlistWidgetMixin);

        return $.mage.wishlist;
    };
});
