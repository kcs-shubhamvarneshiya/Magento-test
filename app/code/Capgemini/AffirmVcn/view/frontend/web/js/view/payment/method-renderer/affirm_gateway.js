/**
 * Astound
 * NOTICE OF LICENSE
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to codemaster@astoundcommerce.com so we can send you a copy immediately.
 *
 * @category  Affirm
 * @package   Astound_Affirm
 * @copyright Copyright (c) 2016 Astound, Inc. (http://www.astoundcommerce.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/*browser:true*/
/*global define*/
define(
    [
        'jquery',
        'Astound_Affirm/js/view/payment/method-renderer/affirm_gateway',
        'Magento_Checkout/js/model/quote',
        'Magento_Payment/js/model/credit-card-validation/credit-card-number-validator',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Magento_Checkout/js/model/url-builder',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Ui/js/model/messages',
        'Magento_Checkout/js/action/set-payment-information',
        'Astound_Affirm/js/action/prepare-affirm-checkout',
        'Astound_Affirm/js/action/send-to-affirm-checkout',
        'Astound_Affirm/js/action/verify-affirm',
        'Astound_Affirm/js/action/inline-checkout',
        'Magento_Checkout/js/action/redirect-on-success'
    ],
    function ($, Component, quote, cardNumberValidator, additionalValidators,
              urlBuilder, errorProcessor, Messages, setPaymentAction,
              initChekoutAction, sendToAffirmCheckout, verifyAffirmAction, inlineCheckout,
              redirectOnSuccessAction) {

        'use strict';

        function calculateCcType(ccNumber) {
            return cardNumberValidator(ccNumber).card.title;
        }

        return Component.extend({
            isPayfabric: false,

            /**
             * Init Affirm specify message controller
             */
            initAffirm: function() {
                this.messageContainer = new Messages();
            },

            /**
             * Payment code
             *
             * @returns {string}
             */
            getPayfabricCode: function () {
                return 'affirm_vcn';
            },

            /**
             * Continue to Affirm redirect logic
             */
            continueInAffirm: function() {
                var self = this;
                if (additionalValidators.validate()) {
                    //update payment method information if additional data was changed
                    this.selectPaymentMethod();
                    $.when(setPaymentAction(self.messageContainer, {'method': self.getCode()})).done(function() {
                        $.when(initChekoutAction(self.messageContainer)).done(function(response) {
                            sendToAffirmCheckout(response, self);
                        });
                    }).fail(function(){
                        self.isPlaceOrderActionAllowed(true);
                    });
                    return false;
                }
            },

            /**
             * Place order.
             */
            placeOrderPayfabric: function (affirmData) {
                this.isPlaceOrderActionAllowed(false);
                this.isPayfabric = true;
                this.affirmData = affirmData;
                var self = this;
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
            },

            getData: function () {
                if (this.isPayfabric) {
                    try {
                        return {
                            'method': this.getPayfabricCode(),
                            'additional_data': {
                                'cc_cid': this.affirmData.cvv,
                                'cc_ss_start_month': '',
                                'cc_ss_start_year': '',
                                'cc_ss_issue': '',
                                'cc_type': calculateCcType(this.affirmData.number),
                                'cc_exp_year': this.affirmData.expiration.substring(2),
                                'cc_exp_month': this.affirmData.expiration.substring(0, 2),
                                'cc_number': this.affirmData.number,
                                'name_on_card': this.affirmData.cardholder_name,
                                'save_credit_card': false,
                                'wallet_id': ''
                            }
                        }
                    } catch (e) {
                        // console.log(e.message);
                        return this._super();
                    }
                } else {
                    return this._super();
                }
            },
        });
    }
);
