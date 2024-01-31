<?php

namespace Lyonscg\SalesPad\Model\Sync;

use Lyonscg\SalesPad\Api\Data\QuoteItemSyncInterface;
use Lyonscg\SalesPad\Helper\SyncRegistry;
use Lyonscg\SalesPad\Model\Api\Quote as QuoteApi;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\RequisitionList\Api\Data\RequisitionListItemInterface;
use Magento\RequisitionList\Model\RequisitionList\Items;

class QuoteItem extends AbstractSync implements QuoteItemSyncInterface
{
    const SYNCED_ENTITY_TYPE = 'quote_item';
    const SYNCED_ENTITY_ID = 'item_id';

    /**
     * @var QuoteApi
     */
    protected $quoteApi;
    /**
     * @var Items
     */
    protected $itemRepository;
    /**
     * @var RequisitionListItemInterface
     */
    protected $item = null;

    /**
     * QuoteItem constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param QuoteApi $quoteApi
     * @param Items $itemRepository
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
        Items $itemRepository,
        \Magento\Framework\Lock\LockBackendFactory $lockBackendFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->quoteApi = $quoteApi;
        $this->itemRepository = $itemRepository;
        $this->locker = $lockBackendFactory->create();
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Lyonscg\SalesPad\Model\ResourceModel\Sync\QuoteItem::class);
    }

    /**
     * @return int
     */
    public function getItemId()
    {
        return $this->getData(self::ITEM_ID);
    }

    /**
     * @param $itemId
     * @return QuoteItem
     */
    public function setItemId($itemId)
    {
        return $this->setData(self::ITEM_ID, $itemId);
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
     * @return QuoteItem
     */
    public function setQuoteId($quoteId)
    {
        return $this->setData(self::QUOTE_ID, $quoteId);
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
     * @return QuoteItem
     */
    public function setSalesDocNum($salesDocNum)
    {
        return $this->setData(self::SALES_DOC_NUM, $salesDocNum);
    }

    /**
     * @return mixed
     */
    public function getLineNum()
    {
        return $this->getData(self::LINE_NUM);
    }

    /**
     * @param $lineNum
     * @return QuoteItem
     */
    public function setLineNum($lineNum)
    {
        return $this->setData(self::LINE_NUM, $lineNum);
    }

    /**
     * @return mixed
     */
    public function getComponentSeqNum()
    {
        return $this->getData(self::COMPONENT_SEQ_NUM);
    }

    /**
     * @param $componentSeqNum
     * @return QuoteItem
     */
    public function setComponentSeqNum($componentSeqNum)
    {
        return $this->setData(self::COMPONENT_SEQ_NUM, $componentSeqNum);
    }

    /**
     * @return RequisitionListItemInterface
     * @throws NoSuchEntityException
     */
    public function getItem()
    {
        if ($this->item === null) {
            $this->item = $this->itemRepository->get($this->getItemId());
        }
        return $this->item;
    }

    /**
     * @return false
     * @throws NoSuchEntityException|\InvalidArgumentException
     */
    protected function runSync()
    {
        $syncRegKey = SyncRegistry::register($this);
        try {
            switch ($this->getSyncAction()) {
                case self::ACTION_SEND:
                    $salesDocNum = $this->getSalesDocNum() ?? null;
                    $result =  $this->quoteApi->createOrUpdateItem($this->getItem(), $salesDocNum);
                    SyncRegistry::unregister($syncRegKey);
                    return $result;
                case self::ACTION_DELETE:
                    $result = $this->quoteApi->deleteItem(
                        $this->getSalesDocNum(),
                        $this->getLineNum(),
                        $this->getComponentSeqNum()
                    );
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
            SyncRegistry::unregister($syncRegKey);
            return false;
        }
    }
}
