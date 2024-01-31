/**
 * Capgemini_WholesaleAddress
 *
 * Mixin to prevent saving shipping address to address book for wholesale customers
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
define([
    'mage/utils/wrapper'
], function (wrapper) {
    'use strict';

    return function (selectShippingAddress) {
        return wrapper.wrap(selectShippingAddress, function (selectShippingAddress, shippingAddress) {
            //Do not validate billing address for wholesale customer
            if (window.checkoutConfig.isWholesaleCustomer) {
                shippingAddress['save_in_address_book'] = 0;
            }
            return selectShippingAddress(shippingAddress);
        });
    };
});
