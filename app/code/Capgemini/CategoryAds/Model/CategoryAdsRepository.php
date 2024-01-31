<?php
/**
 * Capgemini_CategoryAds
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\CategoryAds\Model;

use Capgemini\CategoryAds\Api\CategoryAdsRepositoryInterface;
use Capgemini\CategoryAds\Api\Data\CategoryAdsInterface;
use Capgemini\CategoryAds\Model\CategoryAds\CategoryLink;
use Magento\Framework\Api\Search\SearchResult;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\DataObject;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\ObjectManagerInterface;
use Capgemini\CategoryAds\Model\ResourceModel\CategoryAds\CollectionFactory as CollectionFactory;
use Capgemini\CategoryAds\Model\ResourceModel\CategoryAdsFactory as CategoryAdsResourceFactory;

class CategoryAdsRepository implements CategoryAdsRepositoryInterface
{

    /**
     * @var CategoryAdsFactory
     */
    private $factory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var array
     */
    private $repositoryPool = [];

    /**
     * @var SearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var ResourceModel\CategoryAds\CategoryLink\CollectionFactory
     */
    protected $categoryLinkCollectionFactory;

    /**
     * @var CategoryAdsResourceFactory
     */
    protected $categoryAdsResourceFactory;

    /**
     * EmailRepository constructor.
     * @param CategoryAdsFactory $categoryAdFactory
     * @param CollectionFactory $collectionFactory
     * @param EntityManager $entityManager
     * @param ObjectManagerInterface $objectManager
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param ResourceModel\CategoryAds\CategoryLink\CollectionFactory $categoryLinkCollectionFactory
     * @param CategoryAdsResourceFactory $categoryAdsResourceFactory
     * @param array $repositoryPool
     */
    public function __construct(
        CategoryAdsFactory                                       $categoryAdFactory,
        CollectionFactory                                        $collectionFactory,
        EntityManager                                            $entityManager,
        ObjectManagerInterface                                   $objectManager,
        SearchResultsInterfaceFactory                            $searchResultsFactory,
        ResourceModel\CategoryAds\CategoryLink\CollectionFactory $categoryLinkCollectionFactory,
        CategoryAdsResourceFactory                               $categoryAdsResourceFactory,
        array                                                    $repositoryPool = []
    ) {
        $this->factory           = $categoryAdFactory;
        $this->collectionFactory = $collectionFactory;
        $this->entityManager     = $entityManager;
        $this->objectManager     = $objectManager;
        $this->repositoryPool    = $repositoryPool;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->categoryLinkCollectionFactory = $categoryLinkCollectionFactory;
        $this->categoryAdsResourceFactory = $categoryAdsResourceFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create(): ?CategoryAdsInterface
    {
        return $this->factory->create();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($id): ?CategoryAdsInterface
    {
        $categoryAd = $this->create();

        $this->entityManager->load($categoryAd, $id);

        $this->addCategoriesToAd($categoryAd);

        return $categoryAd->getId() ? $categoryAd : null;
    }

    /**
     * {@inheritdoc}
     */
    public function save(CategoryAdsInterface $categoryAd): CategoryAdsInterface
    {
        $classResource = $this->categoryAdsResourceFactory->create();
        $classResource->save($categoryAd);

        return $categoryAd;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(CategoryAdsInterface $categoryAd): bool
    {
        $this->entityManager->delete($categoryAd);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById(int $id): bool
    {
        $model = $this->getById($id);

        $this->delete($model);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategoriesForAd(string $categoryAdId): array
    {
        $categoryLinkCollection = $this->categoryLinkCollectionFactory->create();
        $categoryLinkCollection->addFieldToFilter('plpad_id', $categoryAdId);

        $result = [];
        foreach ($categoryLinkCollection as $categoryLink) {
            $result[] = $categoryLink->getCategoryId();
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function addCategoriesToAd(CategoryAdsInterface $categoryAd, array $categories = null): CategoryAdsInterface
    {
        if (is_null($categories)) {
            $categories = $this->getCategoriesForAd($categoryAd->getId());
        }

        $result = [];
        foreach ($categories as $category) {
            if ($category instanceof CategoryLink) {
                $category = $category->getCategoryId();
            }

            $result[] = $category;
        }

        $categoryAd->setCategories($result);

        return $categoryAd;
    }

    /**
     * @param int $categoryId
     * @param bool $orderByDate
     * @return DataObject[]
     */
    public function getAdsByCategory(int $categoryId, bool $orderByDate = false)
    {
        $collection = $this->collectionFactory->create();
        $collection->filterByCategoryIds([$categoryId]);
        $collection->addFieldToFilter(CategoryAdsInterface::IS_ENABLED, 1);
        if ($orderByDate) {
            $collection->addOrder(CategoryAdsInterface::CREATED_AT, 'DESC');
        }
        return $collection->getItems();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $collection = $this->collectionFactory->create();
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $fields[] = $filter->getField();
                $conditions[] = [$condition => $filter->getValue()];
            }

            if ($fields) {
                $collection->addFieldToFilter($fields, $conditions);
            }
        }

        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders) {
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $objects = [];
        foreach ($collection as $objectModel) {
            $objects[] = $objectModel;
        }

        $searchResults->setItems($objects);
        return $searchResults;
    }
}
