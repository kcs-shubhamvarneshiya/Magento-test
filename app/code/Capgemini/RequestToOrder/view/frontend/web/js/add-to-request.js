/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mage/translate',
    'underscore',
    'Magento_Ui/js/modal/modal',
    'mage/url',
    'jquery-ui-modules/widget'
], function ($, $t, _, modal, urlBuilder) {
    'use strict';

    $.widget('capgemini.addToRequest', {

        options: {
            modalPopUpSelector: '#add-to-request-state-pop-up',
            addToCartFormSelector: '#product_addtocart_form',
            addToRequestBlockSelector: '#add_to_request_form .box-tocart',
            addToRequestButtonSelector: '.action.torequest',
            addToRequestButtonDisabledClass: 'disabled',
            addToRequestButtonTextWhileAdding: '',
            addToRequestButtonTextAdded: '',
        },

        /** @inheritdoc */
        _create: function () {
            this._validateProduct();
            this._bindSubmit();
            $(this.options.addToRequestButtonSelector).attr('disabled', false);
        },

        _validateProduct: function () {
            let that = this;
            let addToCartForm = $(this.options.addToCartFormSelector);
            let productId = addToCartForm.find('input[name="product"]').val();
            let optionId = addToCartForm.find('input[name="selected_configurable_option"]').val();
            if (optionId) {
                productId = optionId;
            }

            $.ajax({
                url: urlBuilder.build('orequest/item/validate'),
                data:{
                    "id": productId
                },
                type: 'post',
                dataType: 'json',
                cache: false,
                async: false,
            }).done(function(result){
                if (result && result.hasOwnProperty('success')) {
                    if (result.success === false) {
                        $(that.options.addToRequestBlockSelector).hide()
                    } else {
                        $(that.options.addToRequestBlockSelector).show()
                    }
                }
            })
        },

        /**
         * @private
         */
        _bindSubmit: function () {
            var self = this;
            this.element.on('submit', function (e) {
                e.preventDefault();
                self.submitForm($(this));
            });
        },

        /**
         * Handler for the form 'submit' event
         *
         * @param {jQuery} form
         */
        submitForm: function (form) {
            this.ajaxSubmit(form);
        },

        /**
         * @param {jQuery} form
         */
        ajaxSubmit: function (form) {
            var self = this;

            let addToCartForm = $(this.options.addToCartFormSelector);
            let productId = addToCartForm.find('input[name="product"]').val();
            let optionId = addToCartForm.find('input[name="selected_configurable_option"]').val();
            let priceText = $('.price-box.price-final_price .price-wrapper .price').html();
            let price = priceText.replace(/[^0-9.]/g, '');
            if ($('#product_addtocart_form').valid() == false) {
                return false;
            }
            let formData;

            formData = new FormData(form[0]);
            formData.set('product_id', productId);
            formData.set('option_id', optionId);
            formData.set('price', price);

            self.disableAddToRequestButton(form);

            $.ajax({
                url: form.attr('action'),
                data: formData,
                type: 'post',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,

                /** @inheritdoc */
                success: function (res) {
                    console.log(res);
                    let response = res.item;
                    self.showSuccessModal(response);
                },

                /** @inheritdoc */
                error: function (res) {
                    let response = res.responseJSON.message;
                    self.showErrorModal(response);
                },

                complete: function (res) {
                    self.enableAddToRequestButton(form);
                }
            });
        },

        /**
         * @param {String} form
         */
        disableAddToRequestButton: function (form) {
            var addToRequestButtonTextWhileAdding = this.options.addToRequestButtonTextWhileAdding || $t('Adding...'),
                addToRequestButton = $(form).find(this.options.addToRequestButtonSelector);

            addToRequestButton.addClass(this.options.addToRequestButtonDisabledClass);
            addToRequestButton.find('span').text(addToRequestButtonTextWhileAdding);
            addToRequestButton.attr('title', addToRequestButtonTextWhileAdding);
        },

        /**
         * @param {String} form
         */
        enableAddToRequestButton: function (form) {
            var addToRequestButtonTextAdded = this.options.addToRequestButtonTextAdded || $t('Added'),
                self = this,
                addToRequestButton = $(form).find(this.options.addToRequestButtonSelector);

            addToRequestButton.find('span').text(addToRequestButtonTextAdded);
            addToRequestButton.attr('title', addToRequestButtonTextAdded);

            setTimeout(function () {
                var addToRequestButtonTextDefault = self.options.addToRequestButtonTextDefault || $t('Add to Request');

                addToRequestButton.removeClass(self.options.addToRequestButtonDisabledClass);
                addToRequestButton.find('span').text(addToRequestButtonTextDefault);
                addToRequestButton.attr('title', addToRequestButtonTextDefault);
            }, 1000);
        },

        showErrorModal: function (stateText) {
            var self = this;
            var options = {
                type: 'popup',
                responsive: true,
                innerScroll: false,
                title: 'Error',
                buttons: [{
                    text: $.mage.__('Close'),
                    class: '',
                    click: function () {
                        this.closeModal();
                    }
                }]
            };

            $(self.options.modalPopUpSelector).html('<p>' + stateText + '</p>').modal(options).modal('openModal');
        },
        showSuccessModal: function (itemAddedData) {
            var self = this;
            var options = {
                type: 'popup',
                responsive: true,
                innerScroll: false,
                title: 'Item Added To Request To Order',
                modalClass: 'modal-add-to-request-state-pop-up-container',
                buttons: [{
                    text: $.mage.__('Close'),
                    class: '',
                    click: function () {
                        this.closeModal();
                    }
                }]
            };
            // get the price
            var priceText = $('.price-box.price-final_price .price-wrapper .price').html();
            $(self.options.modalPopUpSelector).find('#item-sku-id').html(itemAddedData.sku);
            $(self.options.modalPopUpSelector).find('.name').html(itemAddedData.name);
            $(self.options.modalPopUpSelector).find('.price').html(priceText);
            $(self.options.modalPopUpSelector).find('#product-qty-id').html(itemAddedData.qty);
            $(self.options.modalPopUpSelector).find('#product-availability-id').html(itemAddedData.availability);
            $(self.options.modalPopUpSelector).find('.text').html(itemAddedData.text);
            $(self.options.modalPopUpSelector).find('#product-availability-id').html(itemAddedData.availability);
            $(self.options.modalPopUpSelector).find('img').attr('src', itemAddedData.image);
            $(self.options.modalPopUpSelector).find('#product-finish-id').html(itemAddedData.finish);
            $("#view-request-order-id").find('a').attr('href',urlBuilder.build('/orequest/request/index/id/'+itemAddedData.requestId));

            $(self.options.modalPopUpSelector).modal(options).modal('openModal');
        },
    });

    return $.capgemini.addToRequest;
});
