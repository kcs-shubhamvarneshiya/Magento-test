<?php

namespace Capgemini\OrderView\Block;

use Capgemini\CompanyType\Model\Config;
use Capgemini\OrderView\Helper\Data;


class View extends \Magento\Sales\Block\Order\View
{
    protected $_template = 'Capgemini_OrderView::order/view.phtml';

    /**
     * @var
     */
    protected $customerSession;

    /**
     * @var \Magento\Sales\Helper\Reorder
     */
    protected $reorderHelper;

    /**
     * @var \Magento\Framework\Data\Helper\PostHelper
     */
    protected $postHelper;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private $serializer;

    /**
     * @var mixed
     */
    private $carriers;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * View constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Payment\Helper\Data $paymentHelper
     * @param \Magento\Sales\Helper\Reorder $reorderHelper
     * @param \Magento\Framework\Data\Helper\PostHelper $postHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Payment\Helper\Data $paymentHelper,
        \Magento\Sales\Helper\Reorder $reorderHelper,
        \Magento\Framework\Data\Helper\PostHelper $postHelper,
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $registry, $httpContext, $paymentHelper, $data);
        $this->reorderHelper = $reorderHelper;
        $this->postHelper = $postHelper;
        $this->serializer = $serializer;
        $this->helper = $helper;
    }

    public function getOrderInvoices()
    {
        return $this->getOrder()->getSalespadInvoices();
    }

    public function getOrderShipments()
    {
        // shipment info is tied to invoices
        return $this->getOrder()->getSalespadInvoices();
    }

    public function hasInvoices()
    {
        return count($this->getOrderInvoices()) > 0;
    }

    public function hasShipments()
    {
        if ($this->helper->getCustomerType() != Config::WHOLESALE) {
            // items shipped are the items that were invoiced
            $shipments = $this->getOrderShipments();
            foreach ($shipments as $shipment) {
                if (isset($shipment['items']) && count($shipment['items'])) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getRealOrder()
    {
        return $this->getOrder()->getData('_real_order');
    }

    public function canReorder()
    {
        if ($this->getRealOrder() && $this->getRealOrder()->getId()) {
            return $this->reorderHelper->canReorder($this->getRealOrder()->getId());
        } else {
            return false;
        }
    }

    public function getReorderData()
    {
        if ($this->canReorder()) {
            return $this->postHelper->getPostData($this->_getReorderUrl($this->getRealOrder()));
        } else {
            return '';
        }
    }

    public function getOrderItems()
    {
        if ($this->helper->getCustomerType() == Config::WHOLESALE) {
            return false;
        } else {
            $this->getSalespadItems();
        }
    }

    public function getSalespadItems()
    {
        try {
            return $this->collectSalespadItems();
        } catch (\Exception $e) {
            $this->_logger->error($e);
        }
        return false;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function collectSalespadItems()
    {
        $magentoOrder = $this->getOrder();

        if (is_array($magentoOrder->getSalespadItems())) {
            return $magentoOrder->setSalespadItems();
        }

        $items = [];
        $salespadOrder = $magentoOrder->getSalespadData();

        foreach ($salespadOrder['items']['Items'] as $item) {
            $item = $this->normalizeSalespadData($item);

            $item['invoices'] = [];
            $item['tracking_numbers'] = [];
            $item['invoiced'] = 0;
            $item['shipped'] = 0;

            $items[$item['Item_Number']] = $item;

        }

        if (array_key_exists('not_shipped', $salespadOrder)) {
            foreach ($salespadOrder['not_shipped']['Items'] as $item) {
                $item = $this->normalizeSalespadData($item);
                if (array_key_exists($item['Item_Number'], $items)) {
                    foreach (['xVC_Current_Availability', 'xVC_Status'] as $key) {
                        $items[$item['Item_Number']][$key] = $item[$key];
                    }
                }
            }
        }

        foreach ($salespadOrder['invoices'] as $invoice) {
            $invoice = $this->normalizeSalespadData($invoice);
            foreach ($invoice['items'] as $item) {
                $item = $this->normalizeSalespadData($item);
                $sku = $item['Item_Number'];
                if (array_key_exists($item['Item_Number'], $items)) {
                    $items[$sku]['invoices'][] = $invoice;
                    $items[$sku]['invoiced'] += $item['Quantity'];
                    $items[$sku]['shipped'] += $item['Quantity'];

                    foreach (['xTracking_Num', 'xTracking_Num2'] as $key) {
                        $track = $item[$key];
                        if (!empty($track)) {
                            $url = $this->getCarrierTrackingUrl($item['xCarrier'], $track);
                            $items[$sku]['tracking_numbers'][] = !empty($url) ? "<a href=\"$url\">$track</a>" : $track;
                        }
                    }
                    $items[$sku]['tracking_numbers'] = array_unique($items[$sku]['tracking_numbers']);
                }
            }
        }

        foreach ($magentoOrder->getItems() as $item) {
            if (array_key_exists($item->getSku(), $items)) {
                $items[$item->getSku()]['magento_item'] = $item;
                $salespadItem = $items[$item->getSku()];
                $item->setSalespadData($items[$item->getSku()]);

                $shipped = array_key_exists('shipped', $salespadItem) ? $items[$item->getSku()]['shipped'] : 0;
                if ($shipped > $item->getQtyShipped()) {
                    $item->setQtyShipped($shipped);
                }

                $estimatedShipping = strtotime($salespadItem['xVC_Current_Availability'] ?? '');
                if ($estimatedShipping > 0) {
                    $item->setEstimatedShipping(date('m.d.Y', $estimatedShipping));
                }

                if ($item->getQtyShipped() > 0 && $item->getQtyOrdered() > $item->getQtyShipped()) {
                    $item->setStatus('Partial Ship');
                } elseif ($item->getQtyOrdered() == $item->getQtyShipped()) {
                    $item->setStatus('Shipped');
                }

                $item->setTracking(implode(',', $salespadItem['tracking_numbers']));
            }
        }

        $magentoOrder->setSalespadItems($items);

        return $items;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function collectPiOrderItems()
    {
        $magentoOrder = $this->getOrder();



        $items = [];
        $salespadOrder = $magentoOrder->getSalespadData();

        foreach ($salespadOrder['items']['Items'] as $item) {
            $item = $this->normalizeSalespadData($item);

            $item['invoices'] = [];
            $item['tracking_numbers'] = [];
            $item['invoiced'] = 0;
            $item['shipped'] = 0;

            $items[$item['Item_Number']] = $item;

        }

        if (array_key_exists('not_shipped', $salespadOrder)) {
            foreach ($salespadOrder['not_shipped']['Items'] as $item) {
                $item = $this->normalizeSalespadData($item);
                if (array_key_exists($item['Item_Number'], $items)) {
                    foreach (['xVC_Current_Availability', 'xVC_Status'] as $key) {
                        $items[$item['Item_Number']][$key] = $item[$key];
                    }
                }
            }
        }

        foreach ($salespadOrder['invoices'] as $invoice) {
            $invoice = $this->normalizeSalespadData($invoice);
            foreach ($invoice['items'] as $item) {
                $item = $this->normalizeSalespadData($item);
                $sku = $item['Item_Number'];
                if (array_key_exists($item['Item_Number'], $items)) {
                    $items[$sku]['invoices'][] = $invoice;
                    $items[$sku]['invoiced'] += $item['Quantity'];
                    $items[$sku]['shipped'] += $item['Quantity'];

                    foreach (['xTracking_Num', 'xTracking_Num2'] as $key) {
                        $track = $item[$key];
                        if (!empty($track)) {
                            $url = $this->getCarrierTrackingUrl($item['xCarrier'], $track);
                            $items[$sku]['tracking_numbers'][] = !empty($url) ? "<a href=\"$url\">$track</a>" : $track;
                        }
                    }
                    $items[$sku]['tracking_numbers'] = array_unique($items[$sku]['tracking_numbers']);
                }
            }
        }

        foreach ($magentoOrder->getItems() as $item) {
            if (array_key_exists($item->getSku(), $items)) {
                $items[$item->getSku()]['magento_item'] = $item;
                $salespadItem = $items[$item->getSku()];
                $item->setSalespadData($items[$item->getSku()]);

                $shipped = array_key_exists('shipped', $salespadItem) ? $items[$item->getSku()]['shipped'] : 0;
                if ($shipped > $item->getQtyShipped()) {
                    $item->setQtyShipped($shipped);
                }

                $estimatedShipping = strtotime($salespadItem['xVC_Current_Availability'] ?? '');
                if ($estimatedShipping > 0) {
                    $item->setEstimatedShipping(date('m.d.Y', $estimatedShipping));
                }

                if ($item->getQtyShipped() > 0 && $item->getQtyOrdered() > $item->getQtyShipped()) {
                    $item->setStatus('Partial Ship');
                } elseif ($item->getQtyOrdered() == $item->getQtyShipped()) {
                    $item->setStatus('Shipped');
                }

                $item->setTracking(implode(',', $salespadItem['tracking_numbers']));
            }
        }

        $magentoOrder->setSalespadItems($items);

        return $items;
    }

    public function getCarrierTrackingUrl($carrier, $trackingNumber)
    {
        if (empty($this->carriers)) {
            $carriers = $this->_scopeConfig->getValue('lyonscg_salespad/tracking/carrier_tracking_url');
            $this->carriers = $this->serializer->unserialize($carriers);
        }

        if (!empty($carrier) && !empty($trackingNumber)) {
            foreach ($this->carriers as $carrierTrackingUrl) {
                if (strtolower($carrier) == strtolower($carrierTrackingUrl['carrier'])) {
                    return str_replace('[TRACKING_NUMBER]', $trackingNumber, $carrierTrackingUrl['tracking_url']);
                }
            }
        }
        return '';
    }

    public function normalizeSalespadData(&$data)
    {
        if (array_key_exists('UserFieldData', $data) && array_key_exists('UserFieldNames', $data)) {
            $data = array_merge($data, array_combine($data['UserFieldNames'], $data['UserFieldData']));
        }
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = trim($value);
            }
        }
        return $data;
    }

    /**
     * Get reorder URL
     *
     * @param object $order
     * @return string
     */
    protected function _getReorderUrl($order)
    {
        return $this->getUrl('orderview/orders/reorder', ['order_id' => $order->getId()]);
    }
}
