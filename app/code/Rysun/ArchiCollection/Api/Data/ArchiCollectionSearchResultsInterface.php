<?php
declare(strict_types=1);

namespace Rysun\ArchiCollection\Api\Data;

interface ArchiCollectionSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Archi_Collection list.
     * @return \Rysun\ArchiCollection\Api\Data\ArchiCollectionInterface[]
     */
    public function getItems();

    /**
     * Set collection_id list.
     * @param \Rysun\ArchiCollection\Api\Data\ArchiCollectionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

