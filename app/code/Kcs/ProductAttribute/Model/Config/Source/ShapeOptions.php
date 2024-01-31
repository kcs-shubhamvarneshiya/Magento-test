<?php
namespace Kcs\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class ShapeOptions extends AbstractSource
{
    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (null === $this->_options) {
            $this->_options=[
                                ['label' => __('--Select Shape--'), 'value' => ''],
                                ['label' => __('Round'), 'value' => 'R'],
                                ['label' => __('Square'), 'value' => 'S']
                            ];
        }
        return $this->_options;
    }
}
