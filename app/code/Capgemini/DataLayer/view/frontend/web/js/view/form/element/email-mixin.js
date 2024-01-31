define(function () {
    'use strict';

    var mixin = {

        emailHasChanged: function() {
            this._super();
            this.emailToDataLayer(this.email());
        },

        emailToDataLayer: function (email) {
            if (window.dataLayer) {
                let existing = false;
                window.dataLayer.forEach(function (value, index) {
                    if (typeof value.otherPageData !== 'undefined') {
                        existing = index;
                    }
                });
                var toPush = {
                    otherPageData: {
                        userEmail: email
                    }
                };
                if (existing !== false) {
                    window.dataLayer[existing] = toPush;
                } else {
                    window.dataLayer.push(toPush);
                }
            }
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
