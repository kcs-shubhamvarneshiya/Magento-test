<?php
/**
 * Capgemini_CategoryAds
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\CategoryAds\Model\Config;

use Capgemini\CategoryAds\Model\CategoryAds;
use Magento\Framework\Data\OptionSourceInterface;

class Position implements OptionSourceInterface
{
    /**
     * @return array[]
     */
    public function toOptionArray() : array
    {
        return [
            [
                'value' => CategoryAds::FIRST_POSITION,
                'label' => __('First'),
            ],
            [
                'value' => CategoryAds::SECOND_POSITION,
                'label' => __('Second'),
            ]
        ];
    }
}
