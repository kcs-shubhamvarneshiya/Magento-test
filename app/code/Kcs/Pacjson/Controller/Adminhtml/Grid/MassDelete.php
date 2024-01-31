<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Created By : Nirav Modi
 */
namespace Kcs\Pacjson\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Kcs\Pacjson\Model\ResourceModel\Pacjson\CollectionFactory;

/**
 * Mass Delete Record Controller
 */
class MassDelete extends \Magento\Backend\App\Action
{

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context           $context
     * @param Filter            $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $deleteIds = $this->getRequest()->getPost('entity_id');
        $id_string = "";
        $delete = 0;
        foreach ($deleteIds as $id) {
            if($id_string == "") {
                $id_string .= $id;
            } else {
                $id_string .= ", ".$id;
            }
            $delete = $delete + 1;
        }
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('kcs_pacjson'); //gives table name with prefix
        $sql = "delete from " . $tableName . " where entity_id in (".$id_string.")";
        //echo $sql;exit;
        $result = $connection->query($sql);
        
        /*$collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('entity_id', ['in' => $deleteIds]);
        $delete = 0;

        foreach ($collection as $item) {
            $item->delete();
            //echo "CAlled...".$item->getEntityId();
            $delete++;
        }*/

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $delete));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
