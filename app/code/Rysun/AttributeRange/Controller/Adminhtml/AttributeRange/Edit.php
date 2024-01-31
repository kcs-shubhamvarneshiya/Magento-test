<?php
declare(strict_types=1);

namespace Rysun\AttributeRange\Controller\Adminhtml\AttributeRange;

class Edit extends \Rysun\AttributeRange\Controller\Adminhtml\AttributeRange
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
        $id = $this->getRequest()->getParam('attributerange_id');
        $model = $this->_objectManager->create(\Rysun\AttributeRange\Model\AttributeRange::class);
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Attributerange no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('rysun_attributerange_attributerange', $model);
        
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Attributerange') : __('New Attributerange'),
            $id ? __('Edit Attributerange') : __('New Attributerange')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Attributeranges'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Attributerange %1', $model->getId()) : __('New Attributerange'));
        return $resultPage;
    }
}

