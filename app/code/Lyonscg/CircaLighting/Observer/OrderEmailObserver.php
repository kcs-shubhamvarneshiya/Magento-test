<?php


namespace Lyonscg\CircaLighting\Observer;

use Magento\Framework\Event\ObserverInterface;

class OrderEmailObserver implements ObserverInterface
{
    protected $amastyAttributes = [
        'po_number',
        'project_name'
    ];

    /**
     * @var \Lyonscg\SalesPad\Helper\Order
     */
    protected $orderHelper;

    /**
     * @var \Lyonscg\SalesPad\Helper\Customer
     */
    protected $customerHelper;

    /**
     * OrderEmailObserver constructor.
     * @param \Lyonscg\SalesPad\Helper\Order $orderHelper
     * @param \Lyonscg\SalesPad\Helper\Customer $customerHelper
     */
    public function __construct(
        \Lyonscg\SalesPad\Helper\Order $orderHelper,
        \Lyonscg\SalesPad\Helper\Customer $customerHelper
    ) {
        $this->orderHelper = $orderHelper;
        $this->customerHelper = $customerHelper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $transport = $observer->getEvent()->getTransport();
        /** @var \Magento\Sales\Model\Order $order */
        $order = $transport->getOrder();
        $amastyAttributes = $this->orderHelper->getAmastyOrderAttributeData($order);
        $comments = $amastyAttributes['comments'] ?? '';
        $poNumber = $amastyAttributes['po_number'] ?? '';
        $projectName = $amastyAttributes['project_name'] ?? '';
        $data = [
            'po_number' => $poNumber,
            'customer_number' => false,
            'project_name' => $projectName,
            'order_notes' => $comments
        ];

        $extAttrs = $order->getExtensionAttributes();
        if ($extAttrs) {
            $data['customer_number'] = $extAttrs->getSalesPadCustomerNum();
            $data['salespad_order_number'] = $extAttrs->getSalespadSalesDocNum();
        }
        if (!$data['customer_number']) {
            $data['customer_number'] = $order->getData('sales_pad_customer_num');
        }
        if (!$data['customer_number']) {
            $data['customer_number'] = $this->_getCustomerNumberFromCustomer($order);
        }
        if (!$data['salespad_order_number']) {
            $data['salespad_order_number'] = $order->getData('salespad_sales_doc_num');
        }
        // send increment id if sales doc num is not yet present
        if (!$data['salespad_order_number']) {
            $data['salespad_order_number'] = $order->getIncrementId();
        }

        $amastyAttrs = $this->orderHelper->getAmastyOrderAttributeData($order);
        foreach ($this->amastyAttributes as $amastyAttribute) {
            if (isset($amastyAttrs[$amastyAttribute])) {
                $data[$amastyAttribute] = $amastyAttrs[$amastyAttribute];
            }
        }
        foreach ($data as $key => $value) {
            $transport->setData($key, $value);
        }

        // CLMI-723 - remove 'xxxx-' from payment html
        $paymentHtml = $transport->getData('payment_html');
        if ($paymentHtml) {
            $paymentHtml = preg_replace('/xxxx-/', '', $paymentHtml);
            $transport->setData('payment_html', $paymentHtml);
        }
    }

    protected function _getCustomerNumberFromCustomer(\Magento\Sales\Model\Order $order)
    {
        if (!$order->getCustomerId()) {
            return '';
        }
        return $this->customerHelper->getCustomerNum($order->getCustomerId());
    }
}
