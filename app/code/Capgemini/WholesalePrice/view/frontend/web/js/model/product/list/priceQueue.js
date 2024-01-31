define(['jquery'],
    function($) {
        'use strict';
        return  {
            elementsCount: 0,
            elementsWaitingForPrice: [],
            addProductToQueue: function (productId, element) {
                this.elementsWaitingForPrice[productId] = element;
                this.elementsCount++;
                $(document).trigger('priceApi:productAddedToQueue', {
                    productId
                })
            },
            removeProductFromQueue: function (productId) {
                if (this.elementsWaitingForPrice[productId]) {
                    delete this.elementsWaitingForPrice[productId];
                }
            }
        }
    });
