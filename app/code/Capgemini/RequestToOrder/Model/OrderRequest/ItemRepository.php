<?php

namespace Capgemini\RequestToOrder\Model\OrderRequest;

use Capgemini\RequestToOrder\Api\Data\OrderRequest\ItemInterface;
use Capgemini\RequestToOrder\Api\OrderRequest\ItemRepositoryInterface;
use Capgemini\RequestToOrder\Api\RepositoryInterface;
use Capgemini\RequestToOrder\Model\AbstractRepository;
use Capgemini\RequestToOrder\Model\ResourceModel\OrderRequest\Item\Collection as RequestItemsCollection;
use Capgemini\RequestToOrder\Model\ResourceModel\OrderRequest\Item\CollectionFactory as RequestItemsCollectionFactory;
use Capgemini\RequestToOrder\Model\ResourceModel\OrderRequest\ItemFactory as RequestItemsResourceFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\ObjectManagerInterface;


class ItemRepository extends AbstractRepository implements ItemRepositoryInterface, RepositoryInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var ItemFactory
     */
    private $reuqestItemFactory;

    /**
     * @var RequestItemsCollection
     */
    private $requestItemsCollection;

    /**
     * @var RequestItemsCollectionFactory
     */
    private $requestItemsCollectionFactory;

    /**
     * @var RequestItemsResourceFactory
     */
    private $requestItemResourceFactory;

    /**
     * @param ItemFactory $reuqestItemFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param EntityManager $entityManager
     * @param ObjectManagerInterface $objectManager
     * @param RequestItemsCollection $requestItemsCollection
     * @param RequestItemsCollectionFactory $requestItemsCollectionFactory
     * @param RequestItemsResourceFactory $requestItemResourceFactory
     */
    public function __construct(
        ItemFactory                   $reuqestItemFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        EntityManager                 $entityManager,
        ObjectManagerInterface        $objectManager,
        RequestItemsCollection        $requestItemsCollection,
        RequestItemsCollectionFactory $requestItemsCollectionFactory,
        RequestItemsResourceFactory   $requestItemResourceFactory
    )
    {
        parent::__construct($searchResultsFactory);
        $this->reuqestItemFactory = $reuqestItemFactory;
        $this->entityManager = $entityManager;
        $this->objectManager = $objectManager;
        $this->requestItemsCollection = $requestItemsCollection;
        $this->requestItemsCollectionFactory = $requestItemsCollectionFactory;
        $this->requestItemResourceFactory = $requestItemResourceFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create(): ?ItemInterface
    {
        return $this->reuqestItemFactory->create();
    }

    /**
     * {@inheritdoc}
     */
    public function save(ItemInterface $requestItem): ItemInterface
    {
        $classResource = $this->requestItemResourceFactory->create();
        $classResource->save($requestItem);

        return $requestItem;
    }

    /**
     * {@inheritdoc}
     */
    public function getById(int $id): ?ItemInterface
    {
        $requestItem = $this->create();

        $this->entityManager->load($requestItem, $id);

        return $requestItem->getId() ? $requestItem : null;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(ItemInterface $requestItem): bool
    {
        $this->entityManager->delete($requestItem);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById(int $id): bool
    {
        $item = $this->getById($id);

        $this->delete($item);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->requestItemsCollectionFactory->create();
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        return $this->fllSearchResults($collection, $searchCriteria);
    }
}
