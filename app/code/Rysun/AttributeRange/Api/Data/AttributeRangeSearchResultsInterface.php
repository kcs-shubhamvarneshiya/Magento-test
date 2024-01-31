<?php
declare(strict_types=1);

namespace Rysun\AttributeRange\Api\Data;

interface AttributeRangeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get AttributeRange list.
     * @return \Rysun\AttributeRange\Api\Data\AttributeRangeInterface[]
     */
    public function getItems();

    /**
     * Set attribute_range_desc list.
     * @param \Rysun\AttributeRange\Api\Data\AttributeRangeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

