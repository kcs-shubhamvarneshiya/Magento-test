define([
    'jquery',
    'mage/utils/wrapper'
], function ($, wrapper) {
    'use strict';

    return function (payloadExtender) {
        return wrapper.wrap(payloadExtender, function (originalAction, payload) {
            payload = originalAction(payload)
            payload.addressInformation.extension_attributes.ship_on_date = $("input[name='delivery_date']").val();
            return payload;
        });
    };
});
