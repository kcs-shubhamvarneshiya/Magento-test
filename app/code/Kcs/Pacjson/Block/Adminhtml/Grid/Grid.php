<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Created By : Nirav Modi
 */
namespace Kcs\Pacjson\Block\Adminhtml\Grid;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    /**
     * @var \Kcs\Pacjson\Model\Status
     */
    protected $_status;

    /**
     * @var \Kcs\Pacjson\Model\PacjsonFactory
     */
    protected $_pacjsonFactory;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * Collection object
     *
     * @var \Magento\Framework\Data\Collection
     */
    protected $_collection;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data            $backendHelper
     * @param \Kcs\Pacjson\Model\PacjsonFactory              $pacjsonFactory
     * @param \Kcs\Pacjson\Model\Status                   $status
     * @param \Magento\Framework\Module\Manager       $moduleManager
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Kcs\Pacjson\Model\PacjsonFactory $pacjsonFactory,
        \Kcs\Pacjson\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_pacjsonFactory = $pacjsonFactory;
        $this->_status = $status;
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('gridGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('grid_record');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->_pacjsonFactory->create()->getCollection();

        $data = [];
        $filter = $this->getParam($this->getVarNameFilter(), null);
        if ($filter) {
            $data = $this->_backendHelper->prepareFilterString($filter);
            $data = array_merge($data, (array)$this->getRequest()->getPost($this->getVarNameFilter()));
        }
        if (!empty($data['option_combination_json']) && $data['option_combination_json'] != "") {
            $regexpression = $data['option_combination_json'];
            $collection->addFieldToFilter(
                'option_combination_json',
                ['like' => $regexpression]
            );
        }
        
        $this->setCollection($collection);
        //parent::_prepareCollection();
        return $this;
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'entity_id',
            [
                'header' => __('ID'),
                'filter' => false,
                'type' => 'number',
                'index' => 'entity_id',
                'sortable' => true,
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );

        $this->addColumn(
            'pid',
            [
                'header' => __('Product Id'),
                'index' => 'pid',
                //'renderer' => '\Kcs\Pacjson\Block\Adminhtml\Pname\Grid\Renderer\Options',
            ]
        );

        $this->addColumn(
            'pname',
            [
                'header' => __('Product Name'),
                'filter' => false,
                'index' => 'pname',
                //'renderer' => '\Kcs\Pacjson\Block\Adminhtml\Pname\Grid\Renderer\Options',
            ]
        );

        /*$this->addColumn(
            'attribute_combination',
            [
                'header' => __('Attribute Combination'),
                'index' => 'attribute_combination',
            ]
        );

        $this->addColumn(
            'option_combination',
            [
                'header' => __('Option Combination'),
                'index' => 'option_combination',
            ]
        );*/

        $this->addColumn(
            'option_combination_json',
            [
                'header' => __('Option Combination Json'),
                'index' => 'option_combination_json',
            ]
        );

        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'type' => 'options',
                'options' => $this->_status->getOptionArray(),
            ]
        );

        $this->addColumn(
            'created_at',
            [
                'header' => __('Created At'),
                'filter' => false,
                'index' => 'created_at',
            ]
        );

        $this->addColumn(
            'edit',
            [
                'header' => __('Edit'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => 'pacjson/*/edit',
                        ],
                        'field' => 'entity_id',
                    ],
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action',
            ]
        );

        $this->addColumn(
            'delete',
            [
                'header' => __('Delete'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Delete'),
                        'url' => [
                            'base' => 'pacjson/*/delete',
                        ],
                        'field' => 'entity_id',
                    ],
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action',
            ]
        );

        $block = $this->getLayout()->getBlock('grid.bottom.links');

        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('entity_id');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('pacjson/*/massDelete'),
                'confirm' => __('Are you sure?'),
            ]
        );

        $statuses = $this->_status->toOptionArray();

        array_unshift($statuses, ['label' => '', 'value' => '']);

        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('pacjson/*/massStatus', ['_current' => true]),
                'additional' => [
                    'visibility' => [
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => __('Status'),
                        'values' => $statuses,
                    ],
                ],
            ]
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('pacjson/*/grid', ['_current' => true]);
    }

    /**
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('pacjson/*/edit', ['entity_id' => $row->getEntityId()]);
    }
}
