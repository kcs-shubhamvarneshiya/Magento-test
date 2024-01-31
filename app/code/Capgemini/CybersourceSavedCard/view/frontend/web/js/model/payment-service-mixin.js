define([
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/action/select-payment-method'
], function (wrapper, quote, selectPaymentMethod) {
    'use strict';

    return function (paymentService) {
        paymentService.setPaymentMethods = wrapper.wrapSuper(paymentService.setPaymentMethods, function (methods) {
            let regex = /^chcybersource_cc_vault_\d*$/;
            let selectedVault = null;

            //chek if saved card is selected
            if (quote.paymentMethod() && regex.test(quote.paymentMethod().method)) {
                let methodIsAvailable = methods.some(function (item) {
                    return item.method === 'chcybersource_cc_vault';
                });
                if (methodIsAvailable) {
                    selectedVault = quote.paymentMethod();
                }
            }

            this._super(methods);

            //reselected saved card option after updating payment methods
            if (selectedVault) {
                selectPaymentMethod(selectedVault);
            }
        });

        return paymentService;
    };

});
