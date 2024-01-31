define([
    'jquery',
    'ko'
], function($, ko) {
    'use strict';

    return function() {
        $.validator.addMethod(
            'required-company-document',
            function(value, element) {
                var elemDocumentsCount = $(element).siblings('[data-documents-count]').get(0);
                if (elemDocumentsCount !== undefined &&  elemDocumentsCount.dataset.documentsCount != 0) {
                    return true;
                }
                return false;
            },
            $.mage.__('This is a required field')
        );
        $.validator.addMethod(
            'company-telephone',
            function(value, element) {
                if (value.match(/^[0-9 \\.\\-]+$/)) {
                    $(element).val(value.replaceAll(/-|\s/g, ""));
                    return true
                }
                return false
            },
            $.mage.__('The allowed delimiter symbols are \'-\',  \' \', \'.\'')
        );
    }
});
