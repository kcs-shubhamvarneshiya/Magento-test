define([
    'jquery',
    'Magento_Ui/js/modal/confirm',
    'jquery-ui-modules/widget'
], function ($, confirm) {
    'use strict';

    return function (widget) {

        $.widget('mage.address', widget, {
            options: {
                title: $.mage.__('Delete Address'),
                deleteConfirmMessage: $.mage.__('Are you sure you want to delete this address?')
            },

            _deleteAddress: function (e) {
                e.stopImmediatePropagation();
                var self = this;

                confirm({
                    title: this.options.title,
                    content: this.options.deleteConfirmMessage,
                    modalClass: 'delete__address',
                    focus: 'none',
                    actions: {

                        /** @inheritdoc */
                        confirm: function () {
                            if (typeof $(e.target).parent().data('address') !== 'undefined') {
                                window.location = self.options.deleteUrlPrefix + $(e.target).parent().data('address') +
                                    '/form_key/' + $.mage.cookies.get('form_key');
                            } else {
                                window.location = self.options.deleteUrlPrefix + $(e.target).data('address') +
                                    '/form_key/' + $.mage.cookies.get('form_key');
                            }
                        }
                    }
                });

                return false;
            }

        });

        return $.mage.address;
    }
});