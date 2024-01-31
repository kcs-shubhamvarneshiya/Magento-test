<?php
/**
 * Capgemini_Payfabric
 *
 * @category   Capgemini
 * @author    Tanya Mamchik <tanya.mamchik@capgemini.com>
 * @copyright 2021 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\Payfabric\Block\Adminhtml\System\Config\Form\Field;

use Capgemini\Payfabric\Block\Adminhtml\System\Config\Form\Field\Textarea;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class ErrorCode extends AbstractFieldArray
{
    /**
     * @var ErrorColumn
     */
    private $textareaRenderer;

    protected function _prepareToRender()
    {
        $this->addColumn(
            'code',
            [
                'label' => __('Error code'),
                'class' => 'validate-no-empty'
            ]
        );
        $this->addColumn(
            'error',
            [
                'label' => __('Error Message'),
                'renderer' => $this->getTextareaRenderer()
            ]
        );
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Error Code');
    }

    protected function getTextareaRenderer()
    {
        if (!$this->textareaRenderer) {
            $this->textareaRenderer = $this->getLayout()->createBlock(
                Textarea::class,
                ''
            );
        }
        return $this->textareaRenderer;
    }
}
