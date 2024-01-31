<?php
/**
 * Capgemini_PaymentTerms
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\PaymentTerms\Ui;

use Capgemini\PaymentTerms\Model\Wholesale;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Checkout config provider
 */
class ConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    protected const XML_CONFIG_DESCRIPTION = 'payment/termswholesale/description';

    protected ScopeConfigInterface $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getConfig()
    {
        return [
            'payment' => [
                Wholesale::PAYMENT_METHOD_TERMWHOLESALE_CODE => [
                    'description' => $this->scopeConfig->getValue(self::XML_CONFIG_DESCRIPTION,ScopeInterface::SCOPE_STORE)
                ]
            ]
        ];
    }
}
