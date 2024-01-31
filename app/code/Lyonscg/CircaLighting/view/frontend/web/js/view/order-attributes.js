define([
    'jquery',
    'underscore',
    'Magento_Ui/js/form/form',
    'Amasty_Orderattr/js/model/attribute-sets/shipping-attributes',
    'Amasty_Orderattr/js/model/validate-and-save',
    'Amasty_Orderattr/js/checkout-data',
    'ko',
    'uiRegistry'
], function(
    $,
    _,
    Component,
    attributesForm,
    validateAndSave,
    checkoutData,
    ko,
    registry
) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Lyonscg_CircaLighting/order-attributes',
            attributeCodes: []
        },
        initialize: function() {
            this._super();

            // make sure loader is initialized on body
            $('body').loader();

            if (window.orderAttributesData !== undefined) {
                registry.async('orderAttributesProvider')(function (orderAttributesProvider) {
                    orderAttributesProvider.set('orderAttributes', window.orderAttributesData);
                });
                checkoutData.setCheckoutData('amastyShippingMethodAttributes', window.orderAttributesData);
            }
            return this;
        },
        showLoader: function() {
            $('body').loader('show');
        },
        hideLoader: function() {
            $('body').loader('hide');
        },
        onSubmit: function() {
            this.showLoader();
            var data = this.source.get('orderAttributes'),
                amastyCheckoutProvider = registry.get('amastyCheckoutProvider');

            amastyCheckoutProvider.set('amastyShippingMethodAttributes', data);
            checkoutData.setCheckoutData('amastyShippingMethodAttributes', data);
            var result = $.Deferred();
            var self = this;
            validateAndSave(attributesForm).done(
                function() {
                    self.hideLoader();
                    result.resolve();
                }
            ).fail(
                function () {
                    self.hideLoader();
                    result.reject();
                }
            );

            return result.promise();
        }
    });
});
