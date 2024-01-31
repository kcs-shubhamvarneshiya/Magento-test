/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'Magento_Ui/js/grid/massactions',
    'mageUtils',
    'underscore',
    'jquery',
    'mage/translate'
], function (Massactions, utils, _, $, $t) {
    'use strict';

    return Massactions.extend({
        defaults: {
            template: 'Capgemini_WishListViewList/grid/action-delete',
            excludeMode: false,
        },
        initialize: function () {
            this._super();
            window.FORM_KEY = $.mage.cookies.get('form_key');
        },
        defaultCallback: function (action, data) {
            var itemsType = 'selected',
                selections = {};

            selections[itemsType] = data[itemsType];

            if (!selections[itemsType].length) {
                selections[itemsType] = false;
            }

            _.extend(selections, data.params || {});

            utils.submit({
                url: action.url,
                data: selections
            });
        },
    });
});
