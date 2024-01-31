<?php
/**
 * Capgemini_CategoryAds
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Model;

use Capgemini\RequestToOrder\Api\Data\OrderRequestInterface;
use Capgemini\RequestToOrder\Api\OrderRequestRepositoryInterface;
use Capgemini\RequestToOrder\Api\RepositoryInterface;
use Capgemini\RequestToOrder\Model\ResourceModel\OrderRequest\Collection as OrderRequestCollection;
use Capgemini\RequestToOrder\Model\ResourceModel\OrderRequest\CollectionFactory as OrderRequestCollectionFactory;
use Capgemini\RequestToOrder\Model\ResourceModel\OrderRequestFactory as OrderRequestResourceFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\ObjectManagerInterface;

class OrderRequestRepository extends AbstractRepository implements OrderRequestRepositoryInterface, RepositoryInterface
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
     * @var OrderRequestFactory
     */
    private $orderRequestFactory;

    /**
     * @var OrderRequestCollection
     */
    private $orderReqestCollection;

    /**
     * @var OrderRequestCollectionFactory
     */
    private $orderRequestCollectionFactory;

    /**
     * @var OrderRequestResourceFactory
     */
    private $orderRequestResourceFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @param OrderRequestFactory $orderRequestFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param EntityManager $entityManager
     * @param ObjectManagerInterface $objectManager
     * @param OrderRequestCollection $orderReqestCollection
     * @param OrderRequestCollectionFactory $orderRequestCollectionFactory
     * @param OrderRequestResourceFactory $orderRequestResourceFactory
     */
    public function __construct(
        OrderRequestFactory           $orderRequestFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        EntityManager                 $entityManager,
        SearchCriteriaBuilder         $searchCriteriaBuilder,
        ObjectManagerInterface        $objectManager,
        OrderRequestCollection        $orderReqestCollection,
        OrderRequestCollectionFactory $orderRequestCollectionFactory,
        OrderRequestResourceFactory   $orderRequestResourceFactory
    )
    {
        parent::__construct($searchResultsFactory);
        $this->orderRequestFactory = $orderRequestFactory;
        $this->entityManager = $entityManager;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->objectManager = $objectManager;
        $this->orderReqestCollection = $orderReqestCollection;
        $this->orderRequestCollectionFactory = $orderRequestCollectionFactory;
        $this->orderRequestResourceFactory = $orderRequestResourceFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create(): ?OrderRequestInterface
    {
        return $this->orderRequestFactory->create();
    }

    /**
     * {@inheritdoc}
     */
    public function save(OrderRequestInterface $request): OrderRequestInterface
    {
        $classResource = $this->orderRequestResourceFactory->create();
        $classResource->save($request);

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function getById(int $id): ?OrderRequestInterface
    {
        $orderRequest = $this->create();

        $this->entityManager->load($orderRequest, $id);

        return $orderRequest->getId() ? $orderRequest : null;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(OrderRequestInterface $request): bool
    {
        $this->entityManager->delete($request);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById(int $id): bool
    {
        $request = $this->getById($id);

        $this->delete($request);

        return true;
    }

    /**
     * @param int $customerId
     * @param bool $active
     * @return null|OrderRequestInterface
     * @throws NoSuchEntityException
     */
    public function getByCustomerId(int $customerId, bool $active = false): ?OrderRequestInterface
    {
        $this->searchCriteriaBuilder
            ->addFilter(OrderRequestInterface::CUSTOMER_ID, $customerId, 'eq');

        if ($active) {
            $this->searchCriteriaBuilder
                ->addFilter(OrderRequestInterface::STATUS, OrderRequestInterface::STATUS_ENABLE, 'eq');
        }
        $searchCriteria = $this->searchCriteriaBuilder->create();

        $list = $this->getList($searchCriteria);

        return current($list->getItems()) ?: null;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->orderRequestCollectionFactory->create();
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        return $this->fllSearchResults($collection, $searchCriteria);
    }
}
