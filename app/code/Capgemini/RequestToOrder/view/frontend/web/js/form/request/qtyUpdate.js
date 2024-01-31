define([
    'jquery',
    'mage/url',
    'jquery-ui-modules/widget',
], function ($, urlBuilder) {
    'use strict';

    $.widget('cp.updateQty', {
        options: {
            validateUrl: urlBuilder.build('/orequest/item/updateQty'),
        },

        /**
         * @private
         */
        _create: function () {
            this._bind();
            this._initSubmitHandler();
        },

        /**
         * Bind observers
         *
         * @private
         */
        _bind: function () {
            this._on({
                click:  this._updateQty.bind(this)
            });
        },

        _initSubmitHandler: function () {
            let that = this;
            let form = $("#request-add-form");
            form.on('submit', $.proxy(function (e) {
                that._updateQty();
            }, this));
        },

        /**
         * Validate input field
         *
         * @private
         */
        _updateQty: function () {
            let that = this;
            $("input[name='qty']").each(function() {
                 $(".mage-error").hide();
                console.log($(this).data('itemid'));
                var itemId = $(this).data('itemid'),
                    qty = $(this).val();
                    if((qty<=0) || (qty=='')) {
                    var inputElement = document.getElementById("desk-prod-"+itemId);
                    var spanElement = document.createElement("div");
                    spanElement.className = 'mage-error'
                    spanElement.innerHTML = "Please enter a quantity greater than 0.";
                    // Append the <span> element after the input element
                    inputElement.parentNode.insertBefore(spanElement, inputElement.nextSibling);
                    return false;

                } else {
                 $(".mage-error").hide();
                return $.ajax({
                    url: that.options.validateUrl,
                    data: {
                        "item_id": itemId,
                        "qty": qty
                    },
                    success: function (data){
                        $('#order-request-'+itemId+" span").html(data.price);
                    },
                    type: 'post',
                    dataType: 'json',
                    showLoader: true
                });
              }
            });

        },

    });

    return $.cp.updateQty
});
