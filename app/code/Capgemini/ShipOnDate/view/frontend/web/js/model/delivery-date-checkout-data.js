define([
    'jquery',
    'Magento_Customer/js/customer-data',
    'mageUtils',
    'jquery/jquery-storageapi'
], function ($, storage, utils) {
    'use strict';

    let cacheKey = 'delivery-date-checkout-data',

        /**
         * @param {Object} data
         */
        saveData = function (data) {
            storage.set(cacheKey, data);
        },

        /**
         * @return {*}
         */
        initData = function () {
            return {
                'selectedDeliveryDate': null
            };
        },

        /**
         * @return {*}
         */
        getData = function () {
            var data = storage.get(cacheKey)();

            if ($.isEmptyObject(data)) {
                data = $.initNamespaceStorage('mage-cache-storage').localStorage.get(cacheKey);

                if ($.isEmptyObject(data)) {
                    data = initData();
                    saveData(data);
                }
            }

            return data;
        };

    return {
        /**
         * @param data
         */
        setSelectedDeliveryDate: function (data) {
            var obj = getData();

            obj.selectedDeliveryDate = data;
            saveData(obj);
        },

        /**
         * @returns {null|*}
         */
        getSelectedDeliveryDate: function () {
            return getData().selectedDeliveryDate;
        },
    };
});
