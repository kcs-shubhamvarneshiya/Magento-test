<?php
declare(strict_types=1);

namespace Rysun\DimensionImages\Api\Data;

interface DimensionImagesSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get DimensionImages list.
     * @return \Rysun\DimensionImages\Api\Data\DimensionImagesInterface[]
     */
    public function getItems();

    /**
     * Set sql_serv_prod_id list.
     * @param \Rysun\DimensionImages\Api\Data\DimensionImagesInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

