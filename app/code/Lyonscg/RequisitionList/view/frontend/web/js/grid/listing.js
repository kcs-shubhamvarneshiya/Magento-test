define([
    'Magento_B2b/js/grid/listing'
], function (b2bListing) {
    'use strict';

    return b2bListing.extend({
        defaults: {
            template: 'Lyonscg_RequisitionList/grid/listing'
        },

        // start with this flag being true so we don't get a flash showing the no records message
        waitingForData: true,

        hasData: function () {
            return !!this.rows && !!this.rows.length;
        },

        showNoData: function() {
            return !this.hasData() && !this.waitingForData;
        },

        /**
         * Hides loader.
         */
        hideLoader: function () {
            this.waitingForData = false;
            this._super();
        },

        /**
         * Shows loader.
         */
        showLoader: function () {
            this.waitingForData = true;
            this._super();
        }
    });
});
