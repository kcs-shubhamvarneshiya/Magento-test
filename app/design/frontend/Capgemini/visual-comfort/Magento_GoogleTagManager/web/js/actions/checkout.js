/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'Magento_Checkout/js/view/payment',
    'Magento_GoogleTagManager/js/google-tag-manager'
], function ($, payment) {
    'use strict';

    /**
     * Dispatch checkout events to GA
     *
     * @param {Object} cart - cart data
     * @param {String} stepIndex - step index
     * @param {String} stepDescription - step description
     *
     * @private
     */
    function notify(data, stepIndex, stepDescription) {
        var i = 0,
            product,
            dlUpdate = {
                'pageType': 'checkout',
                'hashedEmail': data.customer['hashedEmail'],
                'loggedinStatus': data.customer['loggedinStatus'],
                'currencyCode': data.customer['currencyCode'],
                'tradeCustomer': data.customer['tradeCustomer'],
                'customerClass': data.customer['customerClass'],
            'event': 'checkout',
                'ecommerce': {
                    'currencyCode': window.dlCurrencyCode,
                    'checkout': {
                        'actionField': {
                            'step': stepIndex,
                            'description': stepDescription
                        },
                        'products': [ ]
                    }
                }
            };

        for (i; i < data.cart.length; i++) {
            product = data.cart[i];
            dlUpdate.ecommerce.checkout.products.push({
                'id': product.id,
                'name': product.name,
                'price': product.price,
                'quantity': product.qty,
                'category': product.category,
                'fullPrice': product.fullPrice,
                'olapic': product.olapic,
                'brand': product.brand
            });
        }

        window.dataLayer.push(dlUpdate);
    }

    return function (data) {
        var events = {
                shipping: {
                    desctiption: 'shipping',
                    index: '2'
                },
                payment: {
                    desctiption: 'payment',
                    index: '3'
                }
            },
            subscription = payment.prototype.isVisible.subscribe(function (value) {
                if (value) {
                    notify(data, events.payment.index, events.payment.desctiption);
                    subscription.dispose();
                }
            });

        window.dataLayer ?
            notify(data, events.shipping.index, events.shipping.desctiption) :
            $(document).on(
                'ga:inited',
                notify.bind(this, data, events.shipping.index, events.shipping.desctiption)
            );
    };
});

