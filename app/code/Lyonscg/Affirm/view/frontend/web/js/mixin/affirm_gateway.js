define(['Magento_Customer/js/customer-data'], function (customerData) {
    'use strict';

    var mixin = {
        defaults: {
            template: 'Lyonscg_Affirm/payment/form'
        },

        getZeroInventoryItems() {
            let cart = customerData.get('cart')();
            if (!_.isUndefined('zero_inventory_items_ids')) {
                if (!cart.hasOwnProperty('zero_inventory_items_ids')) {
                    cart['zero_inventory_items_ids'] = ko.observable();
                }
            }
            return cart['zero_inventory_items_ids'];
        },

        getDelayedShippingMessage() {
            return window.checkoutConfig.payment['affirm_gateway'].delayedShippingMessage;
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
