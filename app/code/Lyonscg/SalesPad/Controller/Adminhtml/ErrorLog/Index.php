<?php


namespace Lyonscg\SalesPad\Controller\Adminhtml\ErrorLog;

use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\AbstractAction
{
    const ADMIN_RESOURCE = 'Lyonscg_Salespad::manage';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Lyonscg_SalesPad::view_errors');
        $resultPage->getConfig()->getTitle()->prepend(__('SalesPad API Errors'));

        return $resultPage;
    }
}
