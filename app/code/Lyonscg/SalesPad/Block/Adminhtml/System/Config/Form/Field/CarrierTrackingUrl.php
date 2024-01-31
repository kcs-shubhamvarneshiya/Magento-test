<?php

namespace Lyonscg\SalesPad\Block\Adminhtml\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class CarrierTrackingUrl extends AbstractFieldArray
{

    /**
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'carrier',
            [
                'label' => __('Carrier Name'),
                'class' => 'validate-no-empty field-carrier'
            ]
        );
        $this->addColumn(
            'tracking_url',
            [
                'label' => __('Tracking URL'),
                'class' => 'validate-no-empty field-tracking-url'
            ]
        );
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Carrier Tracking URL');
    }
}
