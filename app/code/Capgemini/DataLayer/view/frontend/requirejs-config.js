var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/view/form/element/email': {
                'Capgemini_DataLayer/js/view/form/element/email-mixin' : true
            },
            'Magento_Checkout/js/sidebar': {
                'Capgemini_DataLayer/js/sidebar-mixin' : true
            },
            'Magento_MultipleWishlist/js/multiple-wishlist': {
                'Capgemini_DataLayer/js/multiple-wishlist-mixin' : true
            },
            'Magento_Wishlist/js/wishlist': {
                'Capgemini_DataLayer/js/wishlist-mixin' : true
            }
        }
    },
    map: {
        '*': {
            md5: 'Capgemini_DataLayer/js/md5'
        }
    }
};
