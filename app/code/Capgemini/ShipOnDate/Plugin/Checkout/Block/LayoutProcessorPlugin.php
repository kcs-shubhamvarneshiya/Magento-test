<?php
/**
 * Capgemini_ShipOnDate
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\ShipOnDate\Plugin\Checkout\Block;

use Magento\Checkout\Block\Checkout\LayoutProcessor;

class LayoutProcessorPlugin
{
    /**
     * @param LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        LayoutProcessor $subject,
        array $jsLayout
    ) {
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['delivery_date'] = [
            'component' => 'Capgemini_ShipOnDate/js/view/delivery-date',
            'config' => [
                'customScope' => 'delivery_date',
                'options' => [],
                'id' => 'delivery_date',
                'data-bind' => ['datetimepicker' => true]
            ],
            'dataScope' => 'delivery_date',
            'provider' => 'checkoutProvider',
            'displayArea' => 'delivery_date',
            'visible' => true,
            'sortOrder' => 10,
            'id' => 'delivery_date'
        ];

        return $jsLayout;
    }
}
