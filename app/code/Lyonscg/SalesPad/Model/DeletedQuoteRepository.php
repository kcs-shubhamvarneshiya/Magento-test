<?php

namespace Lyonscg\SalesPad\Model;

use Lyonscg\SalesPad\Api\Data\DeletedQuoteInterface;
use Lyonscg\SalesPad\Api\DeletedQuoteRepositoryInterface;
use Lyonscg\SalesPad\Helper\Quote as QuoteHelper;
use Lyonscg\SalesPad\Model\DeletedQuote;
use Lyonscg\SalesPad\Model\DeletedQuoteFactory;
use Lyonscg\SalesPad\Model\ResourceModel\DeletedQuote as DeletedQuoteResource;
use Lyonscg\SalesPad\Model\ResourceModel\DeletedQuote\CollectionFactory as DeletedQuoteCollectionFactory;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\RequisitionList\Api\Data\RequisitionListInterface;

class DeletedQuoteRepository implements DeletedQuoteRepositoryInterface
{
    /**
     * @var DeletedQuoteFactory
     */
    protected $deletedQuoteFactory;

    /**
     * @var DeletedQuoteCollectionFactory
     */
    protected $deletedQuoteCollectionFactory;

    /**
     * @var DeletedQuoteResource
     */
    protected $deletedQuoteResource;

    /**
     * @var QuoteHelper
     */
    protected $quoteHelper;

    public function __construct(
        DeletedQuoteFactory $deletedQuoteFactory,
        DeletedQuoteCollectionFactory $deletedQuoteCollectionFactory,
        DeletedQuoteResource $deletedQuoteResource,
        QuoteHelper $quoteHelper
    ) {
        $this->deletedQuoteFactory = $deletedQuoteFactory;
        $this->deletedQuoteCollectionFactory = $deletedQuoteCollectionFactory;
        $this->deletedQuoteResource = $deletedQuoteResource;
        $this->quoteHelper = $quoteHelper;
    }

    /**
     * @param $id
     * @return DeletedQuoteInterface|void
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        /** @var DeletedQuote $deletedQuote */
        $deletedQuote = $this->deletedQuoteFactory->create();
        $deletedQuote->load($id);
        if (!$deletedQuote->getId()) {
            throw NoSuchEntityException::singleField($deletedQuote->getIdFieldName(), $id);
        }
        return $deletedQuote;
    }

    /**
     * @param $salesDocNum
     * @return DeletedQuoteInterface|void
     * @throws NoSuchEntityException
     */
    public function getBySalesDocNum($salesDocNum)
    {
        /** @var \Lyonscg\SalesPad\Model\ResourceModel\DeletedQuote\Collection $collection */
        $collection = $this->deletedQuoteCollectionFactory->create();
        $collection->addFieldToFilter(DeletedQuoteInterface::SALES_DOC_NUM, $salesDocNum);
        $deletedQuote = $collection->getFirstItem();
        if (!$deletedQuote->getId()) {
            throw NoSuchEntityException::singleField(DeletedQuoteInterface::SALES_DOC_NUM, $salesDocNum);
        }
        return $deletedQuote;
    }

    /**
     * @param DeletedQuoteInterface $deletedQuote
     * @return DeletedQuoteInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function save(DeletedQuoteInterface $deletedQuote)
    {
        $this->deletedQuoteResource->save($deletedQuote);
        return $deletedQuote;
    }

    /**
     * @param $salesDocNum
     * @return bool
     */
    public function existsBySalesDocNum($salesDocNum)
    {
        try {
            $deletedQuote = $this->getBySalesDocNum($salesDocNum);
            if ($deletedQuote->getId()) {
                return true;
            } else {
                return false;
            }
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }

    /**
     * @param RequisitionListInterface $requisitionList
     * @return false|DeletedQuoteInterface|void
     */
    public function add(RequisitionListInterface $requisitionList)
    {
        $salesDocNum = $this->quoteHelper->getSalesDocNum($requisitionList);
        if (!$salesDocNum) {
            // no sales doc num, so we don't need to create a record to prevent syncing
            return false;
        }
        try {
            $deletedQuote = $this->getBySalesDocNum($salesDocNum);
            if ($deletedQuote->getId()) {
                return $deletedQuote;
            }
        } catch (NoSuchEntityException $e) {
            // ignore
        }

        /** @var DeletedQuoteInterface $deletedQuote */
        $deletedQuote = $this->deletedQuoteFactory->create();
        $deletedQuote->setSalespadSalesDocNum($salesDocNum);
        try {
            return $this->save($deletedQuote);
        } catch (AlreadyExistsException $e) {
            // this shouldn't happen, since we check above
            return false;
        }
    }
}
