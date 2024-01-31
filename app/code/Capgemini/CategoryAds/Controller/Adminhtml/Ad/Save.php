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

use Capgemini\CategoryAds\Api\Data\CategoryAdsInterface;
use Capgemini\CategoryAds\Controller\Adminhtml\Ads;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;

class Save extends Ads implements HttpGetActionInterface, HttpPostActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(): ResultInterface
    {
        $id = $this->request->getParam(CategoryAdsInterface::ID);

        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->request->getPostValue();

        if ($data) {
            $model = $this->initModel();

            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This category ad no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            if (isset($data[CategoryAdsInterface::NAME])) {
                $model->setName($data[CategoryAdsInterface::NAME]);
            }

            if (isset($data[CategoryAdsInterface::CATEGORIES])) {
                $model->setCategories($data[CategoryAdsInterface::CATEGORIES]);
            }

            if (isset($data[CategoryAdsInterface::IS_ENABLED])) {
                $model->setIsEnabled($data[CategoryAdsInterface::IS_ENABLED]);
            }

            if (isset($data[CategoryAdsInterface::POSITION])) {
                $model->setPosition($data[CategoryAdsInterface::POSITION]);
            }

            if (isset($data[CategoryAdsInterface::CONTENT])) {
                $model->setContent($data[CategoryAdsInterface::CONTENT]);
            }

            try {
                $this->adsRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You have saved the category ad.'));

                return $resultRedirect->setPath('*/*/edit', [CategoryAdsInterface::ID => $model->getId()]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', [CategoryAdsInterface::ID => $id]);
            }
        } else {
            $resultRedirect->setPath('*/*/');
            $this->messageManager->addErrorMessage('No data to save.');

            return $resultRedirect;
        }
    }
}
