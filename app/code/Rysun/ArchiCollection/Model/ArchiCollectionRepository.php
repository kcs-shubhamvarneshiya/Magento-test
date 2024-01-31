<?php
declare(strict_types=1);

namespace Rysun\ArchiCollection\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Rysun\ArchiCollection\Api\ArchiCollectionRepositoryInterface;
use Rysun\ArchiCollection\Api\Data\ArchiCollectionInterface;
use Rysun\ArchiCollection\Api\Data\ArchiCollectionInterfaceFactory;
use Rysun\ArchiCollection\Api\Data\ArchiCollectionSearchResultsInterfaceFactory;
use Rysun\ArchiCollection\Model\ResourceModel\ArchiCollection as ResourceArchiCollection;
use Rysun\ArchiCollection\Model\ResourceModel\ArchiCollection\CollectionFactory as ArchiCollectionCollectionFactory;

class ArchiCollectionRepository implements ArchiCollectionRepositoryInterface
{

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var ArchiCollectionCollectionFactory
     */
    protected $archiCollectionCollectionFactory;

    /**
     * @var ArchiCollectionInterfaceFactory
     */
    protected $archiCollectionFactory;

    /**
     * @var ResourceArchiCollection
     */
    protected $resource;

    /**
     * @var ArchiCollection
     */
    protected $searchResultsFactory;


    /**
     * @param ResourceArchiCollection $resource
     * @param ArchiCollectionInterfaceFactory $archiCollectionFactory
     * @param ArchiCollectionCollectionFactory $archiCollectionCollectionFactory
     * @param ArchiCollectionSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceArchiCollection $resource,
        ArchiCollectionInterfaceFactory $archiCollectionFactory,
        ArchiCollectionCollectionFactory $archiCollectionCollectionFactory,
        ArchiCollectionSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->archiCollectionFactory = $archiCollectionFactory;
        $this->archiCollectionCollectionFactory = $archiCollectionCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(
        ArchiCollectionInterface $archiCollection
    ) {
        try {
            $this->resource->save($archiCollection);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the archiCollection: %1',
                $exception->getMessage()
            ));
        }
        return $archiCollection;
    }

    /**
     * @inheritDoc
     */
    public function get($archiCollectionId)
    {
        $archiCollection = $this->archiCollectionFactory->create();
        $this->resource->load($archiCollection, $archiCollectionId);
        if (!$archiCollection->getId()) {
            throw new NoSuchEntityException(__('Archi_Collection with id "%1" does not exist.', $archiCollectionId));
        }
        return $archiCollection;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->archiCollectionCollectionFactory->create();
        
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
        ArchiCollectionInterface $archiCollection
    ) {
        try {
            $archiCollectionModel = $this->archiCollectionFactory->create();
            $this->resource->load($archiCollectionModel, $archiCollection->getArchiCollectionId());
            $this->resource->delete($archiCollectionModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Archi_Collection: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($archiCollectionId)
    {
        return $this->delete($this->get($archiCollectionId));
    }
}

