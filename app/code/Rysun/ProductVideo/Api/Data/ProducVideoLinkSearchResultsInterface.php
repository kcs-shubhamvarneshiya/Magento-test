<?php
declare(strict_types=1);

namespace Rysun\ProductVideo\Api\Data;

interface ProducVideoLinkSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get ProducVideoLink list.
     * @return \Rysun\ProductVideo\Api\Data\ProducVideoLinkInterface[]
     */
    public function getItems();

    /**
     * Set sql_serv_prod_id list.
     * @param \Rysun\ProductVideo\Api\Data\ProducVideoLinkInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

