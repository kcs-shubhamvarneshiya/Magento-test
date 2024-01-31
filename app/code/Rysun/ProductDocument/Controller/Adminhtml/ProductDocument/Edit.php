<?php
declare(strict_types=1);

namespace Rysun\ProductDocument\Controller\Adminhtml\ProductDocument;

class Edit extends \Rysun\ProductDocument\Controller\Adminhtml\ProductDocument
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
        $id = $this->getRequest()->getParam('productdocument_id');
        $model = $this->_objectManager->create(\Rysun\ProductDocument\Model\ProductDocument::class);
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Productdocument no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('rysun_productdocument_productdocument', $model);
        
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Productdocument') : __('New Productdocument'),
            $id ? __('Edit Productdocument') : __('New Productdocument')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Productdocuments'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Productdocument %1', $model->getId()) : __('New Productdocument'));
        return $resultPage;
    }
}

