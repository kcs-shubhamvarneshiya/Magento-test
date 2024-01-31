/**
 * Capgemini_WholesaleAddress
 *
 * Mixin to disable billing address validation for wholesale customers
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
define([
    'mage/utils/wrapper'
], function (wrapper) {
    'use strict';

    return function (target) {
        target.validateBillingAddress = wrapper.wrap(target.validateBillingAddress, function (validateBillingAddress) {
            //Do not validate billing address for wholesale customer
            if (window.checkoutConfig.isWholesaleCustomer) {
                return true;
            }
            return validateBillingAddress();
        });
        return target;
    };
});
