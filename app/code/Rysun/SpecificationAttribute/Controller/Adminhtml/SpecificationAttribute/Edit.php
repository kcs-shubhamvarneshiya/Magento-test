<?php
declare(strict_types=1);

namespace Rysun\SpecificationAttribute\Controller\Adminhtml\SpecificationAttribute;

class Edit extends \Rysun\SpecificationAttribute\Controller\Adminhtml\SpecificationAttribute
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
        $id = $this->getRequest()->getParam('specificationattribute_id');
        $model = $this->_objectManager->create(\Rysun\SpecificationAttribute\Model\SpecificationAttribute::class);
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Specificationattribute no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('rysun_specificationattribute_specificationattribute', $model);
        
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Specificationattribute') : __('New Specificationattribute'),
            $id ? __('Edit Specificationattribute') : __('New Specificationattribute')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Specificationattributes'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Specificationattribute %1', $model->getId()) : __('New Specificationattribute'));
        return $resultPage;
    }
}

