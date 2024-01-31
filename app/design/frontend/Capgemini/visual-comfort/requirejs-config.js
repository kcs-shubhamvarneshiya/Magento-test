var config = {

    deps: [
        "js/visualcomfort"
    ],
    config: {
        mixins: {
            'Magento_Tax/js/view/checkout/summary/shipping': {
                'Magento_Tax/js/view/checkout/summary/shipping-mixin': true
            },
            'Magento_Checkout/js/view/shipping': {
                'Magento_Checkout/js/mixin/shipping-mixin': true
            },
            'Magento_Catalog/js/product/list/toolbar': {
                'Magento_Catalog/js/product/list/toolbar-mixin': true
            },
            'Amasty_Shopby/js/amShopbyAjax': {
                'Amasty_Shopby/js/amShopbyAjax-mixin': true
	        },
            'Magento_Ui/js/view/messages': {
                'Magento_Ui/js/view/messages-mixin': true
            },
            'mage/collapsible': {
                'js/mage/collapsible-mixin': true
            },
            'Magento_Customer/js/address': {
                'Magento_Customer/js/address-mixin': true
            },
            'Magento_MultipleWishlist/js/multiple-wishlist': {
                'Magento_MultipleWishlist/js/mixin/multiple-wishlist-mixin': true
            }
        }
    },
    paths: {
        'owlcarousel': 'js/owl_carousel/owl.carousel.min',
        'select2': 'js/select2.min',
    },
    shim: {
        'owlcarousel': {
            deps: ['jquery']
        },
        'select2': {
            deps: ['jquery']
        },
        'jquery.lazy': {
            deps: ['jquery']
        }
    }
}
