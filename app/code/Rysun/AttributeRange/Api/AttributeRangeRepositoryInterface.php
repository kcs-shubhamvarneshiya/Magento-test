<?php
declare(strict_types=1);

namespace Rysun\AttributeRange\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface AttributeRangeRepositoryInterface
{

    /**
     * Save AttributeRange
     * @param \Rysun\AttributeRange\Api\Data\AttributeRangeInterface $attributeRange
     * @return \Rysun\AttributeRange\Api\Data\AttributeRangeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Rysun\AttributeRange\Api\Data\AttributeRangeInterface $attributeRange
    );

    /**
     * Retrieve AttributeRange
     * @param string $attributerangeId
     * @return \Rysun\AttributeRange\Api\Data\AttributeRangeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($attributerangeId);

    /**
     * Retrieve AttributeRange matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Rysun\AttributeRange\Api\Data\AttributeRangeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete AttributeRange
     * @param \Rysun\AttributeRange\Api\Data\AttributeRangeInterface $attributeRange
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Rysun\AttributeRange\Api\Data\AttributeRangeInterface $attributeRange
    );

    /**
     * Delete AttributeRange by ID
     * @param string $attributerangeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($attributerangeId);
}

