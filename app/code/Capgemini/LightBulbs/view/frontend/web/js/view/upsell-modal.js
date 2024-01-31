define([
    'jquery',
    'underscore',
    'Magento_Ui/js/modal/modal'
], function($, _) {
    'use strict';
    $.widget('capgemini.lightbulbUpSellModal', {
        options: {

        },
        lightbulbUpSellModal: 'lightbulb_upsell_modal',
        modalSelector:        '#lightbulb-upsell-modal',
        modalContentSelector: '#lightbulb-upsell-modal-content',
        modalFormSelector:    '#lightbulb-upsell-modal [data-role=tocart-form]',
        modalContent:         null,
        upSellModal:          null,
        modalOpen:            false,
        _create: function() {
            $(document).on('ajax:addToCart', this._onAddToCart.bind(this));
            if (this.upSellModal === null) {
                this.upSellModal = $(this.modalSelector).modal({
                    type: 'popup',
                    responsive: true,
                    buttons: []
                });
            }
            this.modalContent = $(this.modalContentSelector);
        },
        _onAddToCart: function(event, data) {
            // data: sku, productIds, form, response
            console.log("lightbulb upsell _onAddToCart");
            console.dir(data);
            var isLightbulb = false;
            try {
                var $lightbulbs = data.form.find('input[name^="lightbulb"]');
                isLightbulb = ($lightbulbs.length > 0);
            } catch (e) {}

            try {
                var $lightbulb = data.form.find('input[name^="lightbulb"]:checked');
                if ($lightbulb.length > 0) {
                    window.dataLayer = window.dataLayer || [];
                    $lightbulb.each(function() {
                        var lightbulbEvent = $(this).data('lightbulb-event');
                        if (lightbulbEvent && lightbulbEvent.event === 'lightBulbBundle') {
                            window.dataLayer.push(lightbulbEvent);
                        }
                    });
                }
            } catch (e) {}
            var response = data.response;

            if (response.hasOwnProperty(this.lightbulbUpSellModal) && isLightbulb) {
                this.openModal(response[this.lightbulbUpSellModal]);
            }
        },
        openModal: function(modalHtml) {
            console.log("lightbulb open modal");
            this._updateModalContent(modalHtml);
            this.modalOpen = true;
            this.upSellModal.modal('openModal');
        },

        closeModal: function() {
            console.log("lightbulb close modal");
            this.upSellModal.modal('closeModal');
            this.modalOpen = false;
        },

        _updateModalContent: function(modalHtml) {
            this.modalContent.html(modalHtml);
            $(this.modalFormSelector).each(function() {
                $(this).catalogAddToCart();
            });
        }
    });
    return $.capgemini.lightbulbUpSellModal;
});
