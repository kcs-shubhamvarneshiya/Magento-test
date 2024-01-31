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

class NewAction extends Ads implements HttpGetActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(): ResultInterface
    {
        return $this->resultForwardFactory->create()->forward('edit');
    }
}
