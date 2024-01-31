var config = {
    "config": {
        "mixins": {
            "Magento_Swatches/js/swatch-renderer" : {
                'Lyonscg_ConfigurableSimple/js/swatch-renderer-mixin' : true
            }
        }
    },
    paths: {
        'owlcarousel': 'Lyonscg_ConfigurableSimple/js/owl_carousel/owl.carousel.min'
    },
    shim: {
        'owlcarousel': {
            deps: ['jquery']
        }
    }
};
