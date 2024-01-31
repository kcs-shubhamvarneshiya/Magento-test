<?php
declare(strict_types=1);

namespace Rysun\ProductVideo\Api\Data;

interface ProductVideoSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get ProductVideo list.
     * @return \Rysun\ProductVideo\Api\Data\ProductVideoInterface[]
     */
    public function getItems();

    /**
     * Set video_caption list.
     * @param \Rysun\ProductVideo\Api\Data\ProductVideoInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

