var config = {
    config: {
        mixins: {
            'Magento_Catalog/js/price-box': {
                'Capgemini_WholesalePrice/js/pricebox-mixin': true
            },
            'Magento_ConfigurableProduct/js/configurable': {
                'Capgemini_WholesalePrice/js/configurable-mixin': true
            }
        }
    },
    map: {
        '*': {
            apiPrice: 'Capgemini_WholesalePrice/js/apiPrice',
            priceList: 'Capgemini_WholesalePrice/js/view/priceList',
            priceQueue: 'Capgemini_WholesalePrice/js/model/product/list/priceQueue'
        }
    }
};
