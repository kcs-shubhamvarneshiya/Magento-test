<?php

namespace Lyonscg\SalesPad\Model\Sync;

use Lyonscg\SalesPad\Api\Data\CustomerSyncInterface;
use Lyonscg\SalesPad\Helper\SyncRegistry;
use Lyonscg\SalesPad\Model\Api\Customer as CustomerApi;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Customer extends AbstractSync implements CustomerSyncInterface
{
    const SYNCED_ENTITY_TYPE = 'customer';
    const SYNCED_ENTITY_ID = 'customer_id';
    /**
     * @var CustomerApi
     */
    protected $customerApi;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var CustomerInterface
     */
    protected $customer = null;

    /**
     * Customer constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param CustomerApi $customerApi
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param \Magento\Framework\Lock\LockBackendFactory $lockBackendFactory
     * @param array $data
     * @throws \Magento\Framework\Exception\RuntimeException
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        CustomerApi $customerApi,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Lock\LockBackendFactory $lockBackendFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->customerApi = $customerApi;
        // make sure this is singleton
        $this->customerRepository = $customerRepository;
        $this->locker = $lockBackendFactory->create();
    }
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Lyonscg\SalesPad\Model\ResourceModel\Sync\Customer::class);
    }

    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
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
     * @return CustomerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCustomer()
    {
        // maybe use CustomerRegistry here?
        if ($this->customer === null) {
            $this->customer = $this->customerRepository->getById($this->getCustomerId());
        }
        return $this->customer;
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function runSync()
    {
        $syncRegKey = SyncRegistry::register($this);
        try {
            $customer = $this->getCustomer();
            $result = $this->customerApi->createOrUpdate($customer);
            if (!$result) {
                $this->setFailures($this->customerApi->getFailures());
            }
            SyncRegistry::unregister($syncRegKey);
            return $result;
        } catch (NoSuchEntityException $e) {
            $this->_logger->debug('Customer entity ' . $this->getCustomerId() . ' does not exist: ' . $e);
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
