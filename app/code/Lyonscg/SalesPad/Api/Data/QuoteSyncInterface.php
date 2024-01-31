<?php


namespace Lyonscg\SalesPad\Api\Data;


interface QuoteSyncInterface extends AbstractSyncInterface
{
    const QUOTE_ID = 'quote_id';
    const CUSTOMER_ID = 'customer_id';
    const SALES_DOC_NUM = 'salespad_sales_doc_num';
    const STORE_ID = 'store_id';

    /**
     * @return int
     */
    public function getQuoteId();

    /**
     * @param $quoteId
     * @return $this
     */
    public function setQuoteId($quoteId);

    /**
     * @return int
     */
    public function getCustomerId();

    /**
     * @param $customerId
     * @return $this
     */
    public function setCustomerId($customerId);

    /**
     * @return mixed
     */
    public function getSalesDocNum();

    /**
     * @param $salesDocNum
     * @return $this
     */
    public function setSalesDocNum($salesDocNum);

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
