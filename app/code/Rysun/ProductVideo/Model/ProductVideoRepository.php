<?php
declare(strict_types=1);

namespace Rysun\ProductVideo\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Rysun\ProductVideo\Api\Data\ProductVideoInterface;
use Rysun\ProductVideo\Api\Data\ProductVideoInterfaceFactory;
use Rysun\ProductVideo\Api\Data\ProductVideoSearchResultsInterfaceFactory;
use Rysun\ProductVideo\Api\ProductVideoRepositoryInterface;
use Rysun\ProductVideo\Model\ResourceModel\ProductVideo as ResourceProductVideo;
use Rysun\ProductVideo\Model\ResourceModel\ProductVideo\CollectionFactory as ProductVideoCollectionFactory;

class ProductVideoRepository implements ProductVideoRepositoryInterface
{

    /**
     * @var ResourceProductVideo
     */
    protected $resource;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var ProductVideoInterfaceFactory
     */
    protected $productVideoFactory;

    /**
     * @var ProductVideoCollectionFactory
     */
    protected $productVideoCollectionFactory;

    /**
     * @var ProductVideo
     */
    protected $searchResultsFactory;


    /**
     * @param ResourceProductVideo $resource
     * @param ProductVideoInterfaceFactory $productVideoFactory
     * @param ProductVideoCollectionFactory $productVideoCollectionFactory
     * @param ProductVideoSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceProductVideo $resource,
        ProductVideoInterfaceFactory $productVideoFactory,
        ProductVideoCollectionFactory $productVideoCollectionFactory,
        ProductVideoSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->productVideoFactory = $productVideoFactory;
        $this->productVideoCollectionFactory = $productVideoCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(ProductVideoInterface $productVideo)
    {
        try {
            $this->resource->save($productVideo);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the productVideo: %1',
                $exception->getMessage()
            ));
        }
        return $productVideo;
    }

    /**
     * @inheritDoc
     */
    public function get($productVideoId)
    {
        $productVideo = $this->productVideoFactory->create();
        $this->resource->load($productVideo, $productVideoId);
        if (!$productVideo->getId()) {
            throw new NoSuchEntityException(__('ProductVideo with id "%1" does not exist.', $productVideoId));
        }
        return $productVideo;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->productVideoCollectionFactory->create();
        
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
    public function delete(ProductVideoInterface $productVideo)
    {
        try {
            $productVideoModel = $this->productVideoFactory->create();
            $this->resource->load($productVideoModel, $productVideo->getProductvideoId());
            $this->resource->delete($productVideoModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the ProductVideo: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($productVideoId)
    {
        return $this->delete($this->get($productVideoId));
    }
}

