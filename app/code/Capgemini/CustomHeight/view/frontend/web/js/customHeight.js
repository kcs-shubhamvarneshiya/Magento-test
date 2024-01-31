define([
    'jquery',
    'underscore',
    'Magento_Catalog/js/price-options',
    'domReady'
], function (
    $,
    _,
    priceOptions
) {

    $.widget('capgemini.custom_height', {
        options: {
            note:'#custom_height_note',
            availabilityMessage: '#availability_message'
        },
        _create: function () {
            var self = this;
            $('.custom_height_wrapper button[type="button"]').click(function(){
                var $this = $(this),
                    $select = $this.closest('.select-btn').find('select'),
                    $op = $select.find('option:selected');
                if($op.length){
                    if ($this.val() == 'Up') {
                        $op.prev().prop("selected",true).trigger('change');
                    } else {
                        $op.next().prop("selected",true).trigger('change');
                    }
                } else {
                    $select.val($select.find('option:first')).trigger('change');
                }
            });

            $('#custom_height').on('change', function(event) {
                var customHeightPrice = parseFloat(this.value);
                var newPrices = {
                    customHeight: {
                        basePrice: {
                            amount: customHeightPrice
                        },
                        finalPrice: {
                            amount: customHeightPrice
                        },
                        oldPrice: {
                            amount: customHeightPrice,
                            adjustments: []
                        }

                    }
                }
                $(priceOptions().options.priceHolderSelector).trigger('updatePrice', newPrices);
                var heightValue = $(this).find(':selected').data('height-value');
                $('#custom_height_value').val(heightValue);
                $('#custom_height_price').val(customHeightPrice);
                if (customHeightPrice == 0) {
                    $(self.options.note).hide();
                    $(self.options.availabilityMessage).hide();
                    return;
                }
                if (!_.isEmpty(self.options.staticNoteText)){
                    $(self.options.note).text(self.options.staticNoteText);
                    $(self.options.note).show();
                }
                if (!_.isEmpty(self.options.availabilityMessageText)){
                    $(self.options.availabilityMessage).html('<span>' + self.options.availabilityMessageText + '</span>');
                    $(self.options.availabilityMessage).show();
                }
            });

            $('.variation-item a').click(function(evt) {
                if (this.dataset && this.dataset.hasOwnProperty('productSku')) {
                    var childProductSku = this.dataset.productSku;
                } else {
                    return;
                }
                if (self.options.heightPricingForAllProducts.hasOwnProperty(childProductSku)){
                    var selectedCustomHeightValue = $('#custom_height_value').val();
                    $('#custom_height option').remove();
                    $('#custom_height_modal option').remove();
                    $('#custom_height_value').val(0);
                    $('#custom_height_price').val(0);
                    var newOptions = self.options.heightPricingForAllProducts[childProductSku];
                    for (var property in newOptions) {
                        if (parseInt(newOptions[property].price) == 0 && selectedCustomHeightValue == 0) {
                            var option = "<option selected value=" + newOptions[property].price
                                + " data-height-value=" + newOptions[property].height + ">" + newOptions[property].label + "</option>";
                        } else  {
                            if (selectedCustomHeightValue == parseInt(newOptions[property].height)) {
                                var option = "<option selected value=" + newOptions[property].price
                                    + " data-height-value=" + newOptions[property].height + ">" + newOptions[property].label + "</option>";
                                $('#custom_height_value').val(newOptions[property].height);
                                $('#custom_height_price').val(newOptions[property].price);
                            } else {
                                var option = "<option value=" + newOptions[property].price
                                    + " data-height-value=" + newOptions[property].height + ">" + newOptions[property].label + "</option>";
                            }
                        }
                        $('#custom_height').append(option);
                        $('#custom_height_modal').append(option);

                    }
                }
            });

        }

    });
    return $.capgemini.custom_height;
});
