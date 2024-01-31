define([
    'jquery'
], function($) {
    'use strict';
    return function(widget) {
        $.widget('mage.SwatchRenderer', widget, {
            _init: function() {
                this._super();
                $('.' + this.options.classes.attributeInput).on('change', this._switchProduct.bind(this));
            },

            _switchProduct: function() {
                if (!window.specificationsData || window.specificationsData['configurable'] === undefined) {
                    console.log('no specification data');
                    return;
                }
                var productId = this.getProduct();
                var defaultData = window.specificationsData['configurable'];
                var childData = window.specificationsData[productId];

                for (var attribute in defaultData) {
                    if (!defaultData.hasOwnProperty(attribute)) {
                        continue;
                    }
                    var value = defaultData[attribute];
                    if (childData && childData.hasOwnProperty(attribute) && childData[attribute]) {
                        value = childData[attribute];
                    }
                    if (value === null) {
                        continue;
                    }

                    if (attribute === 'sku') {
                        $('.product-info-main .product.attribute.sku').text(value);
                    } else {
                        $('.product-attribute-specs-table .data.' + attribute).text(value);
                    }
                }
            },

            _UpdatePrice: function() {
                this._super();
                // Show Sale/Trade Price label on PDP after swatch click
                $(this.options.normalPriceLabelSelector).show();
            },
        });
        return $.mage.SwatchRenderer;
    };
});
