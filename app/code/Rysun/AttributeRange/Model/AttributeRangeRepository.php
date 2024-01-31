<?php
declare(strict_types=1);

namespace Rysun\AttributeRange\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Rysun\AttributeRange\Api\AttributeRangeRepositoryInterface;
use Rysun\AttributeRange\Api\Data\AttributeRangeInterface;
use Rysun\AttributeRange\Api\Data\AttributeRangeInterfaceFactory;
use Rysun\AttributeRange\Api\Data\AttributeRangeSearchResultsInterfaceFactory;
use Rysun\AttributeRange\Model\ResourceModel\AttributeRange as ResourceAttributeRange;
use Rysun\AttributeRange\Model\ResourceModel\AttributeRange\CollectionFactory as AttributeRangeCollectionFactory;

class AttributeRangeRepository implements AttributeRangeRepositoryInterface
{

    /**
     * @var AttributeRangeCollectionFactory
     */
    protected $attributeRangeCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var AttributeRange
     */
    protected $searchResultsFactory;

    /**
     * @var ResourceAttributeRange
     */
    protected $resource;

    /**
     * @var AttributeRangeInterfaceFactory
     */
    protected $attributeRangeFactory;


    /**
     * @param ResourceAttributeRange $resource
     * @param AttributeRangeInterfaceFactory $attributeRangeFactory
     * @param AttributeRangeCollectionFactory $attributeRangeCollectionFactory
     * @param AttributeRangeSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceAttributeRange $resource,
        AttributeRangeInterfaceFactory $attributeRangeFactory,
        AttributeRangeCollectionFactory $attributeRangeCollectionFactory,
        AttributeRangeSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->attributeRangeFactory = $attributeRangeFactory;
        $this->attributeRangeCollectionFactory = $attributeRangeCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(AttributeRangeInterface $attributeRange)
    {
        try {
            $this->resource->save($attributeRange);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the attributeRange: %1',
                $exception->getMessage()
            ));
        }
        return $attributeRange;
    }

    /**
     * @inheritDoc
     */
    public function get($attributeRangeId)
    {
        $attributeRange = $this->attributeRangeFactory->create();
        $this->resource->load($attributeRange, $attributeRangeId);
        if (!$attributeRange->getId()) {
            throw new NoSuchEntityException(__('AttributeRange with id "%1" does not exist.', $attributeRangeId));
        }
        return $attributeRange;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->attributeRangeCollectionFactory->create();
        
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
    public function delete(AttributeRangeInterface $attributeRange)
    {
        try {
            $attributeRangeModel = $this->attributeRangeFactory->create();
            $this->resource->load($attributeRangeModel, $attributeRange->getAttributerangeId());
            $this->resource->delete($attributeRangeModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the AttributeRange: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($attributeRangeId)
    {
        return $this->delete($this->get($attributeRangeId));
    }
}

