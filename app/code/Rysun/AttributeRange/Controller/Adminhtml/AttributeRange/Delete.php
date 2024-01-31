<?php
declare(strict_types=1);

namespace Rysun\AttributeRange\Controller\Adminhtml\AttributeRange;

class Delete extends \Rysun\AttributeRange\Controller\Adminhtml\AttributeRange
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
        $id = $this->getRequest()->getParam('attributerange_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Rysun\AttributeRange\Model\AttributeRange::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Attributerange.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['attributerange_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Attributerange to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}

