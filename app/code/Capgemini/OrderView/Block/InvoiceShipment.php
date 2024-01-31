<?php

namespace Capgemini\OrderView\Block;

use Capgemini\OrderView\Helper\Product as ProductHelper;
use Lyonscg\SalesPad\Model\Config;
use Magento\Directory\Model\Currency;
use Magento\Directory\Model\CurrencyFactory;

class InvoiceShipment extends \Magento\Framework\View\Element\Template
{
    protected $_salesPadData = [];

    /**
     * @var CurrencyFactory
     */
    protected $currencyFactory;

    /** @var \Magento\Directory\Model\Currency */
    protected $currency = null;

    /**
     * @var ProductHelper
     */
    protected $productHelper;

    /**
     * @var Config
     */
    protected $config;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        CurrencyFactory $currencyFactory,
        ProductHelper $productHelper,
        Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->currencyFactory = $currencyFactory;
        $this->productHelper = $productHelper;
        $this->config = $config;
    }

    /**
     * @return Currency
     */
    protected function _getCurrency()
    {
        if ($this->currency === null) {
            $this->currency = $this->currencyFactory->create();
        }
        return $this->currency;
    }

    /**
     * @param $data
     * @return $this
     */
    public function setSalesPadData($data)
    {
        $this->_salesPadData = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getInvoices()
    {
        return $this->_salesPadData;
    }

    /**
     * @return array
     */
    public function getShipments()
    {
        return $this->_salesPadData;
    }

    /**
     * @param $data
     * @param $field
     * @param string $default
     * @return string
     */
    protected function _getField($data, $field, $default = '')
    {
        return isset($data[$field]) ? trim($data[$field]) : $default;
    }

    /**
     * @param $data
     * @param $field
     * @param string $default
     * @return string
     */
    public function getFieldRaw($data, $field, $default = '')
    {
        return trim($this->_getField($data, $field, $default));
    }

    /**
     * @param $data
     * @param $field
     * @param string $default
     * @return string
     */
    public function getField($data, $field, $default = '')
    {
        return $this->escapeHtml($this->_getField($data, $field, $default));
    }

    /**
     * @param $data
     * @param $field
     * @param string $default
     * @return string
     */
    public function getFieldAttr($data, $field, $default = '')
    {
        return $this->escapeHtmlAttr($this->_getField($data, $field, $default));
    }

    /**
     * @param $data
     * @param $field
     * @param string $default
     * @return string
     */
    public function getPrice($data, $field, $qty = 1)
    {
        $rawPriceUnitPrice = $qty * floatval(trim($this->_getField($data, 'Unit_Price', '0')));
        $rawPriceExtendedPrice = $qty * floatval(trim($this->_getField($data, $field, '0')));
        if ($rawPriceExtendedPrice < $rawPriceUnitPrice) {
            $rawPrice = $rawPriceExtendedPrice;
        } else {
            $rawPrice = $rawPriceUnitPrice;
        }
        return $this->_getCurrency()->formatPrecision($rawPrice, 2, [], false, false);
    }

    public function getUserFieldRaw($data, $field, $default = '')
    {
        $userFieldData = $data['UserFieldData'] ?? [];
        $userFieldNames = $data['UserFieldNames'] ?? [];
        $userFieldNames = array_flip($userFieldNames);
        if (!isset($userFieldNames[$field])) {
            return $default;
        }

        $idx = $userFieldNames[$field];

        if (!isset($userFieldData[$idx])) {
            return $default;
        }

        return trim($userFieldData[$idx]);
    }

    public function getUserField($data, $field, $default = '')
    {
        return $this->escapeHtml($this->getUserFieldRaw($data, $field, $default));
    }

    public function getProduct($data)
    {
        $sku = $this->_getField($data, 'Item_Number');
        if (!$sku) {
            return '';
        }
        return $this->productHelper->getProduct($sku);
    }

    public function getItemImage($data)
    {
        $product = $this->getProduct($data);
        if (!$product->getId()) {
            return '';
        }
        return $this->productHelper->getProductImage($product);
    }

    /**
     * @param array $shipment
     * @return array
     */
    public function getShipmentTrackings($shipment)
    {
        try {
            $tracks = [];
            foreach ($shipment['items'] as $item) {
                foreach (['xTracking_Num', 'xTracking_Num2'] as $trackField) {
                    $carrier = $this->getUserField($item, 'xCarrier', '');
                    $trackItems = str_replace(' ', '', $this->getUserField($item, $trackField, ''));
                    foreach (explode(',', $trackItems) as $track) {
                        if (!empty($track)) {
                            $tracks[] = [
                                'code' => $track,
                                'url' => $this->getCarrierTrackingUrl($carrier, $track)
                            ];
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            return [];
        }
        return $tracks;
    }

    /**
     * @param $carrier
     * @param $trackingNumber
     * @return array|string|string[]
     */
    public function getCarrierTrackingUrl($carrier, $trackingNumber)
    {
        if (!empty($carrier) && !empty($trackingNumber)) {
            $carriers = $this->config->getCarrierTrackingUrl();
            foreach ($carriers as $key => $carrierTrackingUrl) {
                if (strtolower($carrier) == strtolower($carrierTrackingUrl['carrier'])) {
                    return str_replace('[TRACKING_NUMBER]', $trackingNumber, $carrierTrackingUrl['tracking_url']);
                }
            }
        }
        return '';
    }
}
