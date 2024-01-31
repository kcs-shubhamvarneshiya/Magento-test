define(['jquery', 'Capgemini_ExponeaSubscription/js/view/message'], function ($, message) {
    function process(dtObj) {
        let self = this;
        if (dtObj.confirmationCheckbox && dtObj.confirmationCheckbox.length === 1) {
            if (!dtObj.confirmationCheckbox.is(":checked")) {
                return dtObj.sendToServer;
            }
        }
        if (!dtObj.form.valid()) {
            return false;
        }
        var emailAddress = $.trim(dtObj.emailInput.val());
        exponea.identify({
                "email_id": emailAddress
            },{ "email": emailAddress},
            function(response) {
                exponea.track(
                    "consent",
                    {
                        category: "email",
                        import_source: dtObj.importSource + ' - ' + dtObj.storeCode + ' Site',
                        action: "accept",
                        valid_until: "unlimited"
                    },
                    function (response) {
                        console.log(response)
                        if (typeof dtObj.successMessage !== "undefined") {
                            message.process({status: 'success', message: dtObj.successMessage});
                        }
                    },
                    function (response) {
                        console.log('error while tracking consent: ' + response);
                        if (typeof dtObj.errorMessageTrack !== "undefined") {
                            message.process({status: 'error', message: dtObj.errorMessageTrack});
                        }
                    }
                );
            },
            function(response) {
                console.log('error while identifying customer: ' + response);
                if (typeof dtObj.errorMessageIdentify !== "undefined") {
                    message.process({status: 'error', message: dtObj.errorMessageIdentify});
                }
            },
            false
        );

        return dtObj.sendToServer;
    }

    return {process: process}
})
