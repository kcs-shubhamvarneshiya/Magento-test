<?php


namespace Lyonscg\AddressRestrictions\Controller\Adminhtml\Logs;

use Lyonscg\AddressRestrictions\Api\AddressDeleteLogRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;

class View extends \Magento\Backend\App\Action
{
    /**
     * {@inheritdoc}
     */
    const ADMIN_RESOURCE = 'Lyonscg_AdminResource::viewlogs';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var AddressDeleteLogRepositoryInterface
     */
    private $addressDeleteLogRepository;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $registry,
        AddressDeleteLogRepositoryInterface $addressDeleteLogRepository
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->registry = $registry;
        $this->addressDeleteLogRepository = $addressDeleteLogRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $logId = (int)$this->getRequest()->getParam('id');
        if (!$logId) {
            $this->messageManager->addErrorMessage(__('This address delete log does not exist'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('address_restrictions/logs/index');
        }

        try {
            $log = $this->addressDeleteLogRepository->get($logId);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('Address delete log %1 does not exist', $logId));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('address_restrictions/logs/index');
        }

        $this->registry->register('current_address_delete_log', $log);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Address Delete Log #%1', $log->getId()));

        return $resultPage;
    }
}
