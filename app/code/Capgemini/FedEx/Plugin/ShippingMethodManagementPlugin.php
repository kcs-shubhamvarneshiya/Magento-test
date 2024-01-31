<?php

namespace Capgemini\FedEx\Plugin;

use Codeception\Extension;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\EstimateAddressInterface;
use Magento\Quote\Api\Data\ShippingMethodExtensionInterfaceFactory as ExtensionFactory;
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Quote\Api\ShipmentEstimationInterface;
use Magento\Quote\Api\ShippingMethodManagementInterface;

class ShippingMethodManagementPlugin
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    protected $extensionFactory;

    protected $methods = [
        'fedex_STANDARD_OVERNIGHT',
        'fedex_FEDEX_2_DAY',
        'fedex_PRIORITY_OVERNIGHT',
        'STANDARD_OVERNIGHT',
        'FEDEX_2_DAY',
        'PRIORITY_OVERNIGHT'
    ];

    /**
     * ShippingMethodManagementPlugin constructor.
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        ExtensionFactory $extensionFactory
    ) {
        $this->registry = $registry;
        $this->extensionFactory = $extensionFactory;
    }

    public function afterEstimateByExtendedAddress(
        ShipmentEstimationInterface $subject,
        array $result,
        $cartId,
        AddressInterface $address
    ) {
        if (empty($result)) {
            return $result;
        } else {
            return $this->processResult($result);
        }
    }

    public function afterEstimateByAddress(
        ShippingMethodManagementInterface $subject,
        array $result,
        $cartId,
        EstimateAddressInterface $address
    ) {
        if (empty($result)) {
            return $result;
        } else {
            return $this->processResult($result);
        }
    }

    public function afterEstimateByAddressId(
        ShippingMethodManagementInterface $subject,
        array $result,
        $cartId,
        $addressId
    ) {
        if (empty($result)) {
            return $result;
        } else {
            return $this->processResult($result);
        }
    }

    public function afterGetList(
        ShippingMethodManagementInterface $subject,
        array $result,
        $cartId
    ) {
        if (empty($result)) {
            return $result;
        } else {
            return $this->processResult($result);
        }
    }

    protected function isOverWeight()
    {
        return intval($this->registry->registry('cartonOverWeightFlag')) > 0;
    }

    protected function processResult(array $shippingMethods)
    {
        $overweight = $this->isOverWeight();
        /** @var ShippingMethodInterface $shippingMethod */
        foreach ($shippingMethods as $shippingMethod) {
            $extensionAttrs = $shippingMethod->getExtensionAttributes() ?? $this->extensionFactory->create();
            if ($overweight && in_array($shippingMethod->getMethodCode(), $this->methods)) {
                $shippingMethod->setPriceExclTax(0.0)
                    ->setPriceInclTax(0.0);
                $extensionAttrs->setIsOverweight(true);
            } else {
                $extensionAttrs->setIsOverweight(false);
            }
            $shippingMethod->setExtensionAttributes($extensionAttrs);
        }
        return $shippingMethods;
    }
}
