<?php
declare(strict_types=1);

namespace Rysun\ProductVideo\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ProductVideoRepositoryInterface
{

    /**
     * Save ProductVideo
     * @param \Rysun\ProductVideo\Api\Data\ProductVideoInterface $productVideo
     * @return \Rysun\ProductVideo\Api\Data\ProductVideoInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Rysun\ProductVideo\Api\Data\ProductVideoInterface $productVideo
    );

    /**
     * Retrieve ProductVideo
     * @param string $productvideoId
     * @return \Rysun\ProductVideo\Api\Data\ProductVideoInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($productvideoId);

    /**
     * Retrieve ProductVideo matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Rysun\ProductVideo\Api\Data\ProductVideoSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete ProductVideo
     * @param \Rysun\ProductVideo\Api\Data\ProductVideoInterface $productVideo
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Rysun\ProductVideo\Api\Data\ProductVideoInterface $productVideo
    );

    /**
     * Delete ProductVideo by ID
     * @param string $productvideoId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($productvideoId);
}

