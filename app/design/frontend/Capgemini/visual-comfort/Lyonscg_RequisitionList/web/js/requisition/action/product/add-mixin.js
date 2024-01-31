define([
    'Magento_Customer/js/customer-data'
], function(customerData) {
    'use strict';
    return function(productAdd) {
        return productAdd.extend({
            defaults: {
                template: 'Magento_RequisitionList/requisition-list/action/product/add',
            },
            initialize: function() {
                this._super();
                this.customer = customerData.get('customer');
            },
            canAddToRequisitionList: function() {
                return this.requisition().is_enabled && this.isTradeUser();
            },
            isTradeUser: function() {
                return this.customer().companyType === 'trade';
            }
        });
    };
});
