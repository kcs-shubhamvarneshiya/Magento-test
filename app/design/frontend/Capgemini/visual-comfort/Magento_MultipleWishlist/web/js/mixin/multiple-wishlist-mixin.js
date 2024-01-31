/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mage/template',
    'mage/validation/url',
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/modal/confirm',
    'mage/translate',
    'Magento_Ui/js/modal/prompt',
    'mage/validation/validation',
    'jquery-ui-modules/widget',
    'mage/dataPost',
    'mage/dropdowns'
], function ($, mageTemplate, urlValidator, alert, confirm, $t) {
    'use strict';

    return function (widget) {

        $.widget('mage.multipleWishlist', widget, {

            // Update the "open" method to include the helper class on the body element
            _showCreateWishlist: function (url, isAjax) {
                this.createTmpl ? this.createTmpl.show() : this.createModal();
                $('body').addClass('_has-modal');
                $('#' + this.options.createTmplData.popupWishlistFormId).attr('action', url);
                $(this.options.createTmplData.focusElement).trigger('focus');
                this.createAjax = isAjax;
            },

            // Remove the helper class on the body element when closing
            clickCloseBtnHandler: function () {
                if (this.validation) {
                    this.validation.resetForm();
                }

                this.createTmpl.hide();
                $('body').removeClass('_has-modal');
            },
        });


        return $.mage.multipleWishlist;
    }
});
