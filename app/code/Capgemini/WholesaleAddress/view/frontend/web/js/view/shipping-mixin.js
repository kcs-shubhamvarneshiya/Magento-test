/**
 * Capgemini_WholesaleAddress
 *
 * Mixin ta add isWholesaleCustomer flag to the shipping view
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
define(function () {
    'use strict';

    var mixin = {
        isWholesaleCustomer : window.checkoutConfig.isWholesaleCustomer
    };

    return function (target) {
        return target.extend(mixin);
    };
});
