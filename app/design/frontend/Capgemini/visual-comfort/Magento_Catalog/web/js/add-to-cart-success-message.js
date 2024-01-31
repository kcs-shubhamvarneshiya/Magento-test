define([
    'jquery',
    'underscore'
], function($, _) {
    'use strict';
    $.widget('capgemini.addToCartSuccessMessage', {
        options: {
        },
        containerSelector: '#add-to-cart-success-message',

        _create: function() {
            $(document).on('ajax:addToCart', this._onAddToCart.bind(this));
        },
        _onAddToCart: function(event, data) {
            // data: sku, productIds, form, response
            try {
                $(this.containerSelector).show();
            } catch (e) {}
        },
    });
    return $.capgemini.addToCartSuccessMessage;
});
