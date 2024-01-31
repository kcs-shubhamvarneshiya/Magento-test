<?php

namespace Lyonscg\SalesPad\Model\Sync;

use Lyonscg\SalesPad\Api\Data\OrderSyncInterface;
use Lyonscg\SalesPad\Model\Api\Order as OrderApi;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Lyonscg\SalesPad\Helper\SyncRegistry;

class Order extends AbstractSync implements OrderSyncInterface
{
    const SYNCED_ENTITY_TYPE = 'order';
    const SYNCED_ENTITY_ID = 'order_id';

    /**
     * @var OrderApi
     */
    protected $orderApi;

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var OrderInterface
     */
    protected $order = null;

    /**
     * Order constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param OrderApi $orderApi
     * @param OrderRepositoryInterface $orderRepository
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param \Magento\Framework\Lock\LockBackendFactory $lockBackendFactory
     * @param array $data
     * @throws \Magento\Framework\Exception\RuntimeException
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        OrderApi $orderApi,
        OrderRepositoryInterface $orderRepository,
        \Magento\Framework\Lock\LockBackendFactory $lockBackendFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->orderApi = $orderApi;
        $this->orderRepository = $orderRepository;
        $this->locker = $lockBackendFactory->create();
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Lyonscg\SalesPad\Model\ResourceModel\Sync\Order::class);
    }

    /**
     * @return int
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * @param $orderId
     * @return $this
     */
    public function setOrderId($orderId)
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * @param $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * @return OrderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getOrder()
    {
        if ($this->order === null) {
            $this->order = $this->orderRepository->get($this->getOrderId());
        }
        return $this->order;
    }


    /**
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function runSync()
    {
        $syncRegKey = SyncRegistry::register($this);
        try {
            if (!$this->orderApi->createOrUpdate($this->getOrder())) {
                $failures = $this->orderApi->getFailures();
                $this->setFailures($failures);
                SyncRegistry::unregister($syncRegKey);
                return false;
            } else {
                // order sent to SalesPad successfully, update payfabric if required
                if (!$this->orderApi->updatePayfabric($this->getOrder())) {
                    $failures = $this->orderApi->getFailures();
                    $this->setFailures($failures);
                    SyncRegistry::unregister($syncRegKey);
                    return false;
                } else {
                    SyncRegistry::unregister($syncRegKey);
                    return true;
                }
            }
        } catch (NoSuchEntityException $e) {
            $this->_logger->debug('Order entity ' . $this->getOrderId() . ' does not exist: ' . $e);
            SyncRegistry::unregister($syncRegKey);
            throw $e;
        } catch (\Exception $e) {
            $this->_logger->debug($e);
            $failures = [];
            if ($this->getFailures()) {
                $failures[] = $this->getFailures();
            }
            $failures[] = $e->getMessage();
            $this->setFailures($failures);
            SyncRegistry::unregister($syncRegKey);
            return false;
        }
    }
}
