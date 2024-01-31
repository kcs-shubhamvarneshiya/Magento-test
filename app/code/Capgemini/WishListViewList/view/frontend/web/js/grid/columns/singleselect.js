define([
    'Magento_Ui/js/grid/columns/multiselect',
    'underscore',
    'jquery',
    'mage/translate'
], function (Multiselect, _, $, $t) {
    'use strict';

    return Multiselect.extend({
        defaults: {
            headerTmpl: 'Capgemini_WishListViewList/grid/columns/single-select',
        },
        initialize: function () {
            this._super();
            window.FORM_KEY = $.mage.cookies.get('form_key');
        },
    });
});
