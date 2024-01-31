<?php
/**
 * Capgemini_CategoryAds
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\CategoryAds\Controller\Adminhtml\Ad;

use Capgemini\CategoryAds\Controller\Adminhtml\Ads;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;

class Delete extends Ads implements HttpGetActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(): ResultInterface
    {
        $model = $this->initModel();

        $resultRedirect = $this->resultRedirectFactory->create();

        if ($model->getId()) {
            try {
                $this->adsRepository->delete($model);

                $this->messageManager->addSuccessMessage(__('Category ad has been deleted.'));

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {

                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
            }
        } else {
            $this->messageManager->addErrorMessage(__('This category ad no longer exists.'));

            return $resultRedirect->setPath('*/*/');
        }
    }
}
