define([
    'jquery',
    'uiComponent',
    'ko'
], function ($,UiComponent, ko) {
    return UiComponent.extend({
        defaults: {
            template: 'Capgemini_TechnicalResources/resources'
        },

        /**
         * Init observable properties
         * @returns {this}
         */
        initObservable: function () {
            this.selectedProductId = ko.observable(this.baseProductId);
            return this;
        },

        /**
         * Get resources for selected product
         * @returns {array}
         */
        getResources: function () {
            return this.productsConfig[this.selectedProductId()].techResources;
        },

        /**
         * Can show resources block
         * @returns {boolean}
         */
        canShow: function() {
            return this.productsConfig[this.selectedProductId()].techResources.length > 0;
        },

        /**
         * Can show Download All link
         * @returns {boolean}
         */
        showDownloadAll: function () {
            return this.productsConfig[this.selectedProductId()].techResources.length > 1;
        },

        /**
         * Download All URL
         * @returns {string}
         */
        getDownloadAllUrl: function () {
            return this.productsConfig[this.selectedProductId()].downloadAllUrl
        }
    });
});
