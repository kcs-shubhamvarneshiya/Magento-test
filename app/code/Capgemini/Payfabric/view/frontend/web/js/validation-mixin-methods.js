define(['jquery'], function($) {
    'use strict';

    let noAlphaForPhoneExp = /^\+?[0-9]+[ ]*[-]?[ ]*(\([0-9]+\)[ ]*[-]?[ ]*)?([0-9]+[ ]*[-]?[ ]*)*[0-9][ ]*$/;
    return function() {
        $.validator.addMethod(
            'validate-phone-no-alpha',
            function(value, element) {
                return noAlphaForPhoneExp.test(value);
            },
            $.mage.__('Please provide a valid phone number.')
        );
        $.validator.addMethod(
            'validate-16-digits-limit',
            function (value, element) {
                return value.replace(/\D/g, '').length <= 16;
            },
            $.mage.__('The phone number may not contain more then 16 digits.')
        )
    }
});
