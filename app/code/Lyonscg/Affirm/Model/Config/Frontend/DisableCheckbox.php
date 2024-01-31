<?php

namespace Lyonscg\Affirm\Model\Config\Frontend;

class DisableCheckbox extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @inheritDoc
     */
    protected function _isInheritCheckboxRequired(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return false;
    }
}
