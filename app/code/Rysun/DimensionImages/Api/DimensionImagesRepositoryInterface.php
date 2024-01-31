<?php
declare(strict_types=1);

namespace Rysun\DimensionImages\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface DimensionImagesRepositoryInterface
{

    /**
     * Save DimensionImages
     * @param \Rysun\DimensionImages\Api\Data\DimensionImagesInterface $dimensionImages
     * @return \Rysun\DimensionImages\Api\Data\DimensionImagesInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Rysun\DimensionImages\Api\Data\DimensionImagesInterface $dimensionImages
    );

    /**
     * Retrieve DimensionImages
     * @param string $dimensionimagesId
     * @return \Rysun\DimensionImages\Api\Data\DimensionImagesInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($dimensionimagesId);

    /**
     * Retrieve DimensionImages matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Rysun\DimensionImages\Api\Data\DimensionImagesSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete DimensionImages
     * @param \Rysun\DimensionImages\Api\Data\DimensionImagesInterface $dimensionImages
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Rysun\DimensionImages\Api\Data\DimensionImagesInterface $dimensionImages
    );

    /**
     * Delete DimensionImages by ID
     * @param string $dimensionimagesId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($dimensionimagesId);
}

