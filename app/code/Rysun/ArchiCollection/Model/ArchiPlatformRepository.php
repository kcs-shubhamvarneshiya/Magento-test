<?php
declare(strict_types=1);

namespace Rysun\ArchiCollection\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Rysun\ArchiCollection\Api\ArchiPlatformRepositoryInterface;
use Rysun\ArchiCollection\Api\Data\ArchiPlatformInterface;
use Rysun\ArchiCollection\Api\Data\ArchiPlatformInterfaceFactory;
use Rysun\ArchiCollection\Api\Data\ArchiPlatformSearchResultsInterfaceFactory;
use Rysun\ArchiCollection\Model\ResourceModel\ArchiPlatform as ResourceArchiPlatform;
use Rysun\ArchiCollection\Model\ResourceModel\ArchiPlatform\CollectionFactory as ArchiPlatformCollectionFactory;

class ArchiPlatformRepository implements ArchiPlatformRepositoryInterface
{

    /**
     * @var ArchiPlatformCollectionFactory
     */
    protected $archiPlatformCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var ResourceArchiPlatform
     */
    protected $resource;

    /**
     * @var ArchiPlatform
     */
    protected $searchResultsFactory;

    /**
     * @var ArchiPlatformInterfaceFactory
     */
    protected $archiPlatformFactory;


    /**
     * @param ResourceArchiPlatform $resource
     * @param ArchiPlatformInterfaceFactory $archiPlatformFactory
     * @param ArchiPlatformCollectionFactory $archiPlatformCollectionFactory
     * @param ArchiPlatformSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceArchiPlatform $resource,
        ArchiPlatformInterfaceFactory $archiPlatformFactory,
        ArchiPlatformCollectionFactory $archiPlatformCollectionFactory,
        ArchiPlatformSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->archiPlatformFactory = $archiPlatformFactory;
        $this->archiPlatformCollectionFactory = $archiPlatformCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(ArchiPlatformInterface $archiPlatform)
    {
        try {
            $this->resource->save($archiPlatform);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the archiPlatform: %1',
                $exception->getMessage()
            ));
        }
        return $archiPlatform;
    }

    /**
     * @inheritDoc
     */
    public function get($archiPlatformId)
    {
        $archiPlatform = $this->archiPlatformFactory->create();
        $this->resource->load($archiPlatform, $archiPlatformId);
        if (!$archiPlatform->getId()) {
            throw new NoSuchEntityException(__('Archi_Platform with id "%1" does not exist.', $archiPlatformId));
        }
        return $archiPlatform;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->archiPlatformCollectionFactory->create();
        
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
    public function delete(ArchiPlatformInterface $archiPlatform)
    {
        try {
            $archiPlatformModel = $this->archiPlatformFactory->create();
            $this->resource->load($archiPlatformModel, $archiPlatform->getArchiPlatformId());
            $this->resource->delete($archiPlatformModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Archi_Platform: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($archiPlatformId)
    {
        return $this->delete($this->get($archiPlatformId));
    }
}

