<?php

namespace Capgemini\CustomHeight\Plugin;

use Magento\Sales\Api\Data\OrderItemExtensionFactory;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class OrderRepositoryPlugin
{
    /**
     * @var OrderItemExtensionFactory
     */
    protected $itemExtensionFactory;

    /**
     * OrderRepositoryPlugin constructor.
     * @param OrderItemExtensionFactory $itemExtensionFactory
     */
    public function __construct(
        OrderItemExtensionFactory $itemExtensionFactory
    ) {
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
        return $this->addExtensionAttributesToItems($order);
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

    /**
     * @param OrderInterface $order
     * @return OrderInterface
     */
    private function addExtensionAttributesToItems(OrderInterface $order)
    {
        $orderItems = $order->getItems();
        if (null !== $orderItems) {
            /** @var \Magento\Sales\Api\Data\OrderItemInterface $orderItem */
            foreach ($orderItems as $orderItem) {
                $customHeightPrice = $orderItem->getCustomHeightPrice();
                $customHeightValue = $orderItem->getCustomHeightValue();
                $customHeightCost = $orderItem->getCustomHeightCost();
                $extensionAttributes = $orderItem->getExtensionAttributes();
                $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->itemExtensionFactory->create();
                $extensionAttributes->setCustomHeightPrice($customHeightPrice);
                $extensionAttributes->setCustomHeightValue($customHeightValue);
                $extensionAttributes->setCustomHeightCost($customHeightCost);
                $orderItem->setExtensionAttributes($extensionAttributes);
            }
        }
        return $order;
    }
}
