define([
    'jquery',
    'underscore',
    'uiComponent'
], function (
    $,
    _,
    Component
) {
    'use strict';

    return Component.extend({
        items: window.checkoutConfig.items_collection_config,
        getItemsData: function () {
            return this.items
        }
    });
});
