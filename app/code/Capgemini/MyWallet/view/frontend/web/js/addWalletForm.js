define(
    [
        'jquery',
        'underscore',
        'domReady!',
        'loader',
        'mage/validation'
    ],
    function ($, _) {
        "use strict";

        return function (config) {
            var options =  {
                'CardNumber' : '#cc_number',
                'CardType' : '#cc_type',
                'NickName' : '#cc_nickname',
                'CardHolderName' : '#cc_holder_name',
                'ExpMonth': '#cc_expiration_month',
                'ExpYear' : '#cc_expiration_year',
                'CvvCode' : '#cc_cvv',
                'IsDefaultCard': "#is_default",
                'BillingAddressId': '#billing_address_id'
            };
            $('#loader').loader({
                icon: config.loaderUrl
            });
            $('#loader').loader("hide");

            $('#create_card').on('click', function () {
                var ccNumber = $(options.CardNumber).val();
                if (ccNumber.length > 0) {
                    var firstDigitOfCcNumber = ccNumber[0];
                    switch (firstDigitOfCcNumber) {
                        case '3':
                            var cadType = 'AE';
                            break;
                        case '4':
                            var cadType = 'VI';
                            break;
                        case '5':
                            var cadType = 'MC';
                            break;
                        case '6':
                            var cadType = 'DI';
                            break;
                        default:
                            var cadType = 'Other';
                    }

                    $(options.CardType).val(cadType);
                }

                var dataForm =  $('#wallet_form');
                var valResult  = dataForm.valid();
                if (!valResult) {
                    return;
                }
                $('#loader').loader({
                    icon: config.loaderUrl
                });
                $('#loader').loader("show");

                var addWalletUrl = config.addWalletUrl;

                var postData = {};
                postData['cc_holder_name'] =  $(options.CardHolderName).val();
                postData['cc_nickname'] =  $(options.NickName).val();
                postData['cc_number'] =  $(options.CardNumber).val();
                postData['cc_type'] =  $(options.CardType).val();
                postData['cc_expiration_month'] =  $(options.ExpMonth).val();
                postData['cc_expiration_year'] =  $(options.ExpYear).val();
                postData['cc_cvv'] =  $(options.CvvCode).val();
                postData['billing_address_id'] =  $(options.CvvCode).val();
                postData['billing_address_id'] =  $(options.BillingAddressId).val();
                if ( $(options.IsDefaultCard).prop('checked')) {
                    postData['is_default'] =  true;
                } else {
                    postData['is_default'] =  false;
                }

                $.ajax({
                    url: addWalletUrl,
                    type: "POST",
                    dataType: 'json',
                    data: JSON.stringify(postData),
                    contentType: "application/json",
                    success: function (response) {
                        if (response.error_message !== null && response.error_message !== undefined){
                            $('#loader').loader("hide");
                            alert(response.error_message);
                        }
                        if (response.success !== null && response.success !== undefined){
                            window.location = response.wallet_list_url;
                        }

                    }
                });

            });

            $('#cancel_card').on('click', function () {
                $(options.CardHolderName).val('');
                $(options.CardNumber).val('');
                $(options.NickName).val('');
                $(options.CvvCode).val('');
                $(options.ExpMonth).val('Month');
                $(options.ExpYear).val('Year');
                $(options.CvvCode).val('');
                $(options.IsDefaultCard).prop('checked', false);
            });
        };
    }
);
