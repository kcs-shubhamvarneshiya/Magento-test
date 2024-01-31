<?php

namespace Lyonscg\SalesPad\Api;

use Lyonscg\SalesPad\Api\Data\DeletedQuoteInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\RequisitionList\Api\Data\RequisitionListInterface;

interface DeletedQuoteRepositoryInterface
{
    /**
     * @param $id
     * @return DeletedQuoteInterface
     * @throws NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param $salesDocNum
     * @return DeletedQuoteInterface
     * @throws NoSuchEntityException
     */
    public function getBySalesDocNum($salesDocNum);

    /**
     * @param RequisitionListInterface $requisitionList
     * @return DeletedQuoteInterface|false
     */
    public function add(RequisitionListInterface $requisitionList);

    /**
     * @param DeletedQuoteInterface $deletedQuote
     * @return DeletedQuoteInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function save(DeletedQuoteInterface $deletedQuote);

    /**
     * @param $salesDocNum
     * @return boolean
     */
    public function existsBySalesDocNum($salesDocNum);
}
