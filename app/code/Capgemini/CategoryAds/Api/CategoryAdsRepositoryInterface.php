<?php
/**
 * Capgemini_CategoryAds
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\CategoryAds\Api;

use Capgemini\CategoryAds\Api\Data\CategoryAdsInterface;
use ClassyLlama\AvaTax\Model\CrossBorderClass\CountryLink;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;

interface CategoryAdsRepositoryInterface
{
    /**
     * @return CategoryAdsInterface
     */
    public function create(): ?CategoryAdsInterface;

    /**
     * Save
     *
     * @param CategoryAdsInterface $categoryAd
     * @return CategoryAdsInterface
     */
    public function save(CategoryAdsInterface $categoryAd): CategoryAdsInterface;

    /**
     * Get by id
     *
     * @param int $id
     * @return CategoryAdsInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): ?CategoryAdsInterface;

    /**
     * Delete
     *
     * @param CategoryAdsInterface $categoryAd
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(CategoryAdsInterface $categoryAd): bool;

    /**
     * Delete by id
     *
     * @param int $id
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $id): bool;

    /**
     * Get countries associated with category ad
     *
     * @param string $categoryAdId
     * @return array
     */
    public function getCategoriesForAd(string $categoryAdId): array;

    /**
     * Add associated countries to a class in a standard format
     *
     * @param CategoryAdsInterface $categoryAd
     * @param CountryLink[]|string[]|null $categories
     * @return CategoryAdsInterface
     */
    public function addCategoriesToAd(CategoryAdsInterface $categoryAd, array $categories = null): CategoryAdsInterface;

    /**
     * Lists
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     * @throws NoSuchEntityException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;
}
