<?php
declare(strict_types=1);

namespace Rysun\DimensionImages\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Rysun\DimensionImages\Api\Data\DimensionImagesInterface;
use Rysun\DimensionImages\Api\Data\DimensionImagesInterfaceFactory;
use Rysun\DimensionImages\Api\Data\DimensionImagesSearchResultsInterfaceFactory;
use Rysun\DimensionImages\Api\DimensionImagesRepositoryInterface;
use Rysun\DimensionImages\Model\ResourceModel\DimensionImages as ResourceDimensionImages;
use Rysun\DimensionImages\Model\ResourceModel\DimensionImages\CollectionFactory as DimensionImagesCollectionFactory;

class DimensionImagesRepository implements DimensionImagesRepositoryInterface
{

    /**
     * @var DimensionImagesCollectionFactory
     */
    protected $dimensionImagesCollectionFactory;

    /**
     * @var ResourceDimensionImages
     */
    protected $resource;

    /**
     * @var DimensionImagesInterfaceFactory
     */
    protected $dimensionImagesFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var DimensionImages
     */
    protected $searchResultsFactory;


    /**
     * @param ResourceDimensionImages $resource
     * @param DimensionImagesInterfaceFactory $dimensionImagesFactory
     * @param DimensionImagesCollectionFactory $dimensionImagesCollectionFactory
     * @param DimensionImagesSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceDimensionImages $resource,
        DimensionImagesInterfaceFactory $dimensionImagesFactory,
        DimensionImagesCollectionFactory $dimensionImagesCollectionFactory,
        DimensionImagesSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->dimensionImagesFactory = $dimensionImagesFactory;
        $this->dimensionImagesCollectionFactory = $dimensionImagesCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(
        DimensionImagesInterface $dimensionImages
    ) {
        try {
            $this->resource->save($dimensionImages);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the dimensionImages: %1',
                $exception->getMessage()
            ));
        }
        return $dimensionImages;
    }

    /**
     * @inheritDoc
     */
    public function get($dimensionImagesId)
    {
        $dimensionImages = $this->dimensionImagesFactory->create();
        $this->resource->load($dimensionImages, $dimensionImagesId);
        if (!$dimensionImages->getId()) {
            throw new NoSuchEntityException(__('DimensionImages with id "%1" does not exist.', $dimensionImagesId));
        }
        return $dimensionImages;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->dimensionImagesCollectionFactory->create();
        
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
        DimensionImagesInterface $dimensionImages
    ) {
        try {
            $dimensionImagesModel = $this->dimensionImagesFactory->create();
            $this->resource->load($dimensionImagesModel, $dimensionImages->getDimensionimagesId());
            $this->resource->delete($dimensionImagesModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the DimensionImages: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($dimensionImagesId)
    {
        return $this->delete($this->get($dimensionImagesId));
    }
}

