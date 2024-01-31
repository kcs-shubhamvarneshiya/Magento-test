define([
    'Magento_Checkout/js/view/payment/default',
    'jquery',
    'mage/validation'
], function (Component, $) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Capgemini_PaymentTerms/payment/termswholesale-form',
        },

        /**
         * @return {Object}
         */
        getData: function () {
            return {
                method: this.item.method,
                'additional_data': null
            };
        },

        /**
         * @return {jQuery}
         */
        validate: function () {
            var form = 'form[data-role=termswholesale-form]';

            return $(form).validation() && $(form).validation('isValid');
        },

        getDescription: function () {
            return window.checkoutConfig.payment[this.item.method].description;
        }
    });
});
