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

class Index extends Ads implements HttpGetActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(): ResultInterface
    {
        /** @var Page $resultPage */
        $pageResult = $this->pageFactory->create();

        $this->initPage($pageResult)
            ->getConfig()->getTitle()->prepend(__('Manage Category Ads'));

        return $pageResult;
    }
}
