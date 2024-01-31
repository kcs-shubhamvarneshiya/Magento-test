define([
    'jquery'
], function ($) {
    'use strict';

    $.widget('capgemini.paperModelPreview',{

        options: {
            elementSelector: '#paper-model-preview-button'
        },

        _create: function() {
            $(this.options.elementSelector).attr('href', this.options.paperModelPreviewLink);
        }

    });

    return $.capgemini.paperModelPreview;
});
