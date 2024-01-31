<?php
declare(strict_types=1);

namespace Rysun\ProductDocument\Api\Data;

interface ProductDocumentLinkSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get ProductDocumentLink list.
     * @return \Rysun\ProductDocument\Api\Data\ProductDocumentLinkInterface[]
     */
    public function getItems();

    /**
     * Set sql_serv_prod_id list.
     * @param \Rysun\ProductDocument\Api\Data\ProductDocumentLinkInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

