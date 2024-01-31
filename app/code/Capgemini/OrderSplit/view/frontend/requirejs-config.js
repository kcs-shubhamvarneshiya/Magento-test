var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/place-order': {
                'Capgemini_OrderSplit/js/action/place-order-mixin': true
            },
            'mage/validation': {
                'Capgemini_OrderSplit/js/view/validation-mixin': true
            }
        }
    }
};
