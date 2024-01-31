<?php
/**
 * Copyright © 2016 Lyons Consulting Group, LLC. All rights reserved.
 */

namespace Lyonscg\ImportExport\Plugin\Block\Adminhtml\Scheduled\Operation\Edit\Form;

use \Magento\ImportExport\Model\Import as ImportModel;

/**
 * Class Import
 *
 * Add additional fields to the scheduled import form
 */
class Import
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Import constructor.
     * @param \Lyonscg\ImportExport\Model\TransformSources\Config $config
     */
    public function __construct(
        \Magento\Framework\Registry $registry
    )
    {
        $this->_coreRegistry = $registry;
    }

    /**
     * After form set add fields
     *
     * Transformation field
     * Delete local file select
     * Suppress emails select
     *
     * @param \Magento\ScheduledImportExport\Block\Adminhtml\Scheduled\Operation\Edit\Form $formBlock
     * @param $result
     * @return mixed
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function beforeFetchView(\Magento\ScheduledImportExport\Block\Adminhtml\Scheduled\Operation\Edit\Form $formBlock, $result)
    {
        $form = $formBlock->getForm();

        $fieldset = $form->getElement('operation_settings');
        $fieldset->addField(
            'cron',
            'text',
            ['name' => 'cron', 'title' => __('CRON'), 'label' => __('CRON'), 'required' => false, 'note' => 'Value used when frequency is set to \'CRON\'. Standard CRON syntax, i.e. \'0 * * * *\''],
            'freq'
        );

        $fieldset = $form->getElement('file_settings');

        $fieldset->addField(
            'archive_directory',
            'text',
            [
                'name' => 'file_info[archive_directory]',
                'title' => __('Archive Directory'),
                'label' => __('Archive Directory'),
                'note' => __('For Type "Local Server" use relative path to Magento installation<br/><i>example: var/gp/import/base/{{Y-m}}/{{d}} => var/gp/import/base/2018-03/15</i>'),
                'required' => false,
            ],
            'file_path'
        );

        $fieldset->addField(
            'as_directory',
            'select',
            [
                'name' => 'file_info[as_directory]',
                'title' => __('Use as Directory'),
                'label' => __('Use as Directory'),
                'note' => __('Ignore the file name setting an use the first file found in the specified directory.'),
                'required' => false,
                'values' => ['0' => 'No', '1' => 'Yes']
            ],
            'archive_directory'
        );


        $fieldset->addField(
            'delete_local_file',
            'select',
            [
                'name' => 'file_info[delete_local_file]',
                'title' => __('Delete Local File'),
                'label' => __('Delete Local File'),
                'note' => __('Remove local file from file directory.'),
                'required' => true,
                'values' => ['0' => 'No', '1' => 'Yes']
            ],
            'lower_headers'
        );

        $fieldset->addField(
            'suppress_not_found',
            'select',
            [
                'name' => 'file_info[suppress_not_found]',
                'title' => __('Suppress Not Found'),
                'label' => __('Suppress Not Found'),
                'note' => __('Suppress file not found email notifications.'),
                'required' => true,
                'values' => ['0' => 'No', '1' => 'Yes']
            ],
            'delete_local_file'
        );

        $this->_setFormValues($form);

        return $result;
    }


    /**
     * @param \Magento\Framework\Data\Form $form
     * @return $this
     */
    protected function _setFormValues(\Magento\Framework\Data\Form $form)
    {
        $operation = $this->_coreRegistry->registry('current_operation');
        $data = $operation->getData();

        if (isset($data['file_info'])) {
            $fileInfo = $data['file_info'];
            unset($data['file_info']);
            if (is_array($fileInfo)) {
                $data = array_merge($data, $fileInfo);
            }
        }
        if (isset($data['entity_type'])) {
            $data['entity'] = $data['entity_type'];
        }

        $customFields = [
            'cron',
            'transformation',
            'lower_headers',
            'delete_local_file',
            'suppress_not_found',
            'as_directory',
            'archive_directory',
        ];


        foreach (array_keys($data) as $field) {
            if (!in_array($field, $customFields)) {
                unset($data[$field]);
            }
        }
        $form->addValues($data);
        return $this;
    }

}
