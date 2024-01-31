<?php
/**
 * Capgemini_OrderSplit
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\OrderSplit\Plugin\Sales\Model;

use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Sales\Api\Data\OrderItemExtension;
use Magento\Sales\Api\Data\OrderItemExtensionFactory;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\Data\OrderItemSearchResultInterface;

class OrderItemGetPlugin
{
    /**
     * @var OrderItemExtensionFactory
     */
    protected OrderItemExtensionFactory $orderItemExtensionFactory;

    /**
     * @param OrderItemExtensionFactory $orderItemExtensionFactory
     */
    public function __construct(
        OrderItemExtensionFactory $orderItemExtensionFactory
    ) {
        $this->orderItemExtensionFactory = $orderItemExtensionFactory;
    }

    /**
     * @param OrderItemRepositoryInterface $subject
     * @param OrderItemInterface $orderItem
     *
     * @return OrderItemInterface
     */
    public function afterGet(
        OrderItemRepositoryInterface $subject,
        OrderItemInterface $orderItem
    ): OrderItemInterface {
        $extensionAttributes = $orderItem->getExtensionAttributes();

        /** @var OrderItemExtension $orderItemExtension */
        $orderItemExtension = $extensionAttributes ?: $this->orderItemExtensionFactory->create();

        $orderItemExtension->setDivision(
            $orderItem->getProduct()->getAttributeText('division')
        );

        $orderItem->setExtensionAttributes($orderItemExtension);

        return $orderItem;
    }

    /**
     * @param OrderItemRepositoryInterface $subject
     * @param OrderItemSearchResultInterface $searchResult
     *
     * @return OrderItemSearchResultInterface
     */
    public function afterGetList(
        OrderItemRepositoryInterface $subject,
        OrderItemSearchResultInterface $searchResult
    ): OrderItemSearchResultInterface {
        foreach ($searchResult->getItems() as $item) {
            /** @var OrderItemInterface $item */
            $this->afterGet($subject, $item);
        }

        return $searchResult;
    }
}
