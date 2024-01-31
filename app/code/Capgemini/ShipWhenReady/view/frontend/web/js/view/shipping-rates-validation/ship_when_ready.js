/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'uiComponent',
    'Magento_Checkout/js/model/shipping-rates-validator',
    'Magento_Checkout/js/model/shipping-rates-validation-rules',
    'Magento_OfflineShipping/js/model/shipping-rates-validator/flatrate',
    'Magento_OfflineShipping/js/model/shipping-rates-validation-rules/flatrate'
], function (
    Component,
    defaultShippingRatesValidator,
    defaultShippingRatesValidationRules,
    flatrateShippingRatesValidator,
    flatrateShippingRatesValidationRules
) {
    'use strict';

    defaultShippingRatesValidator.registerValidator('ship_when_ready', flatrateShippingRatesValidator);
    defaultShippingRatesValidationRules.registerRules('ship_when_ready', flatrateShippingRatesValidationRules);

    return Component;
});
