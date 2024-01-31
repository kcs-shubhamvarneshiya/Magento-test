define([
    'jquery',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/checkout-data',
    'Capgemini_ShipOnDate/js/model/delivery-date-checkout-data',
], function (
        $, quote, checkoutData, deliveryDateStorage
    ) {
        'use strict';

        return function (target) {
            return target.extend({
                getShippingMethodTitle: function () {
                    let title = this._super();
                    let shippingMethod = quote.shippingMethod();
                    if (this.getDeliveryDate() && shippingMethod) {
                        title = shippingMethod['carrier_title'];
                    }

                    return title;
                },
                getDeliveryDate: function () {
                    let shippingDate = $("input[name='delivery_date']");
                    let savedShippingDate = deliveryDateStorage.getSelectedDeliveryDate();
                    if ((shippingDate && shippingDate.val()) || savedShippingDate) {
                        let shippingDateValue = shippingDate.val();
                        if (!shippingDateValue) {
                            shippingDateValue = savedShippingDate;
                        }
                        return shippingDateValue.replaceAll('-', '/');
                    }
                    return false;
                }
            });
        }
    }
);
