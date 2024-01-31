<?php
declare(strict_types=1);

namespace Rysun\ProductVideo\Controller\Adminhtml\ProducVideoLink;

class Edit extends \Rysun\ProductVideo\Controller\Adminhtml\ProducVideoLink
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
        $id = $this->getRequest()->getParam('producvideolink_id');
        $model = $this->_objectManager->create(\Rysun\ProductVideo\Model\ProducVideoLink::class);
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Producvideolink no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('rysun_productvideo_producvideolink', $model);
        
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Producvideolink') : __('New Producvideolink'),
            $id ? __('Edit Producvideolink') : __('New Producvideolink')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Producvideolinks'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Producvideolink %1', $model->getId()) : __('New Producvideolink'));
        return $resultPage;
    }
}

