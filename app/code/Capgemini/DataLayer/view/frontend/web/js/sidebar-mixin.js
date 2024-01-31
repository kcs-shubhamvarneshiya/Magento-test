define([
    'jquery'
], function ($) {
    'use strict';

    var sidebarWidgetMixin = {

        _removeItemAfter: function (elem) {
            var productData = this._getProductById(Number(elem.data('cart-item')));

            if (!_.isUndefined(productData)) {
                var optionValues = [];
                _.each(productData.options, function (value, key) {
                    optionValues.push(value.option_value);
                });
                $(document).trigger('ajax:removeFromCart', {
                    productIds: [productData['product_id']],
                    productInfo: [
                        {
                            'id': productData['product_id'],
                            'optionValues': optionValues
                        }
                    ]
                });

                if (window.location.href.indexOf(this.shoppingCartUrl) === 0) {
                    window.location.reload();
                }
            }
        },

    };

    return function (targetWidget) {
        $.widget('mage.sidebar', targetWidget, sidebarWidgetMixin);

        return $.mage.sidebar;
    };
});
