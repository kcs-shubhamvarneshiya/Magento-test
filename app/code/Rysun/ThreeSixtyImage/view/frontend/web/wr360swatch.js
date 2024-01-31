define([
    'jquery'
], function ($) {
    'use strict';

    return function (SwatchRenderer) {
        $.widget('mage.configurable', $['mage']['configurable'], {
            _create: function () {
                window['__wr360Swatches'] = this.options.spConfig.index;
                this._super();
            },
        });
        return $['mage']['configurable'];
    };
});
