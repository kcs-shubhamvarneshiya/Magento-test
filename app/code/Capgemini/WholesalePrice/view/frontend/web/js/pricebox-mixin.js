define(['jquery', 'Magento_Catalog/js/price-utils', 'apiPrice'],
    function ($, utils, apiPrice)
    {
        return function (widget) {
            $.widget('mage.priceBox', widget, {
                _init: function initPriceBox() {
                    let priceConfig = this.options.priceConfig['advancedPriceConfig'];
                    if (priceConfig && priceConfig[this.options.productId]) {
                        let prices = apiPrice.getPrices(this.options.productId);
                        if (prices) {
                            this.setDefault(prices);
                        }
                    }
                    this._super();
                },
            });
            return $.mage.priceBox;
        }
    });
