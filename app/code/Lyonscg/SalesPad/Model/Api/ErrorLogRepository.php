<?php

namespace Lyonscg\SalesPad\Model\Api;

use Exception;
use Lyonscg\SalesPad\Api\Data\AbstractSyncInterface;
use Lyonscg\SalesPad\Api\Data\Api\ErrorLogInterface;
use Lyonscg\SalesPad\Helper\SyncRegistry;
use Lyonscg\SalesPad\Model\Api\ErrorLog;
use Lyonscg\SalesPad\Model\Api\ErrorLogFactory;
use Lyonscg\SalesPad\Model\Config;
use Lyonscg\SalesPad\Model\ResourceModel\Api\ErrorLog as ErrorLogResource;
use Lyonscg\SalesPad\Model\ResourceModel\Api\ErrorLog\Collection as ErrorLogCollection;
use Lyonscg\SalesPad\Model\ResourceModel\Api\ErrorLog\CollectionFactory as ErrorLogCollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;

class ErrorLogRepository implements \Lyonscg\SalesPad\Api\ErrorLogRepositoryInterface
{
    const SECONDS_PER_DAY = 86400;
    const EMPTY_TYPES = ['null', 'NULL' , '[]', '{}', ''];

    /**
     * @var ErrorLogCollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var SearchResultsinterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var ErrorLogResource
     */
    protected $errorLogResource;

    /**
     * @var \Lyonscg\SalesPad\Model\Api\ErrorLogFactory
     */
    protected $errorLogFactory;

    /**
     * @var Json
     */
    protected $serializer;

    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var Config
     */
    private $config;

    /**
     * ErrorLogRepository constructor.
     * @param ErrorLogCollectionFactory $collectionFactory
     * @param SearchResultsinterfaceFactory $searchResultsFactory
     * @param ErrorLogResource $errorLogResource
     * @param \Lyonscg\SalesPad\Model\Api\ErrorLogFactory $errorLogFactory
     * @param Json $serializer
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ErrorLogCollectionFactory $collectionFactory,
        SearchResultsinterfaceFactory $searchResultsFactory,
        ErrorLogResource $errorLogResource,
        ErrorLogFactory $errorLogFactory,
        Json $serializer,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor,
        Config $config
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->errorLogResource = $errorLogResource;
        $this->errorLogFactory = $errorLogFactory;
        $this->serializer = $serializer;
        $this->collectionProcessor = $collectionProcessor;
        $this->config = $config;
    }

    /**
     * @param $logId
     * @return ErrorLogInterface|\Lyonscg\SalesPad\Model\Api\ErrorLog
     * @throws NoSuchEntityException
     */
    public function getById($logId)
    {
        /** @var ErrorLog $errorLog */
        $errorLog = $this->errorLogFactory->create();
        $this->errorLogResource->load($errorLog, $logId);
        if (!$errorLog->getLogId()) {
            throw NoSuchEntityException::singleField(ErrorLog::LOG_ID, $logId);
        }
        return $errorLog;
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }

    public function create($url, $request, $response, $responseCode)
    {
        if (!is_string($url)) {
            $url = $this->serializer->serialize($url);
        }
        if (!is_string($request)) {
            $request = $this->serializer->serialize($request);
        }
        if (!is_string($response)) {
            $response = $this->serializer->serialize($response);
        }
        if (!is_string($responseCode)) {
            $responseCode = $this->serializer->serialize($responseCode);
        }
        /** @var ErrorLog $errorLog */
        $errorLog = $this->errorLogFactory->create();
        $syncRegistryLastEntry = SyncRegistry::getLastEntry();
        /** @var AbstractSyncInterface $syncEntity */
        $syncEntity = $syncRegistryLastEntry ? $syncRegistryLastEntry['value'] : null;

        if ($this->config->isUseEntityIdInLogsAggregation()) {
            $secondAggregator = ['entity' => $syncEntity];
        } else {
            $secondAggregator = ['request' => $request];
        }

        $computed = $this->getComputedFieldsData($url, $secondAggregator);
        $errorLog->setLogId($computed['log_id'])
                 ->setRelatedEntityType($syncEntity ? $syncEntity->getSyncedEntityType() : null)
                 ->setRelatedEntityId($syncEntity ? $syncEntity->getSyncedEntityId() : null)
                 ->setUrl($url)
                 ->setRequest($request)
                 ->setResponse($response)
                 ->setResponseCode($responseCode)
                 ->setIsException(false)
                 ->setCounter(++$computed['counter']);
        $this->errorLogResource->save($errorLog);
        return $errorLog;
    }

    public function createFromException($url, $request, Exception $e)
    {
        if (!is_string($url)) {
            $url = $this->serializer->serialize($url);
        }
        if (!is_string($request)) {
            $request = $this->serializer->serialize($request);
        }
        /** @var ErrorLog $errorLog */
        $errorLog = $this->errorLogFactory->create();
        $syncRegistryLastEntry = SyncRegistry::getLastEntry();
        /** @var AbstractSyncInterface $syncEntity */
        $syncEntity = $syncRegistryLastEntry ? $syncRegistryLastEntry['value'] : null;

        if ($this->config->isUseEntityIdInLogsAggregation()) {
            $secondAggregator = ['entity' => $syncEntity];
        } else {
            $secondAggregator = ['request' => $request];
        }

        $computed = $this->getComputedFieldsData($url, $secondAggregator);
        $errorLog->setLogId($computed['log_id'])
                 ->setRelatedEntityType($syncEntity ? $syncEntity->getSyncedEntityType() : null)
                 ->setRelatedEntityId($syncEntity ? $syncEntity->getSyncedEntityId() : null)
                 ->setUrl($url)
                 ->setRequest($request)
                 ->setResponse($e->getMessage() . "\n\n" . $e->getTraceAsString())
                 ->setResponseCode(0)
                 ->setIsException(true)
                 ->setCounter(++$computed['counter']);
        $this->errorLogResource->save($errorLog);
        return $errorLog;
    }

    public function deleteOldEntries($maxAge)
    {
        $lifetime = $maxAge * self::SECONDS_PER_DAY;
        /** @var ErrorLogCollection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('updated_at', ['to' => date("Y-m-d", time() - $lifetime)]);
        $this->deleteFromCollection($collection);
    }

    public function deleteOnSuccess($url, $request)
    {
        if ($this->config->isUseEntityIdInLogsAggregation()) {
            $syncRegistryLastEntry = SyncRegistry::getLastEntry();
            /** @var AbstractSyncInterface $aggregationEntity */
            $aggregationEntity = $syncRegistryLastEntry ? $syncRegistryLastEntry['value'] : null;
            if ($aggregationEntity) {
                $collection = $this->getFilteredCollection([
                    'url' =>     $url,
                    'related_entity_type' => $aggregationEntity->getSyncedEntityType(),
                    'related_entity_id'   => $aggregationEntity->getSyncedEntityId()
                ]);

                if ($collection->getSize()) {
                    $this->deleteFromCollection($collection);
                }
            }
        }

        $emptiesToProcess = $request === '' ? ['request'] : [];
        $collection = $this->getFilteredCollection([
            'url' =>     $url,
            'request' => $request
        ], $emptiesToProcess);


        if ($collection->getSize()) {
            $this->deleteFromCollection($collection);
        }
    }

    /**
     * @throws NoSuchEntityException
     * @throws Exception
     */
    public function deleteById(int $id)
    {
        $errorLog = $this->getById($id);
        $this->errorLogResource->delete($errorLog);
    }

    private function getFilteredCollection($fields = [], $emptiesToProcess = [])
    {
        $collection = $this->collectionFactory->create();

        foreach ($fields as $key => $value) {
            if (in_array($key, $emptiesToProcess)) {
                $collection->addFieldToFilter([$key, $key], [
                    ['in' => self::EMPTY_TYPES],
                    ['null' => true]
                ]);
            } else {
                $collection->addFieldToFilter($key, $value);
            }
        }

        return $collection;
    }

    private function deleteFromCollection($collection) {
        $select = $collection->getSelect();
        $connection = $this->errorLogResource->getConnection();
        $deleteQuery = $connection->deleteFromSelect($select, 'main_table');
        $connection->query($deleteQuery);
    }

    private function getComputedFieldsData($url, $secondAggregator): array
    {
        $request = $secondAggregator['request'] ?? null;
        $entity = $secondAggregator['entity'] ?? null;

        if ($request !== null) {
            $emptiesToProcess = $request === '' ? ['request'] : [];
            $recordToUpdate = $this->getFilteredCollection([
                'url' =>     $url,
                'request' => $request
            ], $emptiesToProcess)->getFirstItem();
        } else if ($entity !== null) {
            /** @var  AbstractSyncInterface $entity */
            $recordToUpdate = $this->getFilteredCollection([
                'url' =>     $url,
                'related_entity_type' => $entity->getSyncedEntityType(),
                'related_entity_id'   => $entity->getSyncedEntityId()
            ])->getFirstItem();
        } else {
            return [
                'log_id'  => null,
                'counter' => null
            ];
        }

        return [
            'log_id'  => $recordToUpdate->getData('log_id'),
            'counter' => $recordToUpdate->getData('counter')
        ];
    }
}
