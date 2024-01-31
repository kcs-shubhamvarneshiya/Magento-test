<?php
declare(strict_types=1);

namespace Rysun\ProductVideo\Controller\Adminhtml\ProductVideo;

class Edit extends \Rysun\ProductVideo\Controller\Adminhtml\ProductVideo
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
        $id = $this->getRequest()->getParam('productvideo_id');
        $model = $this->_objectManager->create(\Rysun\ProductVideo\Model\ProductVideo::class);
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Productvideo no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('rysun_productvideo_productvideo', $model);
        
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Productvideo') : __('New Productvideo'),
            $id ? __('Edit Productvideo') : __('New Productvideo')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Productvideos'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Productvideo %1', $model->getId()) : __('New Productvideo'));
        return $resultPage;
    }
}

