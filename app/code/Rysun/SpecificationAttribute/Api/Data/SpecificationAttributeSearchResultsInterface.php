<?php
declare(strict_types=1);

namespace Rysun\SpecificationAttribute\Api\Data;

interface SpecificationAttributeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get SpecificationAttribute list.
     * @return \Rysun\SpecificationAttribute\Api\Data\SpecificationAttributeInterface[]
     */
    public function getItems();

    /**
     * Set category_id list.
     * @param \Rysun\SpecificationAttribute\Api\Data\SpecificationAttributeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

