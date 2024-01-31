define(['jquery', 'Magento_Catalog/js/price-utils', 'apiPrice'],
    function ($, utils, apiPrice)
    {
        return function (widget) {
            $.widget('mage.configurable', widget, {
                _calculatePrice: function (config) {
                    var displayPrices = $(this.options.priceHolderSelector).priceBox('option').prices;
                    var newPrices = this.options.spConfig.optionPrices[_.first(config.allowedProducts)] || {};
                    let priceConfig = this.options.spConfig['advancedPriceConfig'];
                    if (priceConfig && priceConfig[this.simpleProduct]){
                        var remotePrices = apiPrice.getPrices(this.simpleProduct);
                        if (remotePrices) {
                            newPrices = remotePrices;
                        }
                    }
                    _.each(
                        displayPrices, function (price, code) {
                        displayPrices[code].amount = newPrices[code] ? newPrices[code].amount - displayPrices[code].amount : 0;
                    });

                    return displayPrices;
                },
            });
            return $.mage.configurable
        }
    });
