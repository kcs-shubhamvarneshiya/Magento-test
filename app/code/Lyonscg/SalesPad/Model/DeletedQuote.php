<?php

namespace Lyonscg\SalesPad\Model;

use Lyonscg\SalesPad\Api\Data\DeletedQuoteInterface;

class DeletedQuote extends \Magento\Framework\Model\AbstractModel implements DeletedQuoteInterface
{
    protected $_isObjectNew;
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Lyonscg\SalesPad\Model\ResourceModel\DeletedQuote::class);
    }

    /**
     * @param string $docNum
     * @return DeletedQuote
     */
    public function setSalespadSalesDocNum($docNum)
    {
        return $this->setData(self::SALES_DOC_NUM, $docNum);
    }

    /**
     * @return string
     */
    public function getSalespadSalesDocNum()
    {
        return $this->getData(self::SALES_DOC_NUM);
    }
}
