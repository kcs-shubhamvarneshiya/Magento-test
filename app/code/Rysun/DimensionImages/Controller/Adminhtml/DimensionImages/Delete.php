<?php
declare(strict_types=1);

namespace Rysun\DimensionImages\Controller\Adminhtml\DimensionImages;

class Delete extends \Rysun\DimensionImages\Controller\Adminhtml\DimensionImages
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
        $id = $this->getRequest()->getParam('dimensionimages_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Rysun\DimensionImages\Model\DimensionImages::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Dimensionimages.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['dimensionimages_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Dimensionimages to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}

