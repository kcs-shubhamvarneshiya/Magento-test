define([
    'jquery',
    'apiPrice',
    'priceQueue',
    'underscore',
    'domReady!'
], function ($, apiPrice, priceQueue, _) {
    'use strict';
    $.widget('cp.priceList',{
        _create: function () {
            this._initPrices();
            this._checkQueueAndUpdatePricesHandler();
            this._initPriceUpdaterHandler();
        },
        _initPrices: function () {
            this._updatePrices();
        },
        _checkQueueAndUpdatePricesHandler: function () {
            $(document).on('contentUpdated', '#amasty-shopby-product-list', this._updatePrices.bind(this));
        },
        _updatePrices: function () {
            let elements = this._getElements();
            if (elements.length > 0) {
                apiPrice.fetchProductsPriceHtml(_(elements).keys());
            }
        },
        _getElements: function () {
            let that = this;
            let elements = [];

            let apiPrices = $('li.product-item .api-price');
            apiPrices.each(function () {
                if ($(this).data('price-init') === undefined) {
                    elements[$(this).data('product-id')] = $(this);
                }
                $(this).attr('data-price-init', 1);
            });

            return elements;
        },
        _initPriceUpdaterHandler: function () {
            let that = this;
            $(document).on("priceApi:pricelistLoaded", function (event, data) {
                let prices = data.result;
                Object.entries(prices).forEach(price => {
                    const [productId, priceHtml] = price;
                    $('li.product-item .api-price[data-product-id = "' + productId + '"]').html(priceHtml);
                });
            })
        }
    });
    return $.cp.priceList;
});
