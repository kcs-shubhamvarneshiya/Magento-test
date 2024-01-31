
define(
    [
        'Magento_Payment/js/view/payment/cc-form',
        'jquery',
        'underscore',
        'Magento_Payment/js/model/credit-card-validation/credit-card-data',
        'Magento_Payment/js/model/credit-card-validation/credit-card-number-validator',
        'Magento_Checkout/js/action/redirect-on-success',
        'Magento_Payment/js/model/credit-card-validation/cvv-validator',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Magento_Checkout/js/action/place-order',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Magento_Payment/js/model/credit-card-validation/validator',
        'Capgemini_Payfabric/js/cleave/cleave',
    ],
    function (Component, $, _, creditCardData, cardNumberValidator, redirectOnSuccessAction, cvvValidator, additionalValidators) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Capgemini_Payfabric/payment/cc-form',
                nameOnCard: '',
                saveCreditCard: ''
            },
            creditCardFields :{
                walletId: '#wallet_id',
                cvvCode:  '#payfabric_cc_cid',
                ccNumber: '#payfabric_cc_number',
                fieldSet: '#payment_form_payfabric',
                storedCredit: '#stored_credit_cards'
            },

            isCreditCardFromWallet : false,

            walletCreditCard: {
                creditCardNumber: '',
                cvvCode: '',
                walletId: ''
            },

            getCode: function() {
                return 'payfabric';
            },

            isActive: function() {
                return true;
            },

            validate: function() {
                var $form = $('#' + this.getCode() + '-form');
                return $form.validation() && $form.validation('isValid');
            },

            /** @inheritdoc */
            initObservable: function () {
                this._super()
                    .observe([
                        'creditCardType',
                        'creditCardExpYear',
                        'creditCardExpMonth',
                        'creditCardNumber',
                        'creditCardVerificationNumber',
                        'creditCardSsStartMonth',
                        'creditCardSsStartYear',
                        'creditCardSsIssue',
                        'selectedCardType',
                        'nameOnCard',
                        'saveCreditCard'
                    ]);

                return this;
            },

            /**
             * Init component
             */
            initialize: function () {
                var self = this;

                this._super();

                //Set credit card number to credit card data object
                this.creditCardNumber.subscribe(function (value) {
                    var result;

                    self.selectedCardType(null);

                    if (value === '' || value === null) {
                        return false;
                    }
                    result = cardNumberValidator(value);

                    if (!result.isPotentiallyValid && !result.isValid) {
                        return false;
                    }

                    if (result.card !== null) {
                        self.selectedCardType(result.card.type);
                        creditCardData.creditCard = result.card;
                    }

                    if (result.isValid) {
                        creditCardData.creditCardNumber = value;
                        self.creditCardType(result.card.type);
                    }
                });

                //Set expiration year to credit card data object
                this.creditCardExpYear.subscribe(function (value) {
                    creditCardData.expirationYear = value;
                });

                //Set expiration month to credit card data object
                this.creditCardExpMonth.subscribe(function (value) {
                    creditCardData.expirationMonth = value;
                });

                //Set cvv code to credit card data object
                this.creditCardVerificationNumber.subscribe(function (value) {
                    creditCardData.cvvCode = value;
                });

                //Set name on card to credit card data object
                this.nameOnCard.subscribe(function (value) {
                    creditCardData.nameOnCard = value;
                });

                this.saveCreditCard.subscribe(function (value) {
                    creditCardData.saveCreditCard = value;
                });

            },

            /**
             * Get data
             * @returns {Object}
             */
            getData: function () {
                if (this.isCreditCardFromWallet) {
                    return {
                        'method': this.item.method,
                        'additional_data': {
                            'cc_cid': this.walletCreditCard.cvvCode,
                            'cc_ss_start_month': '',
                            'cc_ss_start_year': '',
                            'cc_ss_issue': '',
                            'cc_type': '',
                            'cc_exp_year': '',
                            'cc_exp_month': '',
                            'cc_number': '',
                            'name_on_card': '',
                            'save_credit_card': '',
                            'wallet_id': this.walletCreditCard.walletId
                        }
                    }
                } else {
                    return {
                        'method': this.item.method,
                        'additional_data': {
                            'cc_cid': this.creditCardVerificationNumber(),
                            'cc_ss_start_month': this.creditCardSsStartMonth(),
                            'cc_ss_start_year': this.creditCardSsStartYear(),
                            'cc_ss_issue': this.creditCardSsIssue(),
                            'cc_type': this.creditCardType(),
                            'cc_exp_year': this.creditCardExpYear(),
                            'cc_exp_month': this.creditCardExpMonth(),
                            'cc_number': this.creditCardNumber(),
                            'name_on_card': this.nameOnCard(),
                            'save_credit_card': this.saveCreditCard()
                        }
                    };
                }
            },

            isCustomerLoggedIn: function () {
                return window.checkoutConfig.isCustomerLoggedIn;
            },

            getStoredCreditCards: function() {
                var customerCreditCards = window.checkoutConfig.customerCreditCards;
                if (customerCreditCards !== null && customerCreditCards !== undefined){
                    return customerCreditCards;
                } else {
                    return false;
                }
            },

            initFields: function () {
                var ccNumber = $('#payfabric_cc_number');
                var cleaveCreditCard = new Cleave(ccNumber, {
                    creditCard: true
                });
            },

            initListeners: function () {
                var self = this;

                $("#add-new-card").on('click', function (e) {
                    $(self.creditCardFields.walletId).val(null);
		    $(self.creditCardFields.storedCredit).hide();
                    $(self.creditCardFields.fieldSet).show();

                });

                $("a[id^='use-this-card-']").on('click', function (e) {
                    var walletId = this.id;
                    walletId = walletId.substr(14);
                    $("div[id^='cvv_']").hide();
                    $("a[id^='use-this-card-']").show();
                    $('#use-this-card-' + walletId).hide();
                    $("#cvv_" + walletId).show();
                    $(self.creditCardFields.walletId).val(walletId);
                });
                $("a[id^='use-this-card-']").each(function() {
                    $(this).click(function(e) {
                        var wrap = $(this).parents('.cc__wrapper');
                        wrap.addClass('highlight');
                        $('.cc__wrapper').not($(this).parents('.cc__wrapper')).removeClass('highlight');
                    })
                })
            },

            getCardDataByWalletId: function(walletId){
                var neededCard = {};
                _.each(this.getStoredCreditCards(), function (card) {
                    if (card.wallet_id == walletId) {
                        neededCard = card;
                    }
                });

                return neededCard;
            },

            /**
             * Place order.
             */
            placeOrder: function (data, event) {
                var self = this;

                if (event) {
                    event.preventDefault();
                }
                var walletId = $(self.creditCardFields.walletId).val();
                if (walletId !== null && walletId !== undefined && walletId.length > 0) {
                    var storedCreditCardCvv = $('#cvv_input_' + walletId).val();
                    if (storedCreditCardCvv == null || storedCreditCardCvv == undefined || storedCreditCardCvv.length == 0) {
                        alert('Please enter CVV code');
                        return false;
                    }

                    if (!cvvValidator(storedCreditCardCvv, 3).isValid && !cvvValidator(storedCreditCardCvv, 4).isValid) {
                        alert('Please enter valid CVV code');
                        return false;
                    }
                    $(this.creditCardFields.cvvCode).val(storedCreditCardCvv);
                    var cardData = this.getCardDataByWalletId(walletId);

                    this.isCreditCardFromWallet = true;
                    this.walletCreditCard.creditCardNumber = cardData.cc_last4;
                    this.walletCreditCard.cvvCode = storedCreditCardCvv;
                    this.walletCreditCard.walletId = walletId;

                    this.isPlaceOrderActionAllowed(false);
                    this.getPlaceOrderDeferredObject()
                        .done(
                            function () {
                                self.isCreditCardFromWallet = false;
                                self.afterPlaceOrder();

                                if (self.redirectAfterPlaceOrder) {
                                    redirectOnSuccessAction.execute();
                                }
                            }
                        ).always(
                        function () {
                            self.isCreditCardFromWallet = false;
                            self.isPlaceOrderActionAllowed(true);
                        }
                    );

                    return true;
                } else {
                   var nameOnCard = $("#payfabric_name_on_card").val();
                   if (nameOnCard.length == 0) {
                       alert("Please chose saved card from the list or add new one ");
                       return;
                   }

                    if (this.validate() &&
                        additionalValidators.validate() &&
                        this.isPlaceOrderActionAllowed() === true
                    ) {
                        this.isPlaceOrderActionAllowed(false);

                        this.getPlaceOrderDeferredObject()
                            .done(
                                function () {
                                    self.afterPlaceOrder();

                                    if (self.redirectAfterPlaceOrder) {
                                        redirectOnSuccessAction.execute();
                                    }
                                }
                            ).always(
                            function () {
                                self.isPlaceOrderActionAllowed(true);
                            }
                        );

                        return true;
                    }
                }

                return false;
            }
        });
    }
);
