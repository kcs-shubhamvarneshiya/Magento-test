<?php

namespace Lyonscg\SalesPad\Plugin;

use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderItemExtensionFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\Data\OrderInterface;

class OrderRepositoryPlugin
{
    /**
     * @var OrderExtensionFactory
     */
    protected $extensionFactory;

    /**
     * @var OrderItemExtensionFactory
     */
    protected $itemExtensionFactory;

    /**
     * OrderRepositoryPlugin constructor.
     * @param OrderExtensionFactory $extensionFactory
     * @param OrderItemExtensionFactory $itemExtensionFactory
     */
    public function __construct(
        OrderExtensionFactory $extensionFactory,
        OrderItemExtensionFactory $itemExtensionFactory
    ) {
        $this->extensionFactory = $extensionFactory;
        $this->itemExtensionFactory = $itemExtensionFactory;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param $order
     * @param int $id
     * @return OrderInterface
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order)
    {
        $salesDocNum = $order->getSalespadSalesDocNum();
        // note capitalization of Pad, to keep the same as the customer version
        $customerNum = $order->getSalesPadCustomerNum();
        $quoteNum = $order->getSalespadQuoteNum();
        $extensionAttributes = $order->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
        $extensionAttributes->setSalespadSalesDocNum($salesDocNum);
        $extensionAttributes->setSalesPadCustomerNum($customerNum);
        $extensionAttributes->setSalespadQuoteNum($quoteNum);
        $order->setExtensionAttributes($extensionAttributes);
        return $this->addLineNumToOrderItems($order);
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $results
     * @return OrderSearchResultInterface
     */
    public function afterGetList(OrderRepositoryInterface $subject, OrderSearchResultInterface $results)
    {
        foreach ($results->getItems() as $order) {
            $this->afterGet($subject, $order);
        }
        return $results;
    }

    // shouldn't need a beforeSave here because the value is added when it is sent to the api

    /**
     * @param OrderInterface $order
     * @return OrderInterface
     */
    private function addLineNumToOrderItems(OrderInterface $order)
    {
        $orderItems = $order->getItems();
        if (null !== $orderItems) {
            /** @var \Magento\Sales\Api\Data\OrderItemInterface $orderItem */
            foreach ($orderItems as $orderItem) {
                $salesDocNum = $orderItem->getSalespadLineNum();
                $extensionAttributes = $orderItem->getExtensionAttributes();
                $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->itemExtensionFactory->create();
                $extensionAttributes->setSalespadLineNum($salesDocNum);
                $orderItem->setExtensionAttributes($extensionAttributes);
            }
        }
        return $order;
    }
}
