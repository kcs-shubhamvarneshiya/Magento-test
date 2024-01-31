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

use Capgemini\RequestToOrder\Model\OrderRequest;
use Capgemini\RequestToOrder\Model\ResourceModel\OrderRequest as OrderRequestResource;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\VersionControl\Collection
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(OrderRequest::class, OrderRequestResource::class);
    }
}
