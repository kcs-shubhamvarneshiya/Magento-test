define([
    'jquery',
    'Capgemini_DataLayer/js/datalayer-utils'
], function ($, dataLayerUtils) {
    'use strict';

    $.widget('capgemini.datalayer_pdp', {
        _create: function () {
            $('.variation-item a').on('addToDataLayer', function(evt) {
                var ecommerce = dataLayerUtils.getSelectedProductData();
                ecommerce.event = 'productOptionView';
                window.dataLayer.push(ecommerce);
            });
        }
    });
    return $.capgemini.datalayer_pdp;
});
