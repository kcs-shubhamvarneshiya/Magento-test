define([
    'underscore',
    'jquery',
    'Magento_Ui/js/modal/modal'
], function (_, $, modal) {
    'use strict';

    return function (widget) {

        $.widget('mage.catalogAddToCart', widget, {
            options: {
                paperModalErrorContainer: '#paper-model-error-modal',
            },

            _create: function () {
                this._super();
                var self = this;
                $(document).on('ajax:addToCart', function (e, data) {
                    if (!_.isEmpty(data.response.paperModel)) {
                        self.showErrorModal();
                    }
                });
                $(document).on('ajax:addToCart:error', function () {
                    self.showErrorModal();
                });
            },

            showErrorModal: function() {
                var options = {
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    title: 'Add paper template to cart error',
                    modalClass: 'paper-model-error-modal',
                    buttons: [{
                        text: $.mage.__('Close'),
                        class: '',
                        click: function () {
                            this.closeModal();
                        }
                    }]
                };

                var popup = modal(options, $(this.options.paperModalErrorContainer));

                $(this.options.paperModalErrorContainer).modal('openModal');
            },

            enableAddToCartButton: function (form) {
                var paperModelAddToCartButtonText = form.data('add-to-cart-button-text');
                if (typeof paperModelAddToCartButtonText != 'undefined') {
                    this.options.addToCartButtonTextDefault = paperModelAddToCartButtonText;
                }
                this._super(form);
            }
        });

        return $.mage.catalogAddToCart;
    };
});
