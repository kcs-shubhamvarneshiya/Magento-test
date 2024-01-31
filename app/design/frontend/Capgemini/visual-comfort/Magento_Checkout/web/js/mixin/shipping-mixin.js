define([
    'jquery',
    'Magento_Checkout/js/action/select-shipping-method',
    'Magento_Checkout/js/checkout-data'
],function ($, selectShippingMethodAction, checkoutData) {
    'use strict';

    var mixin = {

        selectShippingMethod: function (shippingMethod) {
            selectShippingMethodAction(shippingMethod);
            checkoutData.setSelectedShippingRate(shippingMethod['carrier_code'] + '_' + shippingMethod['method_code']);

            var shippingElem = $('.table-checkout-shipping-method .row .radio');

            shippingElem.each(function() {
                if($(this).prop('checked') == true) {
                    $(this).parents('.selected-method').addClass('highlight');
                }else if($(this).prop('checked') == false){
                    $(this).parents('.selected-method').removeClass('highlight');
                }
            });

            return true;
        },
    };

    return function (target) {
        return target.extend(mixin);
    };
});