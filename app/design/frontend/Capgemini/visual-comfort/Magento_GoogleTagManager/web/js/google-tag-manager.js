/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/* jscs:disable */
/* eslint-disable */
define([
    'jquery',
    'mage/cookies'
], function ($) {
    'use strict';

    function init(config) {
        var allowServices = false,
            allowedCookies,
            allowedWebsites,
            f,
            j,
            dl;

        if (config.isCookieRestrictionModeEnabled) {
            allowedCookies = $.mage.cookies.get(config.cookieName);

            if (allowedCookies !== null) {
                allowedWebsites = JSON.parse(allowedCookies);

                if (allowedWebsites[config.currentWebsite] === 1) {
                    allowServices = true;
                }
            }
        } else {
            allowServices = true;
        }

        if (allowServices) {
            window.dataLayer = window.dataLayer || [];

            (function (w, d, s, l, c) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });
                f = d.getElementsByTagName(s)[0];
                j = d.createElement(s);
                dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                if (c.gtmScript !== null) {
                    j.src = c.gtmServer + c.gtmScript + dl;
                } else {
                    j.src = c.gtmServer + 'gtm.js?id=' + c.gtmAccountId + dl;
                }
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', config);

            window.dlCurrencyCode = config.storeCurrencyCode;

            $(document).trigger('ga:inited');
        }
    }

    /**
     * @param {Object} config
     */
    return function (config) {
        init(config);
        $(document).on('user:allowed:save:cookie', function () {
            init(config);
        });
    }
});
