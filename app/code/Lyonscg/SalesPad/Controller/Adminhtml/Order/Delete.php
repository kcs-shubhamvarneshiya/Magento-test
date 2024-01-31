<?php

namespace Lyonscg\SalesPad\Controller\Adminhtml\Order;

use Lyonscg\SalesPad\Controller\Adminhtml\BaseDelete;
use Magento\Backend\App\Action;
use Lyonscg\SalesPad\Api\SyncRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Lyonscg\SalesPad\Model\Api\Logger;

class Delete extends BaseDelete
{
    const DELETE_METHOD_NAME = 'deleteOrderEntryById';
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
