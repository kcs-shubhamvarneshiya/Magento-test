<?php
declare(strict_types=1);

namespace Rysun\ArchiCollection\Api\Data;

interface ArchiPlatformSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Archi_Platform list.
     * @return \Rysun\ArchiCollection\Api\Data\ArchiPlatformInterface[]
     */
    public function getItems();

    /**
     * Set platform_id list.
     * @param \Rysun\ArchiCollection\Api\Data\ArchiPlatformInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

