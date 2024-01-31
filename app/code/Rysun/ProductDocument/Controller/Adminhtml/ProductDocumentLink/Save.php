<?php
declare(strict_types=1);

namespace Rysun\ProductDocument\Controller\Adminhtml\ProductDocumentLink;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('productdocumentlink_id');
        
            $model = $this->_objectManager->create(\Rysun\ProductDocument\Model\ProductDocumentLink::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Productdocumentlink no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        
            $model->setData($data);
        
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Productdocumentlink.'));
                $this->dataPersistor->clear('rysun_productdocument_productdocumentlink');
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['productdocumentlink_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Productdocumentlink.'));
            }
        
            $this->dataPersistor->set('rysun_productdocument_productdocumentlink', $data);
            return $resultRedirect->setPath('*/*/edit', ['productdocumentlink_id' => $this->getRequest()->getParam('productdocumentlink_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

