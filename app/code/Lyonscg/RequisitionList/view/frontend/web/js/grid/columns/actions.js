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
    'underscore',
    'mageUtils',
    'uiRegistry',
    'Magento_Ui/js/grid/columns/column',
    'Magento_Ui/js/grid/columns/actions',
    'Lyonscg_RequisitionList/js/modal/confirm',
    'mage/dataPost'
], function (_, utils, registry, Column, Actions, confirm, dataPost) {
    'use strict';

    return Actions.extend({

        /**
         * Shows actions' confirmation window.
         *
         * @param {Object} action - Action's data.
         * @param {Function} callback - Callback that will be
         *      invoked if action is confirmed.
         */
        _confirm: function (action, callback) {
            var confirmData = action.confirm;

            confirm({
                title: confirmData.title,
                content: confirmData.message,
                urlReplace: confirmData.urlReplace,
                urlMerge: confirmData.urlMerge,
                urlCreateOrder: confirmData.urlCreateOrder,
                cartNotEmptyTitle: confirmData.cartNotEmptyTitle,
                actions: {
                    confirm: callback
                }
            });
        }
    });
});
