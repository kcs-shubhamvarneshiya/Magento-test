var config = {

    config: {
        mixins: {
            'mage/validation': {
                'Capgemini_Company/js/validation-mixin' : true
            }
        }
    },

    map: {
        "*": {
            uiFileUpload: 'Magento_Ui/js/form/element/file-uploader'
        }
    }
};
