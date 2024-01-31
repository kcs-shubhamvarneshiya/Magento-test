<?php
declare(strict_types=1);

namespace Rysun\ProductDocument\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Rysun\ProductDocument\Api\Data\ProductDocumentLinkInterface;
use Rysun\ProductDocument\Api\Data\ProductDocumentLinkInterfaceFactory;
use Rysun\ProductDocument\Api\Data\ProductDocumentLinkSearchResultsInterfaceFactory;
use Rysun\ProductDocument\Api\ProductDocumentLinkRepositoryInterface;
use Rysun\ProductDocument\Model\ResourceModel\ProductDocumentLink as ResourceProductDocumentLink;
use Rysun\ProductDocument\Model\ResourceModel\ProductDocumentLink\CollectionFactory as ProductDocumentLinkCollectionFactory;

class ProductDocumentLinkRepository implements ProductDocumentLinkRepositoryInterface
{

    /**
     * @var ProductDocumentLink
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var ProductDocumentLinkCollectionFactory
     */
    protected $productDocumentLinkCollectionFactory;

    /**
     * @var ResourceProductDocumentLink
     */
    protected $resource;

    /**
     * @var ProductDocumentLinkInterfaceFactory
     */
    protected $productDocumentLinkFactory;


    /**
     * @param ResourceProductDocumentLink $resource
     * @param ProductDocumentLinkInterfaceFactory $productDocumentLinkFactory
     * @param ProductDocumentLinkCollectionFactory $productDocumentLinkCollectionFactory
     * @param ProductDocumentLinkSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceProductDocumentLink $resource,
        ProductDocumentLinkInterfaceFactory $productDocumentLinkFactory,
        ProductDocumentLinkCollectionFactory $productDocumentLinkCollectionFactory,
        ProductDocumentLinkSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->productDocumentLinkFactory = $productDocumentLinkFactory;
        $this->productDocumentLinkCollectionFactory = $productDocumentLinkCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(
        ProductDocumentLinkInterface $productDocumentLink
    ) {
        try {
            $this->resource->save($productDocumentLink);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the productDocumentLink: %1',
                $exception->getMessage()
            ));
        }
        return $productDocumentLink;
    }

    /**
     * @inheritDoc
     */
    public function get($productDocumentLinkId)
    {
        $productDocumentLink = $this->productDocumentLinkFactory->create();
        $this->resource->load($productDocumentLink, $productDocumentLinkId);
        if (!$productDocumentLink->getId()) {
            throw new NoSuchEntityException(__('ProductDocumentLink with id "%1" does not exist.', $productDocumentLinkId));
        }
        return $productDocumentLink;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->productDocumentLinkCollectionFactory->create();
        
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
        ProductDocumentLinkInterface $productDocumentLink
    ) {
        try {
            $productDocumentLinkModel = $this->productDocumentLinkFactory->create();
            $this->resource->load($productDocumentLinkModel, $productDocumentLink->getProductdocumentlinkId());
            $this->resource->delete($productDocumentLinkModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the ProductDocumentLink: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($productDocumentLinkId)
    {
        return $this->delete($this->get($productDocumentLinkId));
    }
}

