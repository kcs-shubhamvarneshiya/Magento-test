define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (Component,
              rendererList) {
        'use strict';
        rendererList.push(
            {
                type: 'termswholesale',
                component: 'Capgemini_PaymentTerms/js/view/payment/method-renderer/termswholesale-method'
            }
        );
        return Component.extend({});
    }
);
