<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Created By : Nirav Modi
 */
namespace Kcs\Pacjson\Block\Adminhtml\Grid\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Pacjson form block
 */
class Main extends Generic implements TabInterface
{

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    //protected $_adminSession;

    /**
     * @var \Kcs\Pacjson\Model\Status
     */
    protected $_status;

    /**
     * @var \Kcs\Pacjson\Model\Pacjson
     */
    protected $_pacjson;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry             $registry
     * @param \Magento\Framework\Data\FormFactory     $formFactory
     * @param \Kcs\Pacjson\Model\Pacjson              $pacjson
     * @param \Kcs\Pacjson\Model\Status               $status
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        //\Magento\Backend\Model\Auth\Session $adminSession,
        \Kcs\Pacjson\Model\Pacjson $pacjson,
        \Kcs\Pacjson\Model\Status $status,
        array $data = []
    ) {
        //$this->_adminSession = $adminSession;
        $this->_status = $status;
        $this->_pacjson = $pacjson;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare the form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('kcs_pacjson_form_data');

        $isElementDisabled = false;

        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Pacjson Information')]);

        if ($model->getId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
            $fieldset->addField('pid', 'hidden', ['name' => 'pid']);
        }

        $fieldset->addField(
            'pname',
            'text',
            [
                'name' => 'pname',
                'label' => __('Product Name'),
                'title' => __('Product Name'),
                'required' => true,
                'value' => $this->_pacjson->getAttributeCombination(),
                'disabled' => !$isElementDisabled,
            ]
        );

        $fieldset->addField(
            'attribute_combination',
            'text',
            [
                'name' => 'attribute_combination',
                'label' => __('Attribute Combination'),
                'title' => __('Attribute Combination'),
                'required' => true,
                'value' => $this->_pacjson->getAttributeCombination(),
                'disabled' => $isElementDisabled,
            ]
        );

        $fieldset->addField(
            'option_combination',
            'text',
            [
                'name' => 'option_combination',
                'label' => __('Option Combination'),
                'title' => __('Option Combination'),
                'required' => true,
                'value' => $this->_pacjson->getOptionCombination(),
                'disabled' => $isElementDisabled,
            ]
        );

        $fieldset->addField(
            'option_combination_json',
            'text',
            [
                'name' => 'option_combination_json',
                'label' => __('Option Combination Json'),
                'title' => __('Option Combination Json'),
                'required' => true,
                'value' => $this->_pacjson->getOptionCombinationJson(),
                'disabled' => $isElementDisabled,
            ]
        );

        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);

        $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'status',
                'required' => true,
                'options' => $this->_status->getOptionArray(),
                'disabled' => $isElementDisabled,
            ]
        );

        if (!$model->getEntityId()) {
            $model->setData('status', $isElementDisabled ? '0' : '1');
        }

        $form->addValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Return Tab label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Pacjson Information');
    }

    /**
     * Return Tab title
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Pacjson Information');
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
