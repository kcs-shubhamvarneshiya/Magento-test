<?php

namespace Lyonscg\SalesPad\Observer;

use Lyonscg\SalesPad\Plugin\OrderAddressPlugin;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderAddressExtensionFactory;

class OrderAddressCollectionLoadAfter extends OrderAddressPlugin implements ObserverInterface
{
    /**
     * OrderAddressCollectionLoadAfter constructor.
     * @param OrderAddressExtensionFactory $orderAddressExtensionFactory
     */
    public function __construct(
        OrderAddressExtensionFactory $orderAddressExtensionFactory
    ) {
        parent::__construct($orderAddressExtensionFactory);
    }

    /**
     * @param EventObserver $observer
     */
    public function execute(EventObserver $observer)
    {
        $collection = $observer->getEvent()->getOrderAddressCollection();
        foreach ($collection->getItems() as $orderAddress) {
            $this->_addExtensionAttributes($orderAddress);
        }
    }
}
