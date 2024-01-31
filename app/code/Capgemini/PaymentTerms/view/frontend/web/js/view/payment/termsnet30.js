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
                type: 'termsnet30',
                component: 'Capgemini_PaymentTerms/js/view/payment/method-renderer/termsnet30-method'
            }
        );
        return Component.extend({});
    }
);
