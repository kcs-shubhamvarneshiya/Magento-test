<?php
declare(strict_types=1);

namespace Rysun\ProductDocument\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ProductDocumentRepositoryInterface
{

    /**
     * Save ProductDocument
     * @param \Rysun\ProductDocument\Api\Data\ProductDocumentInterface $productDocument
     * @return \Rysun\ProductDocument\Api\Data\ProductDocumentInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Rysun\ProductDocument\Api\Data\ProductDocumentInterface $productDocument
    );

    /**
     * Retrieve ProductDocument
     * @param string $productdocumentId
     * @return \Rysun\ProductDocument\Api\Data\ProductDocumentInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($productdocumentId);

    /**
     * Retrieve ProductDocument matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Rysun\ProductDocument\Api\Data\ProductDocumentSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete ProductDocument
     * @param \Rysun\ProductDocument\Api\Data\ProductDocumentInterface $productDocument
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Rysun\ProductDocument\Api\Data\ProductDocumentInterface $productDocument
    );

    /**
     * Delete ProductDocument by ID
     * @param string $productdocumentId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($productdocumentId);
}

