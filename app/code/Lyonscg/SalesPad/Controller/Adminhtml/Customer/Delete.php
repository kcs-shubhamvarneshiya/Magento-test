<?php

namespace Lyonscg\SalesPad\Controller\Adminhtml\Customer;

use Lyonscg\SalesPad\Controller\Adminhtml\BaseDelete;
use Lyonscg\SalesPad\Api\SyncRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Lyonscg\SalesPad\Model\Api\Logger;

class Delete extends BaseDelete
{
    const DELETE_METHOD_NAME = 'deleteCustomerEntryById';
    const SUCCESS_MESSAGE = 'The entry has been successfully deleted.';
    const ERROR_MESSAGE = 'The record has not been deleted. Detailed information is available in the server logs.';

    public function __construct(
        Context $context,
        SyncRepositoryInterface $actionRepository,
        Logger $logger
    ) {
        parent::__construct($context, $logger);
        $this->actionRepository = $actionRepository;
    }
}
