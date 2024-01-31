<?php

namespace Lyonscg\SalesPad\Api\Data;

interface DeletedQuoteInterface
{
    const SALES_DOC_NUM = 'salespad_sales_doc_num';

    /**
     * @param string $docNum
     * @return $this
     */
    public function setSalespadSalesDocNum($docNum);

    /**
     * @return string
     */
    public function getSalespadSalesDocNum();
}
