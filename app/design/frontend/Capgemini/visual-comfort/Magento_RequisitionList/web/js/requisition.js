/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'uiComponent',
    'Magento_Customer/js/customer-data'
], function (Component, customerData) {
    'use strict';

    return Component.extend({
        /** @inheritdoc */
        initialize: function () {
            this._super();
            this.requisition = customerData.get('requisition');
            this.customer = customerData.get('customer');
        },

        /**
         * Is can create.
         *
         * @returns {Boolean}
         */
        isCanCreateList: function () {
            return !this.requisition().items ||
                this.requisition().items.length < this.requisition()['max_allowed_requisition_lists'];
        },

        ifListBlank: function() {
            return !this.requisition().items || this.requisition().items.length < 1;
        },

        canShowQuotesLink: function() {
            return this.requisition().is_enabled && parseInt(this.customer().tradeCustomer, 10) !== 0;
        }
    });
});
