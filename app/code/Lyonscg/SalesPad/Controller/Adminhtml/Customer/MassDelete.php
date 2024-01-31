<?php


namespace Lyonscg\SalesPad\Controller\Adminhtml\Customer;

use Lyonscg\SalesPad\Controller\Adminhtml\BaseMassDelete;

class MassDelete extends BaseMassDelete
{
    const COLLECTION_FACTORY_CLASS = 'Lyonscg\SalesPad\Model\ResourceModel\Sync\Customer\CollectionFactory';
}
