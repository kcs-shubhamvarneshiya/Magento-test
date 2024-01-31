<?php
/**
 * Capgemini_Payfabric
 *
 * @category   Capgemini
 * @author    Tanya Mamchik <tanya.mamchik@capgemini.com>
 * @copyright 2021 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\Payfabric\Block\Adminhtml\System\Config\Form\Field;

class Textarea extends \Magento\Framework\View\Element\Template
{
    /**
     * @return string
     */
    public function _toHtml()
    {
        $inputName = $this->getInputName();
        $column = $this->getColumn();

        return '<textarea id="' . $this->getInputId().'" name="' . $inputName . '" ' .
            ($column['size'] ? 'size="' . $column['size'] . '"' : '') . ' class="' .
            (isset($column['class']) ? $column['class'] : 'input-text') . '"'.
            (isset($column['style']) ? ' style="'.$column['style'] . '"' : '') . '></textarea>';
    }
}
