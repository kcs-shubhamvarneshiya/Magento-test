<?php

namespace Capgemini\ServerSideAnalytics\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Capgemini\ServerSideAnalytics\Model\GAClient;
use Magento\Framework\Event\ManagerInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order\Item;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * @var GAClient
     */
    private $gaclient;
    /**
     * @var ManagerInterface
     */
    private $event;

    public function __construct(
        Context $context,
        GAClient         $gaclient,
        ManagerInterface $event
    ) {
        parent::__construct($context);
        $this->gaclient = $gaclient;
        $this->event = $event;
    }

    public function sendPurchaseEvent(OrderInterface $order)
    {
        if (!$this->scopeConfig->getValue(GAClient::GOOGLE_ANALYTICS_SERVERSIDE_ENABLED, ScopeInterface::SCOPE_STORE)) {
            return;
        }

        $ua = $this->scopeConfig->getValue(GAClient::GOOGLE_ANALYTICS_SERVERSIDE_UA, ScopeInterface::SCOPE_STORE);

        if (!$ua) {
            $this->_logger->info('No Google Analytics account number has been found in the ServerSideAnalytics configuration.');
            return;
        }

        $uas = explode(',', $ua);
        $uas = array_filter($uas);
        $uas = array_map('trim', $uas);

        $products = [];
        foreach ($order->getAllItems() as $item) {
            if ($item->getHasChildren() || $item->isDeleted()) {
                continue;
            }

            $product = new \Magento\Framework\DataObject([
                'id' => $item->getSku(),
                'sku' => $item->getSku(),
                'name' => $item->getName(),
                'price' => $this->getPaidProductPrice($item),
                'quantity' => ($qtyItem = $item->getParentItem()) ? $qtyItem->getQty() : $item->getQty(),
                'position' => $item->getId()
            ]);
            $this->event->dispatch('capgemini_serversideanalytics_product_item_transport_object',
                ['product' => $product, 'item' => $item]);
            $products[] = $product;

        }

        $trackingDataObject = new \Magento\Framework\DataObject([
            'client_id' => $order->getData('ga_user_id'),
            'ip_override' => $order->getRemoteIp(),
            'document_path' => '/checkout/onepage/success/',
            'event_id' => 'order.' . $order->getIncrementId(),
        ]);

        try {
            /** @var GAClient $client */
            $client = $this->gaclient;

            $client->setTransactionData(
                new \Magento\Framework\DataObject(
                    [
                        'transaction_id' => $order->getIncrementId(),
                        'affiliation' => $order->getStoreName(),
                        'revenue' => $order->getBaseGrandTotal(),
                        'salesRevenue' => $order->getBaseTotalDue(),
                        'tax' => $order->getTaxAmount(),
                        'shipping' => ($this->getPaidShippingCosts($order) ?? 0),
                        'coupon' => $order->getCouponCode(),
                        'coupon_code' => $order->getCouponCode(),
                        'couponAmount' => $order->getDiscountAmount(),
                        'shippingOption' => $order->getShippingMethod(),
                        'orderQuantity' => $order->getTotalQtyOrdered(),
                    ]
                )
            );

            $client->addProducts($products);
        } catch (\Exception $e) {
            $this->_logger->info($e);
            return;
        }


        foreach ($uas as $ua) {
            try {
                $trackingDataObject->setData('tracking_id', $ua);
                $client->setTrackingData($trackingDataObject);
                $this->event->dispatch('capgemini_serversideanalytics_tracking_data_transport_object',
                    ['tracking_data_object' => $trackingDataObject]);
                $client->firePurchaseEvent();
            } catch (\Exception $e) {
                $this->_logger->info($e);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getIsUseQueue()
    {
        return $this->scopeConfig->getValue(
            GAClient::GOOGLE_ANALYTICS_SERVERSIDE_USE_QUEUE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param string $message
     * @param array $context
     * @return void
     */
    public function logError(string $message, array $context = [])
    {
        $this->_logger->error($message, $context);
    }

    /**
     * Get the actual price the customer also saw in it's cart.
     *
     * @param Item $orderItem
     *
     * @return float
     */
    private function getPaidProductPrice(Item $orderItem)
    {
        return $this->scopeConfig->getValue('tax/display/type') == \Magento\Tax\Model\Config::DISPLAY_TYPE_EXCLUDING_TAX
            ? $orderItem->getPrice()
            : $orderItem->getPriceInclTax();
    }

    /**
     * @param OrderInterface $order
     *
     * @return float
     */
    private function getPaidShippingCosts(OrderInterface $order)
    {
        return $this->scopeConfig->getValue('tax/display/type') == \Magento\Tax\Model\Config::DISPLAY_TYPE_EXCLUDING_TAX
            ? $order->getShippingAmount()
            : $order->getShippingInclTax();
    }
}
