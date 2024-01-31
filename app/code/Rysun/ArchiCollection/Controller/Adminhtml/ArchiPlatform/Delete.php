<?php
declare(strict_types=1);

namespace Rysun\ArchiCollection\Controller\Adminhtml\ArchiPlatform;

class Delete extends \Rysun\ArchiCollection\Controller\Adminhtml\ArchiPlatform
{

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('archi_platform_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Rysun\ArchiCollection\Model\ArchiPlatform::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Archi Platform.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['archi_platform_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Archi Platform to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}

