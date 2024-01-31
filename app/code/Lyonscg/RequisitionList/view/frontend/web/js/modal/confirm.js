/**
 * Lyonscg_RequisitionList
 *
 * @category  Lyons
 * @package   Lyonscg_RequisitionList
 * @author    Tetiana Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */

/**
 * @api
 */
define([
    'jquery',
    'underscore',
    'mage/translate',
    'jquery-ui-modules/widget',
    'Magento_Ui/js/modal/modal',
    'Magento_Ui/js/modal/modal-component',
    'jquery/jquery-storageapi'
], function ($, _, $t) {
    'use strict';

    $.widget('lcg.confirm', $.mage.confirm, {
        options: {
            buttons: [{
                text: $t('Yes, Add to Cart'),
                class: 'action-primary action-accept',

                /**
                 * Click handler.
                 */
                click: function (event) {
                    this.closeModal(event);
                    this.cartNotEmptyPopup(this.options.urlMerge, this.options.urlReplace, this.options.urlCreateOrder);

                }
            },
                {
                    text: $t('No, Return to Quotes'),
                    class: 'action-secondary action-dismiss',

                    /**
                     * Click handler.
                     */
                    click: function (event) {
                        this.closeModal(event);
                    }
                }]
        },
        /**
         * Close modal window.
         */
        closeModal: function (event, result) {

            result = result || false;

            if (result) {
                if (this.isCartNotEmpty()) {
                    this.options.actions.confirm(event);
                } else {
                    this.options.actions.cancel(event);
                }
            } else {
                this.options.actions.cancel(event);
            }
            this.options.actions.always(event);
            this.element.bind('confirmclosed', _.bind(this._remove, this));

            return this._super();
        },

        isCartNotEmpty: function () {
            var cacheKey = 'mage-cache-storage';
            var cacheStorage = localStorage.getItem(cacheKey);

            cacheStorage = JSON.parse(cacheStorage);

            return cacheStorage.cart['summary_count'] > 0;
        },

        cartNotEmptyPopup: function(urlMerge, urlReplace, urlCreateOrder) {
            var popupOptions = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                title: this.options.cartNotEmptyTitle,
                modalClass: 'cart-not-empty',
                buttons: [
                    {
                        text: $.mage.__('Merge with Existing Cart'),
                        class: 'merge-cart',
                        click: function () {
                            this.closeModal();
                            window.location.href = urlMerge;
                        }
                    },
                    {
                        text: $.mage.__('Replace Cart'),
                        class: 'replace-cart',
                        click: function () {
                            this.closeModal();
                            window.location.href = urlReplace;
                        }
                    }]
            };
            if (this.isCartNotEmpty()) {
                var popup = $.mage.modal(popupOptions, $('#cart-not-empty-popup'));
                $("#cart-not-empty-popup").modal('openModal');
            } else {
                window.location.href = urlCreateOrder;
            }
        }
    });

    return function (config) {
        return $('<div></div>').html(config.content).confirm(config);
    };
});
