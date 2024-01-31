define([
    'Magento_Customer/js/customer-data'
], function (customerData) {
    'use strict';

    var mixin = {

        initialize: function() {
            this._super();
            this.customHeightAvailabilityMessage = customerData.get('custom_height_availability_message');
        },

        /**
         *
         * @param {Column} elem
         */
        getCustomHeightAvailabilityMessage: function () {
            return this.customHeightAvailabilityMessage().message;
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
