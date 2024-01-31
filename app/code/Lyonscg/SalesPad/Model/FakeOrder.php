<?php

namespace Lyonscg\SalesPad\Model;

use Magento\Sales\Model\ResourceModel\Order\Creditmemo\Collection as CreditmemoCollection;
use Magento\Sales\Model\ResourceModel\Order\Invoice\Collection as InvoiceCollection;
use Magento\Sales\Model\ResourceModel\Order\Shipment\Collection as ShipmentCollection;

class FakeOrder extends \Magento\Sales\Model\Order
{
    /**
     * Retrieve order invoices collection
     *
     * @return InvoiceCollection
     */
    public function getInvoiceCollection()
    {
        if ($this->_invoices === null) {
            $this->_invoices = $this->_invoiceCollectionFactory->create()->setOrderFilter($this);
        }
        return $this->_invoices;
    }

    /**
     * Retrieve order shipments collection
     *
     * @return ShipmentCollection|false
     */
    public function getShipmentsCollection()
    {
        if (empty($this->_shipments)) {
            $this->_shipments = $this->_shipmentCollectionFactory->create()->setOrderFilter($this);
        }
        return $this->_shipments;
    }

    /**
     * Retrieve order creditmemos collection
     *
     * @return CreditmemoCollection|false
     */
    public function getCreditmemosCollection()
    {
        if (empty($this->_creditmemos)) {
            $this->_creditmemos = $this->_memoCollectionFactory->create()->setOrderFilter($this);
        }
        return $this->_creditmemos;
    }

    /**
     * Format price precision
     *
     * @param float $price
     * @param int $precision
     * @param bool $addBrackets
     * @return string
     */
    public function formatPricePrecision($price, $precision, $addBrackets = false)
    {
        try {
            $currencyCode = $this->_storeManager->getStore()->getCurrentCurrencyCode();
        } catch (\Exception $e) {
            return parent::formatBasePricePrecision($price, $precision, $addBrackets);
        }
        return $this->getOrderCurrency()->setCurrencyCode($currencyCode)->formatPrecision($price, $precision, [], true, $addBrackets);
    }
}
