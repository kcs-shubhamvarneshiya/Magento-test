<?php
declare(strict_types=1);

namespace Rysun\ArchiCollection\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ArchiPlatformRepositoryInterface
{

    /**
     * Save Archi_Platform
     * @param \Rysun\ArchiCollection\Api\Data\ArchiPlatformInterface $archiPlatform
     * @return \Rysun\ArchiCollection\Api\Data\ArchiPlatformInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Rysun\ArchiCollection\Api\Data\ArchiPlatformInterface $archiPlatform
    );

    /**
     * Retrieve Archi_Platform
     * @param string $archiPlatformId
     * @return \Rysun\ArchiCollection\Api\Data\ArchiPlatformInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($archiPlatformId);

    /**
     * Retrieve Archi_Platform matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Rysun\ArchiCollection\Api\Data\ArchiPlatformSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Archi_Platform
     * @param \Rysun\ArchiCollection\Api\Data\ArchiPlatformInterface $archiPlatform
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Rysun\ArchiCollection\Api\Data\ArchiPlatformInterface $archiPlatform
    );

    /**
     * Delete Archi_Platform by ID
     * @param string $archiPlatformId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($archiPlatformId);
}

