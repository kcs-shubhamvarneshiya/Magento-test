var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/view/cart-item-renderer': {
                'Capgemini_CustomHeight/js/view/custom-height-availability-message-mixin' : true
            },
            'Magento_Checkout/js/view/summary/item/details': {
                'Capgemini_CustomHeight/js/view/custom-height-availability-message-mixin' : true
            }
        }
    }
};
