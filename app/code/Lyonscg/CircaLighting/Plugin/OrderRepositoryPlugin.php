<?php

namespace Lyonscg\CircaLighting\Plugin;

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
                $sidemark = $orderItem->getSidemark();
                $comments = $orderItem->getCommentsLineItem();
                $extensionAttributes = $orderItem->getExtensionAttributes();
                $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->itemExtensionFactory->create();
                $extensionAttributes->setSidemark($sidemark);
                $extensionAttributes->setCommentsLineItem($comments);
                $orderItem->setExtensionAttributes($extensionAttributes);
            }
        }
        return $order;
    }
}
