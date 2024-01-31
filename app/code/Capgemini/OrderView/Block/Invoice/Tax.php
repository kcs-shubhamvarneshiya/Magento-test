<?php

namespace Capgemini\OrderView\Block\Invoice;

class Tax extends \Magento\Tax\Block\Sales\Order\Tax
{
    /**
     * Add tax total string
     *
     * @param string $after
     * @return \Magento\Tax\Block\Sales\Order\Tax
     */
    protected function _addTax($after = 'discount')
    {
        $taxTotal = new \Magento\Framework\DataObject([
            'code' => 'tax',
            'label' => __('Tax'),
            'block_name' => $this->getNameInLayout(),
            'value' => $this->_source->getTaxAmount(),
            'base_value' => $this->_source->getBaseTaxAmount()
        ]);
        $totals = $this->getParentBlock()->getTotals();
        if (isset($totals['grand_total_incl'])) {
            $this->getParentBlock()->addTotal($taxTotal, 'grand_total');
        }
        $this->getParentBlock()->addTotal($taxTotal, $after);
        return $this;
    }
}
