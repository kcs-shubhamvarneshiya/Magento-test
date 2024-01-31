define([
    'jquery',
    'Magento_Checkout/js/model/quote'
], function($, quote) {
    'use strict';

    return function(shippingInformation) {
        return shippingInformation.extend({
            isOverWeight: function() {
                var method = quote.shippingMethod();
                return method.extension_attributes && method.extension_attributes.is_overweight;
            }
        });
    };
});
