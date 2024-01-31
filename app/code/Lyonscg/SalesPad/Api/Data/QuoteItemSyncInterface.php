<?php

namespace Lyonscg\SalesPad\Api\Data;

interface QuoteItemSyncInterface extends AbstractSyncInterface
{
    const QUOTE_ID = 'quote_id';
    const ITEM_ID = 'item_id';
    const SALES_DOC_NUM = 'salespad_sales_doc_num';
    const COMPONENT_SEQ_NUM = 'salespad_component_seq_num';
    const LINE_NUM = 'salespad_line_num';

    /**
     * @return int
     */
    public function getItemId();

    /**
     * @param $itemId
     * @return $this
     */
    public function setItemId($itemId);

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
     * @return mixed
     */
    public function getSalesDocNum();

    /**
     * @param $salesDocNum
     * @return $this
     */
    public function setSalesDocNum($salesDocNum);

    /**
     * @return mixed
     */
    public function getLineNum();

    /**
     * @param $lineNum
     * @return $this
     */
    public function setLineNum($lineNum);

    /**
     * @return mixed
     */
    public function getComponentSeqNum();

    /**
     * @param $componentSeqNum
     * @return $this
     */
    public function setComponentSeqNum($componentSeqNum);
}
