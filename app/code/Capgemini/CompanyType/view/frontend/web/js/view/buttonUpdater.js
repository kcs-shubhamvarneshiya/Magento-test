define([
    'jquery',
    'underscore',
    'Magento_Customer/js/customer-data',
    'mage/url'
], function ($, _, customerData, urlBuilder) {
    'use strict';

    function validationCallback(customer, productId) {
        if (customer && customer.companyType === 'wholesale') {
            if (this.validationCache.hasOwnProperty(productId)) {
                if (this.validationCache[productId] === false) {
                    this._hideButton();
                } else {
                    this._showButton();
                }
            } else {
                //hide button before passing validation
                this._hideButton();
                $.ajax({
                    url: urlBuilder.build('cpcom/product/validate'),
                    data: {
                        "id": productId
                    },
                    type: 'post',
                    dataType: 'json',
                    cache: false,
                    context: this
                }).done(function (result) {
                    if (result && result.hasOwnProperty('success')) {
                        if (result.success === false) {
                            this.validationCache[productId] = false;
                            this._hideButton();
                        } else {
                            this.validationCache[productId] = true;
                            this._showButton();
                        }
                    }
                })
            }
        } else {
            this._showButton();
        }
    }

    $.widget('cp.addToButtonUpdater', {
        options: {
            buttonElement: '#product_addtocart_form .box-tocart',
            purchase_limit: {}
        },
        validationCache: {},
        _create() {
            this._initSwitchHandler();
        },
        _initSwitchHandler: function () {
            let that = this;
            let productId = $('#product_addtocart_form [name="product"]').val();
            if (productId) {
                that._validateProduct(productId);
            }
            $(document).on("currentProductId", function(event, data) {
                that._validateProduct(data.product_id);
            });
        },
        _validateProduct: function (productId) {
            let customerObservable = customerData.get('customer'),
                customer = customerObservable(),
                callback = validationCallback.bind(this);
            if (!customer || customer.companyType === undefined) {
                customerObservable.subscribe(customer => {callback(customer, productId)})
            } else {
                callback(customer, productId);
            }
        },
        _hideButton: function () {
            $(this.options.buttonElement).hide();
        },
        _showButton: function () {
            $(this.options.buttonElement).show();
        }
    });

    return $.cp.addToButtonUpdater;
});
