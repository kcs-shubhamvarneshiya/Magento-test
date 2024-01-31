<?php
declare(strict_types=1);

namespace Rysun\ProductVideo\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Rysun\ProductVideo\Api\Data\ProducVideoLinkInterface;
use Rysun\ProductVideo\Api\Data\ProducVideoLinkInterfaceFactory;
use Rysun\ProductVideo\Api\Data\ProducVideoLinkSearchResultsInterfaceFactory;
use Rysun\ProductVideo\Api\ProducVideoLinkRepositoryInterface;
use Rysun\ProductVideo\Model\ResourceModel\ProducVideoLink as ResourceProducVideoLink;
use Rysun\ProductVideo\Model\ResourceModel\ProducVideoLink\CollectionFactory as ProducVideoLinkCollectionFactory;

class ProducVideoLinkRepository implements ProducVideoLinkRepositoryInterface
{

    /**
     * @var ProducVideoLink
     */
    protected $searchResultsFactory;

    /**
     * @var ResourceProducVideoLink
     */
    protected $resource;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var ProducVideoLinkCollectionFactory
     */
    protected $producVideoLinkCollectionFactory;

    /**
     * @var ProducVideoLinkInterfaceFactory
     */
    protected $producVideoLinkFactory;


    /**
     * @param ResourceProducVideoLink $resource
     * @param ProducVideoLinkInterfaceFactory $producVideoLinkFactory
     * @param ProducVideoLinkCollectionFactory $producVideoLinkCollectionFactory
     * @param ProducVideoLinkSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceProducVideoLink $resource,
        ProducVideoLinkInterfaceFactory $producVideoLinkFactory,
        ProducVideoLinkCollectionFactory $producVideoLinkCollectionFactory,
        ProducVideoLinkSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->producVideoLinkFactory = $producVideoLinkFactory;
        $this->producVideoLinkCollectionFactory = $producVideoLinkCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(
        ProducVideoLinkInterface $producVideoLink
    ) {
        try {
            $this->resource->save($producVideoLink);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the producVideoLink: %1',
                $exception->getMessage()
            ));
        }
        return $producVideoLink;
    }

    /**
     * @inheritDoc
     */
    public function get($producVideoLinkId)
    {
        $producVideoLink = $this->producVideoLinkFactory->create();
        $this->resource->load($producVideoLink, $producVideoLinkId);
        if (!$producVideoLink->getId()) {
            throw new NoSuchEntityException(__('ProducVideoLink with id "%1" does not exist.', $producVideoLinkId));
        }
        return $producVideoLink;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->producVideoLinkCollectionFactory->create();
        
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
        ProducVideoLinkInterface $producVideoLink
    ) {
        try {
            $producVideoLinkModel = $this->producVideoLinkFactory->create();
            $this->resource->load($producVideoLinkModel, $producVideoLink->getProducvideolinkId());
            $this->resource->delete($producVideoLinkModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the ProducVideoLink: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($producVideoLinkId)
    {
        return $this->delete($this->get($producVideoLinkId));
    }
}

