<?php

namespace Lyonscg\SalesPad\Controller\Adminhtml;

use Lyonscg\SalesPad\Api\SyncRepositoryInterface;
use Lyonscg\SalesPad\Model\Api\Logger;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class BaseDelete  extends Action
{
    const DELETE_METHOD_NAME = '';
    const SUCCESS_MESSAGE = 'The entry has been successfully deleted.';
    const ERROR_MESSAGE = 'The record has not been deleted. Detailed information is available in the server logs.';

    /**
     * @var SyncRepositoryInterface
     */
    protected $actionRepository;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param Context $context
     * @param Logger $logger
     */
    public function __construct(Context $context, Logger $logger)
    {
        parent::__construct($context);
        $this->logger = $logger;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                call_user_func([$this->actionRepository, static::DELETE_METHOD_NAME], $id);
                $this->messageManager->addSuccessMessage(static::SUCCESS_MESSAGE);
            } catch (\Exception $exception) {
                $this->logger->debug('\Lyonscg\SalesPad\Controller\Adminhtml\Quote\Delete: ' . $exception->getMessage());
                $this->messageManager->addErrorMessage(static::ERROR_MESSAGE);
            }
        } else {
            $this->logger->debug('\Lyonscg\SalesPad\Controller\Adminhtml\Quote\Delete: no record id.' );
            $this->messageManager->addErrorMessage(static::ERROR_MESSAGE);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
