define(['jquery', 'Capgemini_ExponeaSubscription/js/subscription-processor'], function ($, subscription) {
    'use strict';

    return function (config, node) {
        let form = $(node),
            eventData = {
                form: form,
                emailInput: form.find(config.emailInFormSelector),
                confirmationCheckbox: config.confirmationCheckboxSelector && form.find(config.confirmationCheckboxSelector),
                importSource: config.importSource,
                storeCode: config.storeCode,
                successMessage: config.successMessage,
                errorMessageTrack: config.errorMessageTrack,
                errorMessageIdentify: config.errorMessageIdentify,
                sendToServer: config.sendToServer || false
            }

        form.on('submit', null, eventData, function (event) {
            return subscription.process(eventData);
        });
    }
})
