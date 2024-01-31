<?php


namespace Lyonscg\SalesPad\Controller\Adminhtml\Order;

use Lyonscg\SalesPad\Controller\Adminhtml\BaseMassDelete;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends BaseMassDelete
{
    const COLLECTION_FACTORY_CLASS = 'Lyonscg\SalesPad\Model\ResourceModel\Sync\Order\CollectionFactory';
}
