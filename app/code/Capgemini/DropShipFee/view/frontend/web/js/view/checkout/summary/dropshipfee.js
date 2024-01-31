define([
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils',
        'Magento_Checkout/js/model/totals'
    ], function (Component, quote, priceUtils, totals) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Capgemini_DropShipFee/checkout/summary/dropshipfee'
            },
            totals: quote.getTotals(),
            isDisplayed: function() {
                return totals.getSegment('drop_ship_fee').value !== 'undefined'
                    && totals.getSegment('drop_ship_fee').value !== null
                    && totals.getSegment('drop_ship_fee').value !== 0;
            },
            getValue: function() {
                var price = 0;
                if (this.totals()) {
                    price = totals.getSegment('drop_ship_fee').value;
                }
                return this.getFormattedPrice(price);
            },
            getBaseValue: function() {
                var price = 0;
                if (this.totals()) {
                    price = this.totals().base_drop_ship_fee;
                }
                return priceUtils.formatPrice(price, quote.getBasePriceFormat());
            }
        });
    }
);
