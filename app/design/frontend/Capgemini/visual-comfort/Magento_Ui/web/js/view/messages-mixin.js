define([
    'jquery'
], function ($) {
    'use strict';

    var mixin = {
        /**
         * @param {Boolean} isHidden
         */
        onHiddenChange: function (isHidden) {
            var self = this;

            // Hide message block if needed
            if (isHidden) {
                setTimeout(function () {
                    $(self.selector).hide('blind', {}, 500);
                }, 300000);
            }
        },
    };

    return function (target) {
        return target.extend(mixin);
    };
});
