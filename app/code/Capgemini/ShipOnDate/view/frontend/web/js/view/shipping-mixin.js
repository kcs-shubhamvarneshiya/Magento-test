define(
    [
        'jquery',
        'Magento_Checkout/js/model/quote',
        'ko',
        'mage/translate',
    ], function (
        $,
        quote,
        ko,
        $t
    ) {
        'use strict';

        return function (target) {
            return target.extend({
                validateShippingInformation: function () {
                    let result = this._super();
                    if (quote.shippingMethod() && quote.shippingMethod()['method_code'] === 'ship_on_date' ) {
                        let shipDateEl = $("input[name='delivery_date']");
                        let shipDateVar = shipDateEl.val().replaceAll('-', '/');

                        let isEmpty = !shipDateVar;

                        if (isEmpty) {
                            this.errorValidationMessage(
                                $t('Please, specify the ship date')
                            );
                            return false;
                        }

                        let first_day = parseInt(window.checkoutConfig.shipping.delivery_date_first);
                        let last_day = parseInt(window.checkoutConfig.shipping.delivery_date_last);
                        let allowed_days = window.checkoutConfig.shipping.delivery_date_allowed_days;

                        let allowedDays = allowed_days.split(",").map(function(item) {
                            return parseInt(item, 10);
                        });

                        let minDate = new Date();
                        minDate.setDate(minDate.getDate() + first_day);

                        let lastDate = new Date();
                        lastDate.setDate(minDate.getDate() + last_day);

                        let currentDate = new Date(shipDateVar);
                        let isInRange = (currentDate = minDate || currentDate > minDate) && currentDate <= lastDate;

                        if(allowedDays.indexOf(currentDate.getDay() + 1) === -1) {
                            this.errorValidationMessage(
                                $t('Please, specify the correct ship date')
                            );
                            return false;
                        }
                        if (!isInRange) {
                            this.errorValidationMessage(
                                $t('Please, specify the correct ship date')
                            );
                            return false;
                        }
                    }

                    return result;
                },
            });
        }
    }
);
