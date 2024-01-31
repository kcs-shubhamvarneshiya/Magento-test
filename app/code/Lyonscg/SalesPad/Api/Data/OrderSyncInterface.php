<?php


namespace Lyonscg\SalesPad\Api\Data;


interface OrderSyncInterface extends AbstractSyncInterface
{
    const ORDER_ID = 'order_id';
    const STORE_ID = 'store_id';

    /**
     * @return int
     */
    public function getOrderId();

    /**
     * @param $orderId
     * @return $this
     */
    public function setOrderId($orderId);

    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @param $storeId
     * @return $this
     */
    public function setStoreId($storeId);
}
