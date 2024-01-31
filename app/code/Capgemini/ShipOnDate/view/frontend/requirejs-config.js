/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/view/shipping': {
                'Capgemini_ShipOnDate/js/view/shipping-mixin': true
            },
            'Magento_Checkout/js/view/shipping-information': {
                'Capgemini_ShipOnDate/js/view/shipping-information-mixin': true
            },
            'Magento_Checkout/js/action/select-shipping-method': {
                'Capgemini_ShipOnDate/js/action/select-shipping-mixin': true
            },
            'Magento_Checkout/js/model/shipping-save-processor/payload-extender': {
                'Capgemini_ShipOnDate/js/model/shipping-save-processor/payload-extender-mixin': true
            }
        }
    }
};
