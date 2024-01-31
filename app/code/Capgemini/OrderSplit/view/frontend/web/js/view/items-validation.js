define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/additional-validators',
    'Capgemini_OrderSplit/js/model/items-validator'
], function (Component, additionalValidators, itemsValidator) {
    'use strict';

    additionalValidators.registerValidator(itemsValidator);

    return Component.extend({});
});
