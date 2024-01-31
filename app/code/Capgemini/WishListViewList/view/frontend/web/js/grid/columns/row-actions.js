define([
    'underscore',
    'mageUtils',
    'uiRegistry',
    'Capgemini_WishListViewList/js/grid/columns/actions'
], function (_, utils, registry, Actions) {
    'use strict';

    return Actions.extend({
        defaults: {
            headerTmpl: 'Capgemini_WishListViewList/grid/columns/actions-header',
            bodyTmpl: 'Capgemini_WishListViewList/grid/columns/row-actions',
        },
    });
});
