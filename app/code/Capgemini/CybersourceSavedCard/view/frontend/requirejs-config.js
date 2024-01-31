/**
 * Capgemini_Cybersource
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
var config = {
    'map': {
        '*': {
            'CyberSource_SecureAcceptance/template/payment/sa/iframe.html':
                'Capgemini_CybersourceSavedCard/template/payment/sa/iframe.html',
            'CyberSource_SecureAcceptance/template/payment/vault-form.html':
                'Capgemini_CybersourceSavedCard/template/payment/vault-form.html'
        }
    },
    'config': {
        'mixins': {
            'Magento_Checkout/js/model/payment-service': {
                'Capgemini_CybersourceSavedCard/js/model/payment-service-mixin': true
            }
        }
    }
};
