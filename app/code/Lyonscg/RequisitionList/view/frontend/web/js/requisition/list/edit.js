/**
 * Lyonscg_RequisitionList
 *
 * @category  Lyons
 * @package   Lyonscg_RequisitionList
 * @author    Tetiana Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */

define([
    'Magento_RequisitionList/js/requisition',
    'underscore',
    'jquery',
    'uiLayout',
    'mage/storage',
    'mage/dataPost',
    'mage/cookies',
    'Magento_RequisitionList/js/requisition/list/edit/modal'
], function (RequisitionComponent, _, $, layout, storage, dataPost) {
    'use strict';

    return RequisitionComponent.extend({
        defaults: {
            saveUrl: '',
            isAjax: true,
            modules: {
                modal: '${ $.modal }'
            }
        },

        /**
         * Show edit form
         *
         * @param {*} data
         * @returns {Promise}
         */
        edit: function (data) {
            this.modal().setValues(data);
            return this.modal().openModal().then(_.bind(this.save, this));
        },

        /**
         * Save data
         *
         * @param {Object} data
         * @returns {jQuery.Deferred|Boolean}
         * @private
         */
        save: function (data) {
            var save = this.isAjax ?
                this._saveAjax :
                this._save,
                promise;

            if (data.hasOwnProperty('po_number')) {
                if (!data.hasOwnProperty('extension_attributes')) {
                    data['extension_attributes'] = {};
                }
                data.extension_attributes.po_number = data.po_number;
                delete data.po_number;
            }

            $('body').trigger('processStart');
            promise = save.call(this, data);
            promise.always(function () {
                $('body').trigger('processStop');
            });

            return promise;
        },

        /**
         * Save data using ajax
         *
         * @param {Object} data
         * @returns {Promise}
         * @private
         */
        _saveAjax: function (data) {
            return storage.post(
                this.saveUrl,
                JSON.stringify({
                    requisitionList: data
                })
            );
        },

        /**
         * Save data
         *
         * @param {Object} data
         * @returns {Boolean}
         * @private
         */
        _save: function (data) {
            dataPost().postData({
                action: this.saveUrl,
                data: data
            });

            return $.Deferred().resolve().promise();
        }
    });
});
