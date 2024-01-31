<?php
namespace Kcs\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class CeilingAppearOptions extends AbstractSource
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
                                ['label' => __('--Select Ceiling Appearance--'), 'value' => ''],
                                ['label' => __('FLANGELESS (CEILING MOUNT)'), 'value' => 'L'],
                                ['label' => __('FLANGED (CEILING MOUNT)'), 'value' => 'F'],
                                ['label' => __('WOOD CEILING (CEILING MOUNT)'), 'value' => 'WC'],
                                ['label' => __('T-GRID/FLANGED (HOUSING MOUNT)'), 'value' => 'T']
                            ];
        }
        return $this->_options;
    }
}
