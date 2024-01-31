define([
    'jquery',
    'mage/utils/wrapper',
    'Capgemini_ExponeaSubscription/js/subscription-processor'
], function ($, wrapper, subscription) {
    'use strict';


    return function (target) {
        let newSaveShippingInformation = target.saveShippingInformation;
        newSaveShippingInformation = wrapper.wrap(newSaveShippingInformation, function (original, type) {
            let form = $('form[data-role=email-with-possible-login]');
            if (form.length === 0) {
                return original(type)
            }
            let dataObject = {
                form: form,
                emailInput: form.find('#customer-email'),
                confirmationCheckbox: form.find("#is_subscribed"),
                importSource: 'Guest Checkout',
                storeCode: window.checkoutConfig.customStoreCode,
                sendToServer: false
            }
            return original(type).then(() => {subscription.process(dataObject)});
        });
        target.saveShippingInformation = newSaveShippingInformation;
        return target;
    }
})
