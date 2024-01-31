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

use Capgemini\OrderSplit\Api\Data\CustomPoNumberInterfaceFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Sales\Api\Data\OrderExtension;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\ResourceModel\Order\Collection;

class OrderGetPlugin
{
    /**
     * @var OrderExtensionFactory
     */
    protected OrderExtensionFactory $orderExtensionFactory;
    protected Json $serializer;
    protected CustomPoNumberInterfaceFactory $poNumberFactory;
    protected \Capgemini\OrderSplit\Api\Data\CustomPromoCodeInterfaceFactory $promoCodeFactory;

    /***
     * @param OrderExtensionFactory $orderExtensionFactory
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        Json $serializer,
        CustomPoNumberInterfaceFactory $poNumberFactory,
        \Capgemini\OrderSplit\Api\Data\CustomPromoCodeInterfaceFactory $promoCodeFactory
    ) {
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->serializer = $serializer;
        $this->poNumberFactory = $poNumberFactory;
        $this->promoCodeFactory = $promoCodeFactory;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $resultOrder
     * @return OrderInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(
        OrderRepositoryInterface $subject,
        OrderInterface $resultOrder
    ): OrderInterface {
        $extensionAttributes = $resultOrder->getExtensionAttributes();

        /** @var OrderExtension $orderExtension */
        $orderExtension = $extensionAttributes ?: $this->orderExtensionFactory->create();

        $orderExtension->setCustomPoNumbers($this->deserializeAttribute($resultOrder, 'custom_po_numbers'));
        $orderExtension->setCustomPromoCodes($this->deserializeAttribute($resultOrder, 'custom_promo_codes'));
        $orderExtension->setDivision($this->getDivisionAttributes($resultOrder));

        $resultOrder->setExtensionAttributes($orderExtension);

        return $resultOrder;
    }

    /**
     * Deserialize attribute value from JSON
     *
     * @param OrderInterface $order
     * @param $attribute
     * @return array
     */
    protected function deserializeAttribute(OrderInterface $order, $attribute): array
    {
        $result = [];
        $data = $order->getData($attribute);
        if ($data) {
            try {
                $dataArray = $this->serializer->unserialize($data);
                if (is_array($dataArray)) {
                    foreach ($dataArray as $itemData) {
                        if ($attribute == 'custom_po_numbers') {
                            $item = $this->poNumberFactory->create();
                            $item->setData($itemData);
                        } elseif ($attribute == 'custom_promo_codes') {
                            $item = $this->poNumberFactory->create($itemData);
                            $item->setData($itemData);
                        } else {
                            $item = $itemData;
                        }
                        $result[] = $item;
                    }
                }
            } catch (\Exception $e) {
                $result = [];
            }
        }
        return $result;
    }

    /**
     * @param OrderInterface $order
     * @return string
     */
    private function getDivisionAttributes(OrderInterface $order): string
    {
        $divisionAttributes = [];

        foreach ($order->getItems() as $item) {
            $divisionValueText = $item->getProduct()->getAttributeText('division');
            if ($divisionValueText) {
                $divisionAttributes[] = $divisionValueText;
            }
        }

        return implode(',', $divisionAttributes);
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param Collection $resultOrder
     * @return Collection
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        Collection $resultOrder
    ): Collection {
        foreach ($resultOrder->getItems() as $order) {
            /** @var OrderInterface $order */
            $this->afterGet($subject, $order);
        }
        return $resultOrder;
    }
}
