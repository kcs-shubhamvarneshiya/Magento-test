<?php
declare(strict_types=1);

namespace Rysun\SpecificationAttribute\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Rysun\SpecificationAttribute\Api\Data\SpecificationAttributeInterface;
use Rysun\SpecificationAttribute\Api\Data\SpecificationAttributeInterfaceFactory;
use Rysun\SpecificationAttribute\Api\Data\SpecificationAttributeSearchResultsInterfaceFactory;
use Rysun\SpecificationAttribute\Api\SpecificationAttributeRepositoryInterface;
use Rysun\SpecificationAttribute\Model\ResourceModel\SpecificationAttribute as ResourceSpecificationAttribute;
use Rysun\SpecificationAttribute\Model\ResourceModel\SpecificationAttribute\CollectionFactory as SpecificationAttributeCollectionFactory;

class SpecificationAttributeRepository implements SpecificationAttributeRepositoryInterface
{

    /**
     * @var SpecificationAttribute
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var SpecificationAttributeCollectionFactory
     */
    protected $specificationAttributeCollectionFactory;

    /**
     * @var ResourceSpecificationAttribute
     */
    protected $resource;

    /**
     * @var SpecificationAttributeInterfaceFactory
     */
    protected $specificationAttributeFactory;


    /**
     * @param ResourceSpecificationAttribute $resource
     * @param SpecificationAttributeInterfaceFactory $specificationAttributeFactory
     * @param SpecificationAttributeCollectionFactory $specificationAttributeCollectionFactory
     * @param SpecificationAttributeSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceSpecificationAttribute $resource,
        SpecificationAttributeInterfaceFactory $specificationAttributeFactory,
        SpecificationAttributeCollectionFactory $specificationAttributeCollectionFactory,
        SpecificationAttributeSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->specificationAttributeFactory = $specificationAttributeFactory;
        $this->specificationAttributeCollectionFactory = $specificationAttributeCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(
        SpecificationAttributeInterface $specificationAttribute
    ) {
        try {
            $this->resource->save($specificationAttribute);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the specificationAttribute: %1',
                $exception->getMessage()
            ));
        }
        return $specificationAttribute;
    }

    /**
     * @inheritDoc
     */
    public function get($specificationAttributeId)
    {
        $specificationAttribute = $this->specificationAttributeFactory->create();
        $this->resource->load($specificationAttribute, $specificationAttributeId);
        if (!$specificationAttribute->getId()) {
            throw new NoSuchEntityException(__('SpecificationAttribute with id "%1" does not exist.', $specificationAttributeId));
        }
        return $specificationAttribute;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->specificationAttributeCollectionFactory->create();
        
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
        SpecificationAttributeInterface $specificationAttribute
    ) {
        try {
            $specificationAttributeModel = $this->specificationAttributeFactory->create();
            $this->resource->load($specificationAttributeModel, $specificationAttribute->getSpecificationattributeId());
            $this->resource->delete($specificationAttributeModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the SpecificationAttribute: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($specificationAttributeId)
    {
        return $this->delete($this->get($specificationAttributeId));
    }
}

