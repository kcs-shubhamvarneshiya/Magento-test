<?php

namespace Lyonscg\SalesPad\Controller\Adminhtml\ErrorLog;

use Lyonscg\SalesPad\Api\ErrorLogRepositoryInterface;
use Lyonscg\SalesPad\Controller\Adminhtml\BaseDelete;
use Lyonscg\SalesPad\Model\Api\Logger;
use Magento\Backend\App\Action\Context;

class Delete extends BaseDelete
{
    const DELETE_METHOD_NAME = 'deleteById';
    const SUCCESS_MESSAGE = 'The entry has been successfully deleted.';
    const ERROR_MESSAGE = 'The record has not been deleted. Detailed information is available in the server logs.';

    public function __construct(
        Context $context,
        ErrorLogRepositoryInterface $actionRepository,
        Logger $logger
    ) {
        parent::__construct($context, $logger);
        $this->actionRepository = $actionRepository;
    }
}
