<?php

namespace Capgemini\ServerSideAnalytics\Model;

use Magento\Framework\MessageQueue\PublisherInterface;

class Publisher
{
    const TOPIC_NAME = 'serversideanalytics.purchase_event.send';

    /**
     * @var \Magento\Framework\MessageQueue\PublisherInterface
     */
    private $publisher;

    /**
     * Publisher constructor.
     * @param \Gaiterjones\RabbitMQ\Model\MessageQueues\Product\Publisher $publisher
     */
    public function __construct(PublisherInterface $publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * @param int $orderId
     */
    public function execute(int $orderId)
    {
        $this->publisher->publish(self::TOPIC_NAME, $orderId);
    }
}
