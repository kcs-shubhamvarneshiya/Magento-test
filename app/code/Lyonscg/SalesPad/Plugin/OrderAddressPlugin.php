<?php

namespace Lyonscg\SalesPad\Plugin;

use Magento\Sales\Api\Data\OrderAddressExtensionFactory;
use Magento\Sales\Model\Order\Address as OrderAddress;

class OrderAddressPlugin
{
    /**
     * @var OrderAddressExtensionFactory
     */
    protected $orderAddressExtensionFactory;

    /**
     * OrderAddressPlugin constructor.
     * @param OrderAddressExtensionFactory $orderAddressExtensionFactory
     */
    public function __construct(
        OrderAddressExtensionFactory $orderAddressExtensionFactory
    ) {
        $this->orderAddressExtensionFactory = $orderAddressExtensionFactory;
    }

    /**
     * @param OrderAddress $subject
     */
    public function afterAfterLoad(OrderAddress $subject)
    {
        $this->_addExtensionAttributes($subject);
    }

    /**
     * @param OrderAddress $orderAddress
     */
    protected function _addExtensionAttributes(OrderAddress $orderAddress)
    {
        $extensionAttributes = $orderAddress->getExtensionAttributes();
        if ($extensionAttributes === null) {
            $extensionAttributes = $this->orderAddressExtensionFactory->create();
        }
        $extensionAttributes->setSalespadAddressCode($orderAddress->getSalespadAddressCode());
        $orderAddress->setExtensionAttributes($extensionAttributes);
    }
}
