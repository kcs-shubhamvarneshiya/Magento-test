/**
 * Lyonscg_RequisitionList
 *
 * @category  Lyons
 * @package   Lyonscg_RequisitionList
 * @author    Tetiana Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */

define([
    'jquery',
    'mage/translate',
    'mage/storage',
    'Magento_Ui/js/modal/confirm',
    'jquery-ui-modules/widget'
], function ($, $t, storage, confirm) {
    'use strict';

    $.widget('lyonscg.quote_to_order', {
        options: {
            triggerEvent: 'click'
        },

        _create: function() {
            this._bind();
        },

        _bind: function() {
            var self = this;
            self.element.on('click', $.proxy(function (event) {
                event.preventDefault();
                self.confirmAction();
            }, self));
        },

        confirmAction: function() {
            var self = this;
            confirm({
                modalClass: 'add-to-cart-popup',
                content: self.options.confirmation.message,
                title: self.options.confirmation.title,
                actions: {
                    confirm: function () {
                        if (self.isCartNotEmpty()) {
                            self.cartNotEmptyPopup();
                        } else {
                            window.location.href = self.options.confirmation.urlCreateOrder;
                        }
                    }
                }
            });
        },

        isCartNotEmpty: function () {
            var cacheKey = 'mage-cache-storage';
            var cacheStorage = localStorage.getItem(cacheKey);
            cacheStorage = JSON.parse(cacheStorage);
            return cacheStorage.cart['summary_count'] > 0;
        },

        cartNotEmptyPopup: function() {
            var self = this,
                popupOptions = {
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    title: self.options.confirmation.cartNotEmptyTitle,
                    modalClass: 'cart-not-empty',
                    buttons: [
                        {
                            text: $.mage.__('Merge with Existing Cart'),
                            class: 'merge-cart',
                            click: function () {
                                this.closeModal();
                                window.location.href = self.options.confirmation.urlMerge;
                            }
                        },
                        {
                            text: $.mage.__('Replace Cart'),
                            class: 'replace-cart',
                            click: function () {
                                this.closeModal();
                                window.location.href = self.options.confirmation.urlReplace;
                            }
                        }]
                };
            $.mage.modal(popupOptions, $('#cart-not-empty-popup'));
            $("#cart-not-empty-popup").modal('openModal');
        }
    });
    return $.lyonscg.quote_to_order;
});
