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
    'jquery-ui-modules/widget'
], function ($, $t) {
    'use strict';

    $.widget('lyonscg.quote_to_pdf', {
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
                self.getPdf();
            }, self));
        },

        getPdf: function() {
            var self = this,
                url = self.options.getPdfUrl;
            console.log('URL = ' + url);
            window.location.href = url + '/';
        },

    });
    return $.lyonscg.quote_to_pdf;
});
