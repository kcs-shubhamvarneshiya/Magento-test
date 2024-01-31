<?php


namespace Lyonscg\SalesPad\Api\Data;


interface CustomerSyncInterface extends AbstractSyncInterface
{
    const CUSTOMER_ID = 'customer_id';
    const STORE_ID = 'store_id';

    /**
     * @return int
     */
    public function getCustomerId();

    /**
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId);

    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId);
}
