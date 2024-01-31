define([
    'jquery'
], function ($) {
    "use strict";

    return function () {
        var validation = {
            errorMessage: null,
            isValid: true,
            getErrorMessage: function() {
                return this.errorMessage;
            },
            setErrorMessage: function (message) {
              this.errorMessage = message;
            },
            getIsValid: function () {
                return this.isValid;
            },
            setIsValid(isValid) {
              this.isValid = isValid;
            }
        }
        $.validator.addMethod(
            'validate-custom-po-number',
            function (value, element) {
                $.ajax({
                    url: "/ordersplit/ponumber/validate",
                    data: {
                        "po_number": value,
                        "division": element.dataset.division
                    },
                    type: "POST",
                    showLoader: true,
                    async: false,
                    success: function(response) {
                        if (response.errors) {
                            validation.setErrorMessage(response.errors.join(','));
                        }
                        validation.setIsValid(response.result);
                    },
                    error: function() {
                        validation.setIsValid(true);
                    }
                });
                return validation.getIsValid();
            },
            function () {
                return validation.getErrorMessage();
            }
        );
    }
});
