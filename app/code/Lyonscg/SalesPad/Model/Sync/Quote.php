<?php

namespace Lyonscg\SalesPad\Model\Sync;

use Lyonscg\SalesPad\Api\Data\QuoteSyncInterface;
use Lyonscg\SalesPad\Helper\SyncRegistry;
use Lyonscg\SalesPad\Model\Api\Quote as QuoteApi;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\RequisitionList\Api\Data\RequisitionListInterface;
use Magento\RequisitionList\Api\RequisitionListRepositoryInterface;

class Quote extends AbstractSync implements QuoteSyncInterface
{
    const SYNCED_ENTITY_TYPE = 'quote';
    const SYNCED_ENTITY_ID = 'quote_id';

    /**
     * @var QuoteApi
     */
    protected $quoteApi;
    /**
     * @var RequisitionListRepositoryInterface
     */
    protected $requisitionListRepository;

    /**
     * @var RequisitionListInterface
     */
    protected $requisitionList = null;

    /**
     * @var \Lyonscg\SalesPad\Helper\Quote
     */
    protected $quoteHelper;

    /**
     * Quote constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param QuoteApi $quoteApi
     * @param RequisitionListRepositoryInterface $requisitionListRepository
     * @param \Lyonscg\SalesPad\Helper\Quote $quoteHelper
     * @param \Magento\Framework\Lock\LockBackendFactory $lockBackendFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     * @throws \Magento\Framework\Exception\RuntimeException
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        QuoteApi $quoteApi,
        RequisitionListRepositoryInterface $requisitionListRepository,
        \Lyonscg\SalesPad\Helper\Quote $quoteHelper,
        \Magento\Framework\Lock\LockBackendFactory $lockBackendFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->quoteApi = $quoteApi;
        $this->requisitionListRepository = $requisitionListRepository;
        $this->quoteHelper = $quoteHelper;
        $this->locker = $lockBackendFactory->create();
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Lyonscg\SalesPad\Model\ResourceModel\Sync\Quote::class);
    }

    /**
     * @return int
     */
    public function getQuoteId()
    {
        return $this->getData(self::QUOTE_ID);
    }

    /**
     * @param $quoteId
     * @return Quote
     */
    public function setQuoteId($quoteId)
    {
        return $this->setData(self::QUOTE_ID, $quoteId);
    }

    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @param $customerId
     * @return Quote
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * @return mixed
     */
    public function getSalesDocNum()
    {
        return $this->getData(self::SALES_DOC_NUM);
    }

    /**
     * @param $salesDocNum
     * @return Quote
     */
    public function setSalesDocNum($salesDocNum)
    {
        return $this->setData(self::SALES_DOC_NUM, $salesDocNum);
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
     * @return RequisitionListInterface
     * @throws NoSuchEntityException
     */
    public function getRequisitionList()
    {
        if ($this->requisitionList === null) {
            $this->requisitionList = $this->requisitionListRepository->get($this->getQuoteId());
        }
        return $this->requisitionList;
    }

    /**
     * @return bool
     * @throws NoSuchEntityException|\InvalidArgumentException
     */
    protected function runSync()
    {
        $syncRegKey = SyncRegistry::register($this);
        try {
            switch ($this->getSyncAction()) {
                case self::ACTION_SEND:
                    $result = $this->quoteApi->createOrUpdate($this->getRequisitionList());
                    if (!$result) {
                        $failures = $this->quoteApi->getFailures();
                        $this->setFailures($failures);
                    }
                    SyncRegistry::unregister($syncRegKey);
                    return $result;
                case self::ACTION_DELETE:
                    $result = $this->quoteApi->delete($this->getSalesDocNum());
                    if (!$result) {
                        $failures = $this->quoteApi->getFailures();
                        $this->setFailures($failures);
                    }
                    SyncRegistry::unregister($syncRegKey);
                    return $result;
                default:
                    SyncRegistry::unregister($syncRegKey);
                    throw new \InvalidArgumentException(
                        sprintf('Invalid sync action: "%s"', strval($this->getSyncAction()))
                    );
            }
        } catch (NoSuchEntityException $e) {
            $this->_logger->debug('Requisition list entity ' . $this->getQuoteId() . ' does not exist: ' . $e);
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
