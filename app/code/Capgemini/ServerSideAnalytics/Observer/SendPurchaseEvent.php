<?php

namespace Capgemini\ServerSideAnalytics\Observer;

use Capgemini\ServerSideAnalytics\Model\Publisher;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Capgemini\ServerSideAnalytics\Helper\Data as ModuleHelper;

class SendPurchaseEvent implements ObserverInterface
{
    /**
     * @var Publisher
     */
    private $publisher;
    /**
     * @var ModuleHelper
     */
    private $moduleHelper;

    public function __construct(Publisher $publisher, ModuleHelper $moduleHelper) {
        $this->publisher = $publisher;
        $this->moduleHelper = $moduleHelper;
    }

    /**
     * @param $observer
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();

        if (!$order->getData('ga_user_id')) {
            return;
        }

        if ($this->moduleHelper->getIsUseQueue()) {
            try {
                $this->publisher->execute($order->getId());
            } catch (\Exception $e) {
                $this->moduleHelper->logError(
                    'Capgemini_ServerSideAnalytics: ' . $e->getMessage(),
                    ['Increment ID' => $order->getIncrementId()]
                );
            }
        } else {
            $this->moduleHelper->sendPurchaseEvent($order);
        }
    }
}
