define([
    'mage/utils/wrapper',
    'jquery'
], function (wrapper, $) {
    'use strict';

    return function (placeOrderAction) {
        return wrapper.wrap(placeOrderAction, function (originalAction, paymentData, redirectOnSuccess) {

            let poNumbersInputs, promoCodesInputs, poNumbers, promoCodes;

            poNumbersInputs = $('.payment-method._active div[data-role=po-numbers] .po-number-wrapper input');
            promoCodesInputs = $('.payment-method._active div[data-role=po-numbers] .promocode-wrapper input');
            poNumbers = [];
            promoCodes = [];

            poNumbersInputs.each(function() {
                if ($(this).val() !== '') {
                    poNumbers.push(
                        {
                            'division': $(this).attr('data-division'),
                            'po_number': $(this).val()
                        }
                    );
                }
            });

            promoCodesInputs.each(function() {
                if ($(this).val() !== '') {
                    promoCodes.push(
                        {
                            'division': $(this).attr('data-division'),
                            'promo_code': $(this).val()
                        }
                    );
                }
            });

            if (paymentData['extension_attributes'] === undefined) {
                paymentData['extension_attributes'] = {};
            }

            paymentData['extension_attributes']['custom_po_numbers'] = poNumbers;
            paymentData['extension_attributes']['custom_promo_codes'] = promoCodes;

            return originalAction(paymentData, redirectOnSuccess);
        });
    };
});
