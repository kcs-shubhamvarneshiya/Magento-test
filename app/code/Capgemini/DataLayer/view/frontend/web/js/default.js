define([
    'jquery',
    'Capgemini_DataLayer/js/action/execute-push'
], function ($, executePush) {
    'use strict';

    window.dataLayer = window.dataLayer || [];

    function pushData(data) {
        let existing = false;
        window.dataLayer.forEach(function (value, index) {
            if (typeof value.otherPageData !== 'undefined') {
                existing = index;
            }
        });
        let toPush = {
            otherPageData: {
                userEmail: data.userEmail ? data.userEmail : ''
            }
        };
        if (existing !== false) {
            window.dataLayer[existing] = toPush;
        } else {
            window.dataLayer.push(toPush);
        }
    }

    $.widget('capgemini.datalayer_default', {
        options: {
            controlCookieName: undefined,
            ajaxUrl: undefined
        },
        _create: function () {
            executePush(this.options.controlCookieName, this.options.ajaxUrl, pushData);
        }
    });
    return $.capgemini.datalayer_default;
});
