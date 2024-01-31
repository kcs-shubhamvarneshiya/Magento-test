var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/view/form/element/email': {
                'Capgemini_ExponeaSubscription/js/view/form/element/email-mixin' : true
            },
            'Magento_Checkout/js/model/shipping-save-processor': {
                'Capgemini_ExponeaSubscription/js/model/shipping-save-processor-mixin': true
            }
        }
    }
};
