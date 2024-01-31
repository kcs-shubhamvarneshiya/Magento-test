<?php
/**
 * Capgemini_CategoryAds
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\CategoryAds\Model\ResourceModel\CategoryAds\CategoryLink;

use Capgemini\CategoryAds\Model\CategoryAds\CategoryLink;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(
            CategoryLink::class,
            \Capgemini\CategoryAds\Model\ResourceModel\CategoryAds\CategoryLink::class
        );
    }
}
