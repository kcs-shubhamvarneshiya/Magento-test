<?php

namespace Capgemini\ImportExport\Model\Catalog;

class UpdateCatalogBulk
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    private $catalogFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * @var \Capgemini\ImportExport\Model\Product\Bunch
     */
    private $bunch;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $catalogFactory,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Capgemini\ImportExport\Model\Product\Bunch $bunch
    ) {
        $this->catalogFactory = $catalogFactory;
        $this->dateTime = $dateTime;
        $this->bunch = $bunch;
    }

    /**
     * @param array $bunch
     */
    public function execute($bunch)
    {
        $bunchLimit = 500;
        $chunkBunches = array_chunk($bunch, $bunchLimit);

        foreach ($chunkBunches as $chunk) {
            $this->processBatch($chunk);
        }
    }

    /**
     * Process creates or updates a catalog with products
     * @param $bunch
     */
    private function processBatch($bunch)
    {
        $productIds = $this->bunch->getProductIdsBySkuInBunch($bunch);
        $existingProductIds = $this->getExistingProductIds($productIds);

        $newEntryIds = array_diff($productIds, $existingProductIds);
        $createdAt = $this->dateTime->formatDate(true);

        $newEntries = array_map(function ($id) use ($createdAt) {
            if (!$id) {
                return [];
            } else {
                return [
                    'product_id' => $id,
                    'processed' => 0,
                    'created_at' => $createdAt
                ];
            }
        }, $newEntryIds);
    }

    /**
     * Returns all product Id's that belongs to Catalog Collection
     * @return array
     */
    private function getExistingProductIds($productIds)
    {
        $connectorCollection = $this->catalogFactory->create()
            ->addFieldToFilter('product_id', ['in' => $productIds])
            ->addFieldToSelect(['product_id']);

        $catalogIds = $connectorCollection->getColumnValues('product_id');
        return $catalogIds;
    }
}
