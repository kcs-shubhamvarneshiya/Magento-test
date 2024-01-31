<?php
declare(strict_types=1);

namespace Rysun\ProductDocument\Api\Data;

interface ProductDocumentSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get ProductDocument list.
     * @return \Rysun\ProductDocument\Api\Data\ProductDocumentInterface[]
     */
    public function getItems();

    /**
     * Set document_caption list.
     * @param \Rysun\ProductDocument\Api\Data\ProductDocumentInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

