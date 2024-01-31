define([
    'jquery',
    'Magento_Customer/js/customer-data',
    'Capgemini_DataLayer/js/action/get-cached-response'
], function ($, customerData, getCachedResponse) {
    'use strict';

    return function (controlCookieName, ajaxUrl, callback) {
        let customerObs = customerData.get('customer'),
            customerVal = customerObs();
        if (!$.isEmptyObject(customerVal)) {
            callback(customerVal)
        }
        if (document.cookie.indexOf(controlCookieName) > -1) {
            getCachedResponse(ajaxUrl).then(
                result => { callback(JSON.parse(result)) },
                ()     => { callback({}) }
            )
        }
        customerObs.subscribe(callback);
    }
})
