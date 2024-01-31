<?php

namespace Xtento\Custom\Model\Config\Source;

use  Magento\Framework\Data\OptionSourceInterface;

class Environment implements OptionSourceInterface
{

    /**
     * @ingeritdoc
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'stage',
                'label' => __('Staging')
            ],
            [
                'value' => 'prod',
                'label' => __('Production')
            ]
        ];
    }
}
