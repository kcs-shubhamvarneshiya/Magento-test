var config = {
    config: {
        mixins: {
            'mage/gallery/gallery': {
                'Rysun_ThreeSixtyImage/wr360hook': true
            },

            'Magento_ConfigurableProduct/js/configurable': {
                'Rysun_ThreeSixtyImage/wr360swatch': true
            }
        },
    },

    map: {
        '*': {
            'imagerotator': 'Rysun_ThreeSixtyImage/imagerotator/html/js/imagerotator'
        }
    }
};

