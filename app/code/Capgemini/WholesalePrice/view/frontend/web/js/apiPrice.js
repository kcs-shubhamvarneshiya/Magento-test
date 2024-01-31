define(['jquery', 'underscore', 'Magento_Customer/js/customer-data'],
    function($, _, customerData) {
        'use strict';
        return  {
            cachedPrice: {},
            prices: null,
            hasValidPrices: null,
            storedPrices: {},
            initPricesHtmlMap: function (products) {
                this.fetchProductsPriceHtml(products);
            },
            getPrices: function (productId) {
                var $this = this;
                if (this.prices) {
                    if(!_.isUndefined(this.prices.product)
                    && this.prices.product.id == productId){
                        return this.prices;
                    } else {
                        $.each(this.cachedPrice, function(products, apiAmount){
                            if(productId == products){
                                $this.prices =  {
                                    finalPrice: {
                                        amount: apiAmount
                                    },
                                    basePrice: {
                                        amount: apiAmount
                                    },
                                    oldPrice: {
                                        amount: apiAmount
                                    }
                                }
                            } 
                        });
                    }
                } else {
                    this.storedPrices = this.getPrice(productId);
                    if (!_.isEmpty(this.cachedPrice)) {
                        this.hasValidPrices = true;
                        $.each(this.cachedPrice, function(products, apiAmount){
                            if(productId == products){
                                $this.prices =  {
                                    finalPrice: {
                                        amount: apiAmount
                                    },
                                    basePrice: {
                                        amount: apiAmount
                                    },
                                    oldPrice: {
                                        amount: apiAmount
                                    }
                                }
                            } 
                        });
                    }
                }
                return this.prices;
            },
            getPrice: function (productId) {
                var apiAmount = 0;
                let customer = customerData.get('customer')();
                if (!customer || customer.companyType !== 'wholesale') {
                    return apiAmount;
                }
                if (this.cachedPrice.hasOwnProperty(productId)) {
                    return this.cachedPrice[productId];
                } else {
                    $.ajax({
                        url: '/wprice/price/get',
                        data: {
                            "id": productId
                        },
                        type: 'post',
                        dataType: 'json',
                        cache: false,
                        async: false,
                    }).done(function (result) {
                        if (result && result.amount) {
                            apiAmount = result.amount;
                        }
                    })
                    // this.cachedPrice[productId] = apiAmount;
                    this.cachedPrice = apiAmount;
                    return apiAmount;
                }
            },
            getProductPriceHtml: function (id) {
                return this.fetchProductPriceHtml(id);
            },
            fetchProductPriceHtml(productId) {
                return this._callHtmlPriceApi(
                    {
                        "id": productId
                    }
                )
            },
            fetchProductsPriceHtml(products) {
                return this._callHtmlPriceApi(
                    {
                        "ids": products
                    }
                )
            },
            _callHtmlPriceApi: function (params, shouldTriggerLoaded = true) {
                return $.ajax({
                    url: '/wprice/price/priceblock',
                    data: params,
                    type: 'post',
                    dataType: 'json',
                    cache: true,
                    async: true,
                }).done(function(result){
                    if (shouldTriggerLoaded === true) {
                        $(document).trigger(
                            'priceApi:pricelistLoaded', {
                                result
                            }
                        );
                    }
                    return result;
                })
            },
        }
    });
