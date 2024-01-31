define([
        "jquery",
        "Magento_Ui/js/modal/modal",
        "mage/translate"
    ], function($, modal) {
        $.widget('capgemini.pdfbutton', {
            addToCartForm: "#product_addtocart_form",
            _create: function() {
                this._initElement();
            },
            _initElement: function() {
                var self = this;
                var options = {
                    type: 'popup',
                    title: 'Print As PDF',
                    responsive: true,
                    innerScroll: true,
                    modalClass: 'pdf-modal-container',
                    modalVisibleClass: '_show product-pdf--modal',
                    buttons: [],
                    printWithoutPricingButton: {
                        text: $.mage.__('Print without pricing'),
                        class: 'btn btn-secondary without-pricing',
                        click: function () {
                            self.printWithoutPricing();
                        }
                    },
                    printWithPricingButton: {
                        text: $.mage.__('Print with pricing'),
                        class: 'btn-primary with-pricing',
                        click: function () {
                            self.printWithRetailPricing();
                        }
                    },
                    printWithRetailPricingButton: {
                        text: $.mage.__('Print with retail pricing'),
                        class: 'btn-primary with-pricing-retail',
                        click: function () {
                            self.printWithRetailPricing();
                        }
                    },
                    printWithTradePricingButton: {
                        text: $.mage.__('Print with trade pricing'),
                        class: 'btn-primary with-pricing-trade',
                        click: function () {
                            self.printWithTradePricing();
                        }
                    }
                };

                if (this.options.isCompanyPrice === 'true') {
                    options.buttons.push(options.printWithRetailPricingButton);
                    options.buttons.push(options.printWithTradePricingButton);
                } else {
                    options.buttons.push(options.printWithPricingButton);
                }
                options.buttons.push(options.printWithoutPricingButton);

                modal(options, $('#pdf-modal-wrap'));

                $("#modal-btn").on('click', function () {
                    if (this.validate()) {
                        $("#pdf-modal-wrap").modal("openModal");
                    }
                }.bind(this));
            },

            printWithRetailPricing: function() {
                if (this.getProductId) {
                    return window.location=this.getUrl(this.getProductId());
                }
            },
            printWithoutPricing: function() {
                if (this.getProductId) {
                    return window.location=this.getUrl(this.getProductId(), 'pricing=1');
                }
            },
            printWithTradePricing: function() {
                if (this.getProductId) {
                    return window.location=this.getUrl(this.getProductId(), 'pricing=2');
                }
            },
            getUrl: function(productId, optionsParams) {
                let url = window.location.protocol + window.location.hostname + '/pdf/product/view/id/' + productId;
                if (optionsParams) {
                    url = url + '?' + optionsParams;
                }
                return url;
            },
            /**
             * Validate all required option are selected
             * @returns {boolean}
             */
            validate() {
                if ($('#product_addtocart_form').validation('isValid')) {
                    return true;
                } else {
                    var invalidModal = {
                        type: 'popup',
                        title: 'Print As PDF',
                        responsive: true,
                        buttons: [],
                        modalVisibleClass: '_show product-pdf--modal'
                    };

                    modal(invalidModal, $('#validation-modal-wrap'));
                    $("#validation-modal-wrap").modal("openModal");
                    return false;
                }
            },
            getProductId: function() {
                let addToCartForm = $(this.addToCartForm);
                let product = addToCartForm.find('input[name="product"]').val();
                let optionProduct  = addToCartForm.find('input[name="selected_configurable_option"]').val();

                if (optionProduct && optionProduct !== 'undefined') {
                    return optionProduct;
                }
                if (product && product !== 'undefined') {
                    return product;
                }

                return null;
            }
        });
        return $.capgemini.pdfbutton;
    }
);
