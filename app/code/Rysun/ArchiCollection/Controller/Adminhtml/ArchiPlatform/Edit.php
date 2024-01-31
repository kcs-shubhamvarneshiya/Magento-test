<?php
declare(strict_types=1);

namespace Rysun\ArchiCollection\Controller\Adminhtml\ArchiPlatform;

class Edit extends \Rysun\ArchiCollection\Controller\Adminhtml\ArchiPlatform
{

    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('archi_platform_id');
        $model = $this->_objectManager->create(\Rysun\ArchiCollection\Model\ArchiPlatform::class);
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Archi Platform no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('rysun_archicollection_archi_platform', $model);
        
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Archi Platform') : __('New Archi Platform'),
            $id ? __('Edit Archi Platform') : __('New Archi Platform')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Archi Platforms'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Archi Platform %1', $model->getId()) : __('New Archi Platform'));
        return $resultPage;
    }
}

