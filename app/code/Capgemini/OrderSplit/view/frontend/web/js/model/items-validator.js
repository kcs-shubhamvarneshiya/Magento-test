/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mage/validation'
], function ($) {
    'use strict';

    var poNumbersInputs = '.payment-method._active div[data-role=po-numbers] input';

    return {
        /**
         * Validate checkout agreements
         *
         * @returns {Boolean}
         */
        validate: function (hideError) {
            var isValid = true;

            $(poNumbersInputs).each(function (index, element) {
                if (!$.validator.validateSingleElement(element, {
                    errorElement: 'div',
                    hideError: hideError || false
                })) {
                    isValid = false;
                }
            });

            return isValid;
        }
    };
});
