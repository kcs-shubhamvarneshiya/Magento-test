<?php


namespace Lyonscg\SalesPad\Controller\Adminhtml\ErrorLog;

use Lyonscg\SalesPad\Controller\Adminhtml\BaseMassDelete;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends BaseMassDelete
{
    const COLLECTION_FACTORY_CLASS = 'Lyonscg\SalesPad\Model\ResourceModel\Api\ErrorLog\CollectionFactory';
}
