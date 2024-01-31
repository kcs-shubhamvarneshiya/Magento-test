define([
    'jquery',
    'jquery/validate'
], function ($) {
    "use strict";

    let noAlphaForPhoneExp = /^\+?[0-9]+[ ]*[-]?[ ]*(\([0-9]+\)[ ]*[-]?[ ]*)?([0-9]+[ ]*[-]?[ ]*)*[0-9][ ]*$/;
    return function(validator) {
        validator.addRule(
            'validate-phone-no-alpha',
            function(value) {
                return noAlphaForPhoneExp.test(value);
            },
            $.mage.__('Please provide a valid phone number.')
        );
        validator.addRule(
            'validate-16-digits-limit',
            function (value) {
                return value.replace(/\D/g, '').length <= 16;
            },
            $.mage.__('The phone number may not contain more then 16 digits.')
        )

        return validator;
    }
});
