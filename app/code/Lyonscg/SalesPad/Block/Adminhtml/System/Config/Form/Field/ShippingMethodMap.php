<?php

namespace Lyonscg\SalesPad\Block\Adminhtml\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class ShippingMethodMap extends AbstractFieldArray
{

    /**
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'magento',
            [
                'label' => __('Magento'),
                'class' => 'validate-no-empty'
            ]
        );
        $this->addColumn(
            'salespad',
            [
                'label' => __('SalesPad'),
                'class' => 'validate-no-empty'
            ]
        );
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Shipping Method');
    }
}
