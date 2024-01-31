<?php
declare(strict_types=1);

namespace Rysun\ArchiCollection\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ArchiCollectionRepositoryInterface
{

    /**
     * Save Archi_Collection
     * @param \Rysun\ArchiCollection\Api\Data\ArchiCollectionInterface $archiCollection
     * @return \Rysun\ArchiCollection\Api\Data\ArchiCollectionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Rysun\ArchiCollection\Api\Data\ArchiCollectionInterface $archiCollection
    );

    /**
     * Retrieve Archi_Collection
     * @param string $archiCollectionId
     * @return \Rysun\ArchiCollection\Api\Data\ArchiCollectionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($archiCollectionId);

    /**
     * Retrieve Archi_Collection matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Rysun\ArchiCollection\Api\Data\ArchiCollectionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Archi_Collection
     * @param \Rysun\ArchiCollection\Api\Data\ArchiCollectionInterface $archiCollection
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Rysun\ArchiCollection\Api\Data\ArchiCollectionInterface $archiCollection
    );

    /**
     * Delete Archi_Collection by ID
     * @param string $archiCollectionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($archiCollectionId);
}

