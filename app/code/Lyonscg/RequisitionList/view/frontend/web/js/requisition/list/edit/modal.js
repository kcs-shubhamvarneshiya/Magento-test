/**
 * Lyonscg_RequisitionList
 *
 * @category  Lyons
 * @package   Lyonscg_RequisitionList
 * @author    Tetiana Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */
define([
    'Magento_RequisitionList/js/modal/modal-component',
    'jquery',
    'underscore',
    'jquery/validate',
    'mage/validation',
    'mage/translate'
], function (ModalComponent, $, _) {
    'use strict';

    return ModalComponent.extend({
        defaults: {
            options: {
                modalClass: 'modal-slid requisition-popup',
                focus: '.requisition-popup .input-text:first',
                buttons: [
                    {
                        'class': 'action primary confirm btn btn-primary',
                        text: $.mage.__('Save Quote'),
                        actions: ['actionDone']
                    },
                    {
                        'class': 'action cancel btn btn-secondary',
                        text: $.mage.__('Cancel'),
                        actions: ['actionCancel']
                    }
                ]
            }
        },

        /**
         * Set values
         *
         * @param {Object} data
         * @returns void
         */
        setValues: function (data) {
            this.elems().forEach(function (elem) {
                if (_.isFunction(elem.setValues)) {
                    elem.setValues(data);
                }
            });
        },

        /**
         * Get values
         *Customized to get velues for extension attributes
         * @returns {Object}
         */
        getValues: function () {
            var values = {};

            this.elems().forEach(function (elem) {
                if (_.isFunction(elem.getValues)) {
                    _.extend(values, elem.getValues());
                }
            });
            var extension_attributes = {};
            _.forEach(values, function(value, key) {
                if (key == 'po_number') {
                    extension_attributes[key] = value;
                    delete values[key];
                }
            });
            values.extension_attributes = extension_attributes;
            return values;
        },

        /**
         * Open modal
         *
         * @return {Promise}
         */
        openModal: function () {
            this._super();
            this.dfd = $.Deferred();
            return this.dfd.promise();
        },

        /**
         * Action done
         */
        actionDone: function () {
            var form = $(this.modal).find('form').validation();

            if (form.valid()) {
                this.dfd.resolve(this.getValues());
                this.closeModal();
            }
        },

        /**
         * Action cancel
         */
        actionCancel: function () {
            this.dfd.reject();
            this.closeModal();
        }
    });
});
