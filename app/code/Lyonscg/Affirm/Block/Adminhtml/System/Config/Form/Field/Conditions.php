<?php

namespace Lyonscg\Affirm\Block\Adminhtml\System\Config\Form\Field;

use Magento\Backend\Block\Widget\Form;
use Lyonscg\Affirm\Helper\Rule as ModuleHelper;

class Conditions extends \Magento\CatalogRule\Block\Adminhtml\Promo\Catalog\Edit\Tab\Conditions implements \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    /**
     * @var ModuleHelper
     */
    private $moduleHelper;

    public function __construct(ModuleHelper $moduleHelper, \Magento\Backend\Block\Template\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Data\FormFactory $formFactory, \Magento\Rule\Block\Conditions $conditions, \Magento\Backend\Block\Widget\Form\Renderer\Fieldset $rendererFieldset, array $data = [])
    {
        parent::__construct($context, $registry, $formFactory, $conditions, $rendererFieldset, $data);
        $this->moduleHelper = $moduleHelper;
    }

    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->toHtml();
    }

    public function _prepareForm()
    {
        $this->moduleHelper->registerRuleFromSerialisedData();

        return parent::_prepareForm();
    }

    /**
     * Set form object
     *
     * @param \Magento\Config\Block\System\Config\Form $form
     * @return $this
     */
    public function setForm($form)
    {
        $this->_form = $form;
        $this->_form->setParent($this);
        $this->_form->setBaseUrl($this->_urlBuilder->getBaseUrl());

        $customAttributes = $this->getData('custom_attributes');
        if (is_array($customAttributes)) {
            foreach ($customAttributes as $key => $value) {
                $this->_form->addCustomAttribute($key, $value);
            }
        }
        return $this;
    }
}
