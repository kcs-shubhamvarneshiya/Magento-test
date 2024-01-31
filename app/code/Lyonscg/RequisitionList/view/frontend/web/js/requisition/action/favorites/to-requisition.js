/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'Magento_RequisitionList/js/requisition/action/abstract',
    'underscore',
    'jquery'
], function (RequisitionComponent, _, $) {
    'use strict';

    return RequisitionComponent.extend({
        defaults: {
            template: 'Lyonscg_RequisitionList/requisition-list/favorites-action',
            wishlistFormSelector: '#wishlist-view-form',
            title: '',
            action: '',
            'action_data': {},
            modules: {
                editModule: '${ $.editModuleName }'
            }
        },

        /**
         * Get action data
         *
         * @param {Object} list
         * @returns {Object}
         * @protected
         */
        _getActionData: function (list) {
            var selectedItemIds = [];
            $(this.wishlistFormSelector).find('input.checkbox:checked').each(function() {
                selectedItemIds.push($(this).data('selected-item-id'));
            });

            return _.extend(this['action_data'], {
                'list_id': list.id,
                'favorite_item_ids': JSON.stringify(selectedItemIds)
            });
        },
    });
});
