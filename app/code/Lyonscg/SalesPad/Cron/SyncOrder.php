<?php

namespace Lyonscg\SalesPad\Cron;

use Lyonscg\SalesPad\Api\Data\OrderSyncInterface;
use Lyonscg\SalesPad\Api\SyncRepositoryInterface;
use Lyonscg\SalesPad\Helper\Email as EmailHelper;
use Lyonscg\SalesPad\Model\Api\Customer as CustomerApi;
use Lyonscg\SalesPad\Model\Api\CustomerAddr as CustomerAddrApi;
use Lyonscg\SalesPad\Model\Api\Order as OrderApi;
use Lyonscg\SalesPad\Model\Api\Logger;
use Lyonscg\SalesPad\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;

class SyncOrder extends AbstractSync
{
    /**
     * @var OrderApi
     */
    protected $orderApi;

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * SyncOrder constructor.
     * @param Config $config
     * @param SyncRepositoryInterface $syncRepository
     * @param EmailHelper $emailHelper
     * @param Logger $logger
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param OrderApi $orderApi
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        Config $config,
        SyncRepositoryInterface $syncRepository,
        EmailHelper $emailHelper,
        Logger $logger,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        OrderApi $orderApi,
        OrderRepositoryInterface $orderRepository
    ) {
        parent::__construct(
            $config,
            $syncRepository,
            $emailHelper,
            $logger,
            $storeManager,
            $scopeConfig
        );

        $this->orderApi = $orderApi;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Process synchronization of orders
     * belonging to the current store.
     *
     * @param int|null $storeId
     */
    protected function processSync(int $storeId = null)
    {
        if (!$this->config->getOrderSyncEnabled()) {

            return;
        }

        $limit = intval($this->config->getOrderSyncLimit());
        $ordersToSync = $this->syncRepository->getOrdersToSync($limit, $storeId);

        /** @var OrderSyncInterface $orderSync */
        foreach ($ordersToSync as $orderSync) {
            if (!$orderSync->canSync()) {

                continue;
            }

            $failed = false;
            try {
                if (!$orderSync->sync()) {
                    $failed = true;
                }
            } catch (NoSuchEntityException $e) {
                $this->logger->debug(__('Order %1 does not exist', $orderSync->getOrderId()));
                $this->syncRepository->deleteOrderEntry($orderSync);
                continue;
            } catch (\Exception $e) {
                $this->logger->debug($e);
                $failed = true;
            }

            if ($failed) {
                $orderSync->setSyncAttempts($orderSync->getSyncAttempts() + 1);
                // send email on first error
                if (intval($orderSync->getSyncAttempts()) === 1) {
                    try {
                        $this->sendErrorEmail($orderSync);
                    } catch (\Throwable $e) {
                        $this->logger->debug($e->getMessage() . "\n\n" . $e->getTraceAsString());
                    }
                }
                $this->syncRepository->saveOrderEntry($orderSync);
            } else {
                $this->syncRepository->deleteOrderEntry($orderSync);
            }
        }
    }

    protected function sendErrorEmail(OrderSyncInterface $orderSync)
    {
        $this->emailHelper->sendOrderErrorEmail($orderSync);
    }
}
