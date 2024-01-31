<?php
/**
 * Wallet Repository Class
 *
 * @category  Lyons
 * @package   Capgemini_Payfabric
 * @author    Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2020 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\MyWallet\Model;

use Capgemini\MyWallet\Api\WalletRepositoryInterface;

/**
 * Class WalletRepository
 * @package Capgemini\MyWallet\Model
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class WalletRepository implements WalletRepositoryInterface
{
    /**
     * @var Wallet[]
     */
    protected $instances = [];

    /**
     * @var WalletFactory
     */
    protected $walletFactory;

    /**
     * @var ResourceModel\Wallet
     */
    protected $walletResource;

    /**
     * @var ResourceModel\Wallet\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Framework\Api\ExtensibleDataObjectConverter
     */
    protected $extensionAttributesJoinProcessor;

    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Api\ExtensibleDataObjectConverter
     */
    protected $dataObjectConverter;

    /**
     * @var \Capgemini\MyWallet\Api\Data\WalletSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaInterface
     */
    protected $searchCriteria;

    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @var \Magento\Framework\Api\Search\FilterGroupFactory
     */
    protected $filterGroupFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $serializer;

    /**
     * WalletRepository constructor
     *
     * @param WalletFactory $walletFactory
     * @param ResourceModel\Wallet $walletResource
     * @param ResourceModel\Wallet\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensibleDataObjectConverter
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Api\ExtensibleDataObjectConverter $dataObjectConverter
     * @param \Capgemini\MyWallet\Api\Data\WalletSearchResultsInterfaceFactory $searchResultsFactory
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     * @param \Magento\Framework\Api\Search\FilterGroupFactory $filterGroupFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        WalletFactory $walletFactory,
        ResourceModel\Wallet $walletResource,
        ResourceModel\Wallet\CollectionFactory $collectionFactory,
        \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensibleDataObjectConverter,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Api\ExtensibleDataObjectConverter $dataObjectConverter,
        \Capgemini\MyWallet\Api\Data\WalletSearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\Search\FilterGroupFactory $filterGroupFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Serialize\SerializerInterface $serializer
    ) {
        $this->walletFactory = $walletFactory;
        $this->walletResource = $walletResource;
        $this->collectionFactory = $collectionFactory;
        $this->extensionAttributesJoinProcessor = $extensibleDataObjectConverter;
        $this->collectionProcessor = $collectionProcessor;
        $this->storeManager = $storeManager;
        $this->dataObjectConverter = $dataObjectConverter;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->searchCriteria = $searchCriteria;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupFactory = $filterGroupFactory;
        $this->date = $date;
        $this->serializer = $serializer;
    }

    /**
     * Creates new instance of Wallet
     *
     * @param $data
     * @return Wallet
     */
    public function create($data)
    {
        return $this->walletFactory->create()->setData($data);
    }

    /**
     * @inheritdoc
     */
    public function save($customerId, \Capgemini\MyWallet\Api\Data\WalletInterface $wallet, $daxUpdate = true)
    {
        $storeId = (int)$this->storeManager->getStore()->getId();
        $existingData = $this->dataObjectConverter
            ->toNestedArray($wallet, [], \Capgemini\MyWallet\Api\Data\WalletInterface::class);
        $existingData['store_id'] = $storeId;

        $existingWallet = $this->getWallet($customerId, $existingData);

        $date = $this->date->gmtDate();
        if ($existingWallet && $existingWallet->getId()) {
            $wallet = $this->get($customerId, $existingWallet->getId(), $storeId);
            $existingData[$wallet->getIdFieldName()] = $wallet->getId();
            $existingData['updated_at'] = $date;
        } else {
            $existingData['created_at'] = $date;
        }

        $wallet->setCustomerId($customerId);
        $wallet->addData($existingData);
        if ($wallet->getIsDefault()) {
            $this->walletResource->clearIsDefaultWalletFlag($customerId);
        }
        try {
            $this->walletResource->save($wallet);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __(
                    'Could not save wallet: %1',
                    $e->getMessage()
                ),
                $e
            );
        }

        unset($this->instances[$wallet->getId()]);
        return $this->get($customerId, $wallet->getId(), $storeId);
    }

    /**
     * {@inheritdoc}
     */
    public function get($customerId, $walletId, $storeId = null)
    {
        $cacheKey = $storeId ?? 'all';
        if (!isset($this->instances[$walletId][$cacheKey])) {
            /** @var Wallet $wallet */
            $wallet = $this->walletFactory->create();
            if (null !== $storeId) {
                $wallet->setStoreId($storeId);
            }
            $this->walletResource->load($wallet, $walletId, 'wallet_id');
            if (!$wallet->getWalletId() || (int)$wallet->getCustomerId() !== (int)$customerId) {
                throw \Magento\Framework\Exception\NoSuchEntityException::singleField('wallet_id', $walletId);
            }
            $this->instances[$walletId][$cacheKey] = $wallet;
        }
        return $this->instances[$walletId][$cacheKey];
    }

    /**
     * {@inheritdoc}
     */
    public function delete($customerId, \Capgemini\MyWallet\Api\Data\WalletInterface $wallet)
    {
        try {
            $wallet = $this->getWallet($customerId, $wallet);

            if ($wallet && $wallet->getWalletId()) {
                $walletId = $wallet->getWalletId();

                if ((int)$wallet->getCustomerId() !== (int)$customerId) {
                    throw new \Magento\Framework\Exception\StateException(
                        __(
                            'Cannot delete wallet with id %1',
                            $wallet->getWalletId()
                        )
                    );
                }

                $this->walletResource->delete($wallet);
                unset($this->instances[$walletId]);
            }
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\StateException(
                __(
                    'Cannot delete wallet with id %1',
                    $wallet->getWalletId()
                ),
                $e
            );
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($customerId, $walletId)
    {
        $wallet = $this->get($customerId, $walletId);
        return  $this->delete($customerId, $wallet);
    }

    /**
     * {@inheritdoc}
     */
    public function getList($customerId, \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Capgemini\MyWallet\Model\ResourceModel\Wallet\Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->extensionAttributesJoinProcessor->process($collection);

        $this->collectionProcessor->process($searchCriteria, $collection);

        $collection->addFieldToFilter('customer_id', ['eq' => $customerId]);

        $collection->load();

        /** @var \Capgemini\MyWallet\Api\Data\WalletSearchResultsInterface $searchResult */
        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        foreach ($collection->getItems() as $wallet) {
            $this->instances[$wallet->getWalletId()]['all'] = $wallet;
        }

        return $searchResult;
    }

    /**
     * Performs on specified non NULL parameters
     *
     * @param null $customerId
     * @param null $make
     * @param null $model
     * @param null $year
     * @return ResourceModel\Wallet\Collection
     */
    public function search($customerId = null, $walletId = null, $cardId = null, $createdAt = null)
    {
        $collection = $this->collectionFactory->create();
        if (!empty($customerId)) {
            $collection->addFieldToFilter('customer_id', ['eq', $customerId]);
        }
        if (!empty($walletId)) {
            $collection->addFieldToFilter('wallet_id', ['eq', $walletId]);
        }

        if (!empty($createdAt)) {
            $collection->addFieldToFilter('created_at', ['eq', $createdAt]);
        }

        return $collection;
    }

    /**
     * Return wallet from database if one exists
     *
     * @param $customerId
     * @param $wallet
     * @return bool|\Capgemini\MyWallet\Api\Data\WalletInterface
     */
    protected function getWallet($customerId, $wallet)
    {
        $searchCriteria = $this->searchCriteria;

        if ($wallet['payfabric_wallet_id']) {
            $cardId = $this->filterBuilder
                ->setField('payfabric_wallet_id')
                ->setValue($wallet['payfabric_wallet_id'])
                ->setConditionType('eq')
                ->create();
        } else {
            $cardId = $this->filterBuilder
                ->setField('payfabric_wallet_id')
                ->setConditionType('null')
                ->create();
        }
        $cardIdFilter = $this->filterGroupFactory->create()->setFilters([$cardId]);

        if ($wallet['cc_last4']) {
            $cardName = $this->filterBuilder
                ->setField('cc_last4')
                ->setValue($wallet['cc_last4'])
                ->setConditionType('eq')
                ->create();
        } else {
            $cardName = $this->filterBuilder
                ->setField('cc_last4')
                ->setConditionType('null')
                ->create();
        }
        $cardNameFilter = $this->filterGroupFactory->create()->setFilters([$cardName]);


        $searchCriteria->setFilterGroups([$cardIdFilter, $cardNameFilter]);

        $searchResult = $this->getList($customerId, $searchCriteria);

        foreach ($searchResult->getItems() as $wallet) {
            return $wallet;
        }

        return false;
    }
}
