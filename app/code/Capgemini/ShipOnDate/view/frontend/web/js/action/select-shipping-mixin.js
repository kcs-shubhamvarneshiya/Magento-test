define([
    'jquery',
    'Capgemini_ShipOnDate/js/view/delivery-date',
    'mage/utils/wrapper',
    'Capgemini_ShipOnDate/js/model/delivery-date-checkout-data'
], function ($, deliveryDate, wrapper, deliveryDateStorage) {
    'use strict';
    return function (selectShippingMethod) {
        return wrapper.wrap(selectShippingMethod, function (originalSelectShippingMethod, shippingMethod) {
            originalSelectShippingMethod(shippingMethod);
            if (shippingMethod !== null && shippingMethod.carrier_code === 'ship_on_date') {
                if ($("input[name='delivery_date']").val() !== 'undefined' &&
                    $("input[name='delivery_date']").val() === ''
                ) {
                    $("input[name='delivery_date']").datepicker('show');
                }
            } else {
                $("input[name='delivery_date']").val('');
                deliveryDateStorage.setSelectedDeliveryDate(null);
            }
        });
    };
});
