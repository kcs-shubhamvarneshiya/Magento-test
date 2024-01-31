<?php

namespace Capgemini\Payfabric\Block\Checkout\Onepage;

use Magento\Checkout\Block\Checkout\AttributeMerger as AttributeMergerBlock;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;

class PhoneValidationProcessor implements LayoutProcessorInterface
{

    /**
     * @var AttributeMergerBlock
     */
    private $merger;

    public function __construct(AttributeMergerBlock $merger)
    {
        $this->merger = $merger;
    }

    public function process($jsLayout)
    {
        $paymentMethodRenders = &$jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
        ['children']['payment']['children']['payments-list']['children'];
        if (is_array($paymentMethodRenders)) {
            foreach ($paymentMethodRenders as &$renderer) {
                if (isset($renderer['children']) && array_key_exists('form-fields', $renderer['children'])) {
                    $renderer['children']['form-fields']['children']['telephone']['validation']['validate-phone-no-alpha'] = true;
                    $renderer['children']['form-fields']['children']['telephone']['validation']['validate-16-digits-limit'] = true;
                }
            }
        }

        return $jsLayout;
    }
}
