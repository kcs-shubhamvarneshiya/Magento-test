/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'Magento_GoogleTagManager/js/google-tag-manager'
], function ($) {
    'use strict';

    /**
     * Dispatch product detail event to GA
     *
     * @param {Object} data - product data
     *
     * @private
     */
    function notify(data) {
        var list = data.list;
        delete data.list;
        window.dataLayer.push({
            'event': 'productDetail',
            'ecommerce': {
                'detail': {
                    'products': [data]
                },
                'impressions': [],
                'actionField': [
                    {
                        'list': list,
                        'action': 'detail'
                    }
                ]
            }
        });
    }

    return function (productData) {
        var hasOlapicImage = 'false';
        if ($(".olapic-item").length > 0) {
            hasOlapicImage = 'true';
        }
        productData.olapic = hasOlapicImage;
        window.dataLayer ?
            notify(productData) :
            $(document).on('ga:inited', notify.bind(this, productData));
    };
});
