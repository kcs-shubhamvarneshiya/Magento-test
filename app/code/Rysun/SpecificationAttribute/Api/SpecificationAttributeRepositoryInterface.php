<?php
declare(strict_types=1);

namespace Rysun\SpecificationAttribute\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface SpecificationAttributeRepositoryInterface
{

    /**
     * Save SpecificationAttribute
     * @param \Rysun\SpecificationAttribute\Api\Data\SpecificationAttributeInterface $specificationAttribute
     * @return \Rysun\SpecificationAttribute\Api\Data\SpecificationAttributeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Rysun\SpecificationAttribute\Api\Data\SpecificationAttributeInterface $specificationAttribute
    );

    /**
     * Retrieve SpecificationAttribute
     * @param string $specificationattributeId
     * @return \Rysun\SpecificationAttribute\Api\Data\SpecificationAttributeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($specificationattributeId);

    /**
     * Retrieve SpecificationAttribute matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Rysun\SpecificationAttribute\Api\Data\SpecificationAttributeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete SpecificationAttribute
     * @param \Rysun\SpecificationAttribute\Api\Data\SpecificationAttributeInterface $specificationAttribute
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Rysun\SpecificationAttribute\Api\Data\SpecificationAttributeInterface $specificationAttribute
    );

    /**
     * Delete SpecificationAttribute by ID
     * @param string $specificationattributeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($specificationattributeId);
}

