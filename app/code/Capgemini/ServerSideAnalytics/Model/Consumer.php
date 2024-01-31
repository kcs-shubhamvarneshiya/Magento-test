<?php

namespace Capgemini\ServerSideAnalytics\Model;

use Magento\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;
use Capgemini\ServerSideAnalytics\Helper\Data as ModuleHelper;

class Consumer
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var ModuleHelper
     */
    private $moduleHelper;

    public function __construct(
        LoggerInterface            $logger,
        OrderRepositoryInterface $orderRepository,
        ModuleHelper $moduleHelper
    ) {
        $this->logger = $logger;
        $this->orderRepository = $orderRepository;
        $this->moduleHelper = $moduleHelper;
    }

    public function process(int $orderId)
    {
        try{
            $order = $this->orderRepository->get($orderId);
            $this->moduleHelper->sendPurchaseEvent($order);
        }catch (\Exception $e){
            $this->logger->critical($e->getMessage());
        }
    }
}
