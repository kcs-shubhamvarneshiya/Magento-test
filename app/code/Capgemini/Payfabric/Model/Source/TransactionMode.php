<?php
/**
 * Capgemini_Payfabric
 *
 * @category   Capgemini
 * @author    Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2020 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\Payfabric\Model\Source;

use Magento\Payment\Model\Method\AbstractMethod;

class TransactionMode implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'sandbox',
                'label' => __('Sandbox'),
            ],
            [
                'value' => 'live',
                'label' => __('Live'),
            ]
        ];
    }
}
