define([
    'jquery',
    'Capgemini_DataLayer/js/datalayer-utils'
], function ($, configurableUtils) {
    'use strict';

    var multipleWishlistWidgetMixin = {

        _buildWishlistDropdown: function () {
            this._super();
            $('[data-action="add-to-wishlist"]').on('click', function(evt) {
                var ecommerce = configurableUtils.getSelectedProductData();
                ecommerce.event = 'addToWishlist';
                window.dataLayer.push(ecommerce);
            });
        }

    };

    return function (targetWidget) {
        $.widget('mage.multipleWishlist', targetWidget, multipleWishlistWidgetMixin);

        return $.mage.multipleWishlist;
    };
});
