<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Model\ResourceModel\OrderRequest;

use Capgemini\RequestToOrder\Api\Data\OrderRequest\ItemInterface;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

class Item extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(ItemInterface::REQUEST_ITEM_TABLE, ItemInterface::ID);
    }
}
