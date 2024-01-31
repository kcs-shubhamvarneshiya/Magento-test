<?php
declare(strict_types=1);

namespace Rysun\ProductDocument\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ProductDocumentLinkRepositoryInterface
{

    /**
     * Save ProductDocumentLink
     * @param \Rysun\ProductDocument\Api\Data\ProductDocumentLinkInterface $productDocumentLink
     * @return \Rysun\ProductDocument\Api\Data\ProductDocumentLinkInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Rysun\ProductDocument\Api\Data\ProductDocumentLinkInterface $productDocumentLink
    );

    /**
     * Retrieve ProductDocumentLink
     * @param string $productdocumentlinkId
     * @return \Rysun\ProductDocument\Api\Data\ProductDocumentLinkInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($productdocumentlinkId);

    /**
     * Retrieve ProductDocumentLink matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Rysun\ProductDocument\Api\Data\ProductDocumentLinkSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete ProductDocumentLink
     * @param \Rysun\ProductDocument\Api\Data\ProductDocumentLinkInterface $productDocumentLink
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Rysun\ProductDocument\Api\Data\ProductDocumentLinkInterface $productDocumentLink
    );

    /**
     * Delete ProductDocumentLink by ID
     * @param string $productdocumentlinkId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($productdocumentlinkId);
}

