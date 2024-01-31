define([
    'jquery'
], function($) {
    'use strict';

    var mixin = {
        initUploader: function (fileInput) {
            this._super(fileInput);
        }
    };

    return function (fileUploader) {
        return fileUploader.extend(mixin);
    };
});
