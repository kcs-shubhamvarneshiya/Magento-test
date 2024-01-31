define([
    'jquery',
    'ko',
    'Magento_Checkout/js/model/quote',
    'Magento_Ui/js/form/element/abstract',
    'Capgemini_ShipOnDate/js/model/delivery-date-checkout-data',
    'mage/calendar',
], function ($, ko, quote, Component, deliveryDateStorage) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Capgemini_ShipOnDate/field/ship-date-picker',
            value: deliveryDateStorage.getSelectedDeliveryDate(),
            listens: {
                value: 'valueHasChanged',
            },
        },
        initObservable: function () {
            this._super().observe(['value']);
            return this;
        },
        initialize: function () {
            this._super();
            var method_code = window.checkoutConfig.shipping.delivery_date_method_code;
            var first_day = parseInt(window.checkoutConfig.shipping.delivery_date_first);
            var last_day = parseInt(window.checkoutConfig.shipping.delivery_date_last);
            var allowed_days = window.checkoutConfig.shipping.delivery_date_allowed_days;
            var format = 'mm-dd-yy';

            var allowedDays = allowed_days.split(",").map(function(item) {
                return parseInt(item, 10);
            });

            ko.bindingHandlers.datetimepicker = {
                init: function (element, valueAccessor, allBindingsAccessor) {
                    var $el = $(element);

                    var minDate = new Date();
                    minDate.setDate(minDate.getDate() + first_day);

                    var lastDate = new Date();
                    lastDate.setDate(minDate.getDate() + last_day);

                    var options = {
                        minDate: minDate,
                        maxDate: lastDate,
                        dateFormat:format,
                        beforeShowDay: function(date) {
                            var day = date.getDay() + 1;
                            if(allowedDays.indexOf(day) > -1) {
                                return [true];
                            } else {
                                return [false];
                            }
                        }
                    };

                    $el.datepicker(options);

                    var writable = valueAccessor();
                    if (!ko.isObservable(writable)) {
                        var propWriters = allBindingsAccessor()._ko_property_writers;
                        if (propWriters && propWriters.datetimepicker) {
                            writable = propWriters.datetimepicker;
                        } else {
                            return;
                        }
                    }
                    writable($(element).datetimepicker("getDate"));
                },
                update: function (element, valueAccessor) {
                    var widget = $(element).data("DateTimePicker");
                    //when the view model is updated, update the widget
                    if (widget) {
                        var date = ko.utils.unwrapObservable(valueAccessor());
                        widget.date(date);
                    }
                }
            };

            return this;
        },
        valueHasChanged: function () {
            var self = this;
            deliveryDateStorage.setSelectedDeliveryDate(self.value());
        },
    });
});
