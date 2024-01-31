<?php
/**
 * Capgemini_ShipOnDate
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\ShipOnDate\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class DaysOfWeek implements OptionSourceInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            ['value' => '1', 'label' => __('Sunday')],
            ['value' => '2', 'label' => __('Monday')],
            ['value' => '3', 'label' => __('Tuesday')],
            ['value' => '4', 'label' => __('Wednesday')],
            ['value' => '5', 'label' => __('Thursday')],
            ['value' => '6', 'label' => __('Friday')],
            ['value' => '7', 'label' => __('Saturday')]
        ];
    }
}
