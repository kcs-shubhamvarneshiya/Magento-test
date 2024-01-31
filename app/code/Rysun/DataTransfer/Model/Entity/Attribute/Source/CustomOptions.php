<?php
namespace Rysun\DataTransfer\Model\Entity\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class CustomOptions extends AbstractSource
{
    public function getAllOptions()
    {
        $this->_options = [
            ['label' => __('Simple product'), 'value' => 'option_1']
        ];

        return $this->_options;
    }

    public function toOptionArray()
    {
        return $this->getAllOptions();
    }
}
