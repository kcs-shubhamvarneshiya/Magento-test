<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Model\ResourceModel\OrderRequest\Item;

use Capgemini\RequestToOrder\Api\Data\OrderRequestInterface;
use Capgemini\RequestToOrder\Model\OrderRequest\Item;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\VersionControl\Collection
{
    private $orderReuqest;

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            Item::class,
            \Capgemini\RequestToOrder\Model\ResourceModel\OrderRequest\Item::class
        );
    }

    /**
     * @param OrderRequestInterface $request
     * @return $this
     */
    public function setRequest(OrderRequestInterface $request): self
    {
        $this->orderReuqest = $request;
        $requestId = $this->orderReuqest->getId();
        if ($requestId) {
            $this->addFieldToFilter(OrderRequestInterface::ID, $requestId);
        } else {
            $this->_totalRecords = 0;
            $this->_setIsLoaded();
        }
        return $this;
    }
}
