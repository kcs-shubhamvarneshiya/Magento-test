<?php
declare(strict_types=1);

namespace Rysun\ProductVideo\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ProducVideoLinkRepositoryInterface
{

    /**
     * Save ProducVideoLink
     * @param \Rysun\ProductVideo\Api\Data\ProducVideoLinkInterface $producVideoLink
     * @return \Rysun\ProductVideo\Api\Data\ProducVideoLinkInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Rysun\ProductVideo\Api\Data\ProducVideoLinkInterface $producVideoLink
    );

    /**
     * Retrieve ProducVideoLink
     * @param string $producvideolinkId
     * @return \Rysun\ProductVideo\Api\Data\ProducVideoLinkInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($producvideolinkId);

    /**
     * Retrieve ProducVideoLink matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Rysun\ProductVideo\Api\Data\ProducVideoLinkSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete ProducVideoLink
     * @param \Rysun\ProductVideo\Api\Data\ProducVideoLinkInterface $producVideoLink
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Rysun\ProductVideo\Api\Data\ProducVideoLinkInterface $producVideoLink
    );

    /**
     * Delete ProducVideoLink by ID
     * @param string $producvideolinkId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($producvideolinkId);
}

