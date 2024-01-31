<?php
declare(strict_types=1);

namespace Rysun\ProductDocument\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Rysun\ProductDocument\Api\Data\ProductDocumentInterface;
use Rysun\ProductDocument\Api\Data\ProductDocumentInterfaceFactory;
use Rysun\ProductDocument\Api\Data\ProductDocumentSearchResultsInterfaceFactory;
use Rysun\ProductDocument\Api\ProductDocumentRepositoryInterface;
use Rysun\ProductDocument\Model\ResourceModel\ProductDocument as ResourceProductDocument;
use Rysun\ProductDocument\Model\ResourceModel\ProductDocument\CollectionFactory as ProductDocumentCollectionFactory;

class ProductDocumentRepository implements ProductDocumentRepositoryInterface
{

    /**
     * @var ResourceProductDocument
     */
    protected $resource;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var ProductDocumentInterfaceFactory
     */
    protected $productDocumentFactory;

    /**
     * @var ProductDocumentCollectionFactory
     */
    protected $productDocumentCollectionFactory;

    /**
     * @var ProductDocument
     */
    protected $searchResultsFactory;


    /**
     * @param ResourceProductDocument $resource
     * @param ProductDocumentInterfaceFactory $productDocumentFactory
     * @param ProductDocumentCollectionFactory $productDocumentCollectionFactory
     * @param ProductDocumentSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceProductDocument $resource,
        ProductDocumentInterfaceFactory $productDocumentFactory,
        ProductDocumentCollectionFactory $productDocumentCollectionFactory,
        ProductDocumentSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->productDocumentFactory = $productDocumentFactory;
        $this->productDocumentCollectionFactory = $productDocumentCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(
        ProductDocumentInterface $productDocument
    ) {
        try {
            $this->resource->save($productDocument);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the productDocument: %1',
                $exception->getMessage()
            ));
        }
        return $productDocument;
    }

    /**
     * @inheritDoc
     */
    public function get($productDocumentId)
    {
        $productDocument = $this->productDocumentFactory->create();
        $this->resource->load($productDocument, $productDocumentId);
        if (!$productDocument->getId()) {
            throw new NoSuchEntityException(__('ProductDocument with id "%1" does not exist.', $productDocumentId));
        }
        return $productDocument;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->productDocumentCollectionFactory->create();
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(
        ProductDocumentInterface $productDocument
    ) {
        try {
            $productDocumentModel = $this->productDocumentFactory->create();
            $this->resource->load($productDocumentModel, $productDocument->getProductdocumentId());
            $this->resource->delete($productDocumentModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the ProductDocument: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($productDocumentId)
    {
        return $this->delete($this->get($productDocumentId));
    }
}

