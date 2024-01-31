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
    'mage/dataPost',
    'loader',
    'Magento_Catalog/js/price-utils',
    'jquery-ui-modules/widget'
], function ($, $t, storage, dataPost, loader, priceUtils) {
    'use strict';

    $.widget('lyonscg.quote_update', {
        options: {
            triggerEvent: 'click'
        },

        _create: function() {
            this._bind();
        },

        _bind: function() {
            var self = this;
            self.element.on(self.options.triggerEvent, $.proxy(function (event) {
                event.preventDefault();
                self._quoteUpdate();
            }, self));
        },

        _quoteUpdate: function() {
            var self = this,
            messageContainer = $(self.options.messageContainerElement);
            messageContainer.hide();
            $('#markup-wrapper').trigger('processStart');
            self.updateData();
            storage.post(
                self.options.actionUpdate,
                JSON.stringify(self.options.data)
            ).done(
                function (response) {
                    self.recalculate();
                    $('#markup-wrapper').trigger('processStop');
                    messageContainer.addClass('success').text(self.options.successMessage).show();
                }
            ).fail(
                function (response) {
                    $('#markup-wrapper').trigger('processStop');
                    messageContainer.addClass('error').text(self.options.errorMessage).show();
                }
            );
        },

        recalculate: function() {
            var self = this,
                markup,
                taxRate,
                isTaxExempted,
                markupTotal,
                estSalesTax,
                requisitionListSubtotal,
                requisitionListTotal;
            requisitionListSubtotal = Number(self.options.requisitionListSubtotal);
            markup = Number($(self.options.markupElement).val());
            if (self.isTaxExempted() == 1) {
                taxRate = 0;
            } else {
                taxRate = Number(self.options.taxRate);
            }
            markupTotal = requisitionListSubtotal * markup / 100;
            estSalesTax = (requisitionListSubtotal + markupTotal) * taxRate / 100;
            requisitionListTotal = requisitionListSubtotal + markupTotal + estSalesTax;
            $(self.options.markupTotalElement).text(priceUtils.formatPrice(markupTotal, self.options.priceFormat));
            $(self.options.estSalesTaxElement).text(priceUtils.formatPrice(estSalesTax));
            $(self.options.totalElement).text(priceUtils.formatPrice(requisitionListTotal));
        },

        updateData: function() {
            var self = this;
            self.options.data.requisitionList.extension_attributes.cort_requisition_attributes.markup = $(self.options.markupElement).val();
            self.options.data.requisitionList.extension_attributes.cort_requisition_attributes.is_tax_exempted = self.isTaxExempted();
        },

        isTaxExempted: function() {
            var self = this;
            if ($(self.options.isTaxExemptedElement).is(':checked')) {
                return 1;
            }
            return 0;
        }
    });
    return $.lyonscg.quote_update;
});
