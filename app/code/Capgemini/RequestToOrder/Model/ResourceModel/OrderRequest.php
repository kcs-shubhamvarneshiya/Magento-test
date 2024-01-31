<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Model\ResourceModel;

use Capgemini\RequestToOrder\Api\Data\OrderRequestInterface;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

class OrderRequest extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(OrderRequestInterface::REQUEST_TABLE, OrderRequestInterface::ID);
    }
}
