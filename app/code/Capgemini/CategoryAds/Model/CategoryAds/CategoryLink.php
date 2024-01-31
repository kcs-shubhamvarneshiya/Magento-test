<?php
/**
 * Capgemini_CategoryAds
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\CategoryAds\Model\CategoryAds;

use Magento\Framework\Model\AbstractModel;

class CategoryLink extends AbstractModel
{
    /**
     * @return void
     */
    public function _construct(): void
    {
        $this->_init(\Capgemini\CategoryAds\Model\ResourceModel\CategoryAds\CategoryLink::class);
    }
}
