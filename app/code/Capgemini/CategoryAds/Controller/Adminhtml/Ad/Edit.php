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
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;

class Edit extends Ads implements HttpGetActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(): ResultInterface
    {
        /** @var Page $resultPage */
        $resultPage = $this->pageFactory->create();

        $model = $this->initModel();

        if ($this->request->getParam('id')) {
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This category ad no longer exists.'));
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            }
        }

        $this->initPage($resultPage)->getConfig()->getTitle()->prepend(
            $model->getId() ? __('Edit Category Ad "%1"', $model->getName()) : __('New Category Ad')
        );

        return $resultPage;
    }
}
