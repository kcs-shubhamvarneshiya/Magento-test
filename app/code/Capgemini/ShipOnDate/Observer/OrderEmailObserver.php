<?php
/**
 * Capgemini_ShipOnDate
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\ShipOnDate\Observer;

use Capgemini\ShipOnDate\Helper\Date;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;

class OrderEmailObserver implements ObserverInterface
{
    /**
     * Date Formatter
     * @var Date
     */
    protected Date $dateHelper;

    /**
     * Constructor
     *
     * @param Date $dateHelper
     */
    public function __construct(
        Date $dateHelper
    ) {
        $this->dateHelper = $dateHelper;
    }

    /**
     * @param Observer $observer
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {
        $transport = $observer->getEvent()->getTransport();

        /** @var Order $order */
        $order = $transport->getOrder();

        if ($extensionAttributes = $order->getExtensionAttributes()) {
            $shipOnDate = $extensionAttributes->getShipOnDate() ?? $order->getShipOnDate();
            if ($shipOnDate) {
                $shippingDescription = $order->getShippingDescription();
                $shippingTitle = $this->dateHelper->formatShippingDescription($shippingDescription);
                $transport->setData(
                    'shipping_title', $shippingTitle
                );
                $transport->setData(
                    'ship_on_date', $this->dateHelper->formatDate($shipOnDate)
                );
            }
        }
    }
}
