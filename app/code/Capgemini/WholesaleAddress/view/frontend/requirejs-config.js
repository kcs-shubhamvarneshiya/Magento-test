/**
 * Capgemini_WholesaleAddress
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
var config = {
    'map': {
        '*': {
            'Magento_Checkout/template/shipping-address/form.html':
                'Capgemini_WholesaleAddress/template/checkout/shipping-address/form.html',
            'Magento_Checkout/template/billing-address/form.html':
                'Capgemini_WholesaleAddress/template/checkout/billing-address/form.html'
        }
    },
    config: {
        mixins: {
            'Magento_Checkout/js/view/shipping': {
                'Capgemini_WholesaleAddress/js/view/shipping-mixin': true
            },
            'Magento_Checkout/js/view/billing-address': {
                'Capgemini_WholesaleAddress/js/view/billing-address-mixin': true
            },
            'ClassyLlama_AvaTax/js/model/billingAddressValidation': {
                'Capgemini_WholesaleAddress/js/model/billingAddressValidation-mixin': true
            },
            'Magento_Checkout/js/action/select-shipping-address': {
                'Capgemini_WholesaleAddress/js/action/select-shipping-address-mixin': true
            }
        }
    }
};
