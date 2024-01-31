define([
    'jquery'
], function ($) {
    'use strict';

    $.widget('capgemini.paperModelPreview',{

        options: {
            elementSelector: '#paper-model-preview-button'
        },

        _create: function() {
            let that = this;
            $(this.options.elementSelector).click(function(e) {
                e.preventDefault();
                window.open(that.options.paperModelPreviewLink, "_blank");
            });
        }

    });

    return $.capgemini.paperModelPreview;
});
