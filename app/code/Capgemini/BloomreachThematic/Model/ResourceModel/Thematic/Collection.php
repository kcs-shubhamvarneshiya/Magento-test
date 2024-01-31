<?php

namespace Capgemini\BloomreachThematic\Model\ResourceModel\Thematic;

use Amasty\Shopby\Model\ResourceModel\Fulltext\Collection as Orig;
use Amasty\Shopby\Model\ResourceModel\Fulltext\Collection\SearchCriteriaResolver;
use Amasty\Shopby\Model\ResourceModel\Fulltext\Collection\SearchResultApplier;
use Amasty\Shopby\Model\ResourceModel\Fulltext\Collection\TotalRecordsResolver;
use Amasty\Shopby\Model\Search\SearchCriteriaBuilderProvider;
use Capgemini\BloomreachThematic\Helper\Converter;
use Capgemini\BloomreachThematic\Model\TechnicalProduct;
use Capgemini\BloomreachThematic\Service\Thematic;
use Exception;
use Lyonscg\Catalog\Pricing\Render\Amount\FinalPrice;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Indexer\Product\Flat\State;
use Magento\Catalog\Model\Product\OptionFactory;
use Magento\Catalog\Model\ResourceModel\Helper;
use Magento\Catalog\Model\ResourceModel\Url;
use Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection\SearchCriteriaResolver as MysqlSearchCriteriaResolver;
use Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection\SearchResultApplierInterface;
use Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection\TotalRecordsResolver as MysqlTotalRecordsResolver;
use Magento\CatalogSearch\Model\Search\RequestGenerator;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Eav\Model\Config;
use Magento\Elasticsearch\Model\ResourceModel\Fulltext\Collection\SearchCriteriaResolver as ElasticSearchCriteriaResolver;
use Magento\Elasticsearch\Model\ResourceModel\Fulltext\Collection\TotalRecordsResolver as ElasticTotalRecordsResolver;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteria;
use Magento\Framework\Api\Search\SearchResultFactory;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactory;
use Magento\Framework\DB\Select;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Module\Manager;
use Magento\Framework\Search\EngineResolverInterface;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Validator\UniversalFactory;
use Magento\Search\Api\SearchInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Collection extends Orig
{
    public const MYSQL_ENGINE = 'mysql';


    /**
     * @var SearchCriteriaBuilderProvider
     */
    private $searchCriteriaBuilderProvider;

    /**
     * @var SearchCriteriaResolver
     */
    private $searchCriteriaResolver;

    /**
     * @var array
     */
    private $searchOrders;

    /**
     * @var \Magento\Framework\Api\Search\SearchResultInterface
     */
    private $searchResult;

    /**
     * @var TotalRecordsResolver
     */
    private $totalRecordsResolver;

    /**
     * @var SearchResultApplier
     */
    private $searchResultApplier;

    /**
     * @var string
     */
    private $queryText;

    /**
     * @var string
     */
    private $searchRequestName;

    /**
     * @var SearchCriteriaBuilderProvider
     */
    private $memCriteriaBuilderProvider;

    /**
     * @var EngineResolverInterface
     */
    private $engineResolver;

    /**
     * @var Thematic\Search
     */
    private $thematicSearch;

    /**
     * @var Converter
     */
    private Converter $converter;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(
        EntityFactory $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        Config $eavConfig,
        ResourceConnection $resource,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        Helper $resourceHelper,
        UniversalFactory $universalFactory,
        StoreManagerInterface $storeManager,
        Manager $moduleManager,
        State $catalogProductFlatState,
        ScopeConfigInterface $scopeConfig,
        OptionFactory $productOptionFactory,
        Url $catalogUrl,
        TimezoneInterface $localeDate,
        CustomerSession $customerSession,
        DateTime $dateTime,
        GroupManagementInterface $groupManagement,
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilderProvider $searchCriteriaBuilderProvider,
        SearchInterface $search,
        TotalRecordsResolver $totalRecordsResolver,
        SearchCriteriaResolver $searchCriteriaResolver,
        SearchResultApplier     $searchResultApplier,
        EngineResolverInterface $engineResolver,
        SearchResultFactory     $searchResultFactory,
        Converter               $converter,
        Thematic\Search         $thematicSearch,
        ProductRepositoryInterface $productRepository,
                                $connection = null,
                                $searchRequestName = 'catalog_view_container'
    ) {
        $this->searchRequestName = $searchRequestName;
        $this->customerSession = $customerSession;
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $eavConfig,
            $resource,
            $eavEntityFactory,
            $resourceHelper,
            $universalFactory,
            $storeManager,
            $moduleManager,
            $catalogProductFlatState,
            $scopeConfig,
            $productOptionFactory,
            $catalogUrl,
            $localeDate,
            $customerSession,
            $dateTime,
            $groupManagement,
            $filterBuilder,
            $searchCriteriaBuilderProvider,
            $search,
            $totalRecordsResolver,
            $searchCriteriaResolver,
            $searchResultApplier,
            $engineResolver,
            $searchResultFactory,
            $connection,
            $searchRequestName,
        );

        $this->searchCriteriaBuilderProvider = $searchCriteriaBuilderProvider;
        $this->totalRecordsResolver = $totalRecordsResolver;
        $this->searchCriteriaResolver = $searchCriteriaResolver;
        $this->searchResultApplier = $searchResultApplier;
        $this->engineResolver = $engineResolver;
        $this->converter = $converter;
        $this->thematicSearch = $thematicSearch;
        $this->productRepository = $productRepository;
    }

    /**
     * Add search query filter
     *
     * @param string $query
     * @return $this
     */
    public function addSearchFilter($query)
    {
        $this->queryText = trim($this->queryText . ' ' . $query);
        return $this;
    }

    public function getSearchCriteria(?array $attributeCodesForRemove): SearchCriteria
    {
        $searchCriteriaBuilderProvider = clone $this->searchCriteriaBuilderProvider;
        $this->prepareSearchTermFilter($searchCriteriaBuilderProvider);
        $this->preparePriceAggregation($searchCriteriaBuilderProvider);
        if (is_array($attributeCodesForRemove) && !empty($attributeCodesForRemove)) {
            foreach ($attributeCodesForRemove as $code) {
                $searchCriteriaBuilderProvider->removeFilter($code);
            }
        }

        return $this->getSearchCriteriaResolver($searchCriteriaBuilderProvider)->resolve();
    }

    /**
     * @param array $filter
     * @return SearchCriteria
     */
    public function getMemSearchCriteria(array $filter = []): SearchCriteria
    {
        $searchCriteriaBuilderProvider = clone $this->getMemSearchCriteriaBuilderProvider();

        foreach ($filter as $field => $value) {
            $searchCriteriaBuilderProvider->addFilter($field, $value);
        }

        return $this->getSearchCriteriaResolver($searchCriteriaBuilderProvider)->resolve();
    }

    /**
     * @return SearchCriteriaBuilderProvider
     */
    private function getMemSearchCriteriaBuilderProvider()
    {
        if ($this->memCriteriaBuilderProvider === null) {
            $this->memCriteriaBuilderProvider = clone $this->searchCriteriaBuilderProvider;
            $this->memCriteriaBuilderProvider->addFilter('scope', $this->getStoreId());
            if ($this->queryText) {
                $this->prepareSearchTermFilter($this->memCriteriaBuilderProvider);
            }
        }

        return $this->memCriteriaBuilderProvider;
    }

    /**
     * @param string $model
     * @param string $entityModel
     * @return $this
     */
    protected function _init($model, $entityModel)
    {
        $model = TechnicalProduct::class;

        return parent::_init($model, $entityModel);
    }

    /**
     * @throws LocalizedException
     */
    protected function _renderFiltersBefore()
    {
        if ($this->isLoaded()) {
            return;
        }

        $this->searchResult = $this->thematicSearch->search();
        $this->_totalRecords = $this->getTotalRecordsResolver($this->searchResult)->resolve();

    }

    /**
     * @param SearchResultInterface $searchResult
     * @return MysqlTotalRecordsResolver|ElasticTotalRecordsResolver
     */
    private function getTotalRecordsResolver(SearchResultInterface $searchResult)
    {
        return $this->totalRecordsResolver->getResolver(['searchResult' => $searchResult]);
    }

    private function prepareSearchTermFilter(SearchCriteriaBuilderProvider $searchCriteriaBuilderProvider): void
    {
        if ($this->queryText) {
            $searchCriteriaBuilderProvider->addFilter('search_term', $this->queryText);
        }
    }

    private function preparePriceAggregation(SearchCriteriaBuilderProvider $searchCriteriaBuilderProvider): void
    {
        $priceRangeCalculation = $this->_scopeConfig->getValue(
            \Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory::XML_PATH_RANGE_CALCULATION,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if ($priceRangeCalculation) {
            $searchCriteriaBuilderProvider->addFilter('price_dynamic_algorithm', $priceRangeCalculation);
        }
    }

    /**
     * Set Order field
     *
     * @param string $attribute
     * @param string $dir
     * @return $this
     */
    public function setOrder($attribute, $dir = Select::SQL_DESC)
    {
        $field = (string)$this->_getMappedField($attribute);
        $direction = strtoupper($dir) == self::SORT_ORDER_ASC ? self::SORT_ORDER_ASC : self::SORT_ORDER_DESC;
        $this->searchOrders[$field] = $direction;
        if ($this->isUseDefaultFilterStrategy()) {
            parent::setOrder($attribute, $dir);
        }

        return $this;
    }

    private function isUseDefaultFilterStrategy(): bool
    {
        return $this->engineResolver->getCurrentSearchEngine() == self::MYSQL_ENGINE;
    }

    private function isUseLiveSearchEngine(): bool
    {
        return $this->engineResolver->getCurrentSearchEngine() === self::LIVESEARCH_ENGINE;
    }

    /**
     * Stub method for compatibility with other search engines
     *
     * @return $this
     */
    public function setGeneralDefaultQuery()
    {
        return $this;
    }

    /**
     * @param string $attribute
     * @param string $dir
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function addAttributeToSort($attribute, $dir = self::SORT_ORDER_ASC)
    {
        if ($this->isUseDefaultFilterStrategy()) {
            return parent::addAttributeToSort($attribute, $dir);
        }

        $this->setOrder($attribute, $dir);
        if ($attribute === 'position') {
            // additional sorting rule
            $this->sortById($dir);
        }

        return $this;
    }

    /**
     * @param $field
     * @param null $searchResult
     * @return array
     * @throws StateException
     */
    public function getFacetedData($field, $searchResult = null)
    {
        if (!$searchResult) {
            $this->_renderFilters();
        }

        $searchResult = $searchResult ?: $this->searchResult;

        $result = [];
        $aggregations = $searchResult->getAggregations();
        // This behavior is for case with empty object when we got EmptyRequestDataException
        if (null !== $aggregations) {
            $bucket = $aggregations->getBucket($field . RequestGenerator::BUCKET_SUFFIX);
            if ($bucket) {
                foreach ($bucket->getValues() as $value) {
                    $metrics = $value->getMetrics();
                    $result[$metrics['value']] = $metrics;
                }
            } else {
                //throw new StateException(__("The bucket doesn't exist."));
            }
        }
        return $result;
    }

    /**
     * @param array $visibility
     * @return $this|\Amasty\Shopby\Model\ResourceModel\Fulltext\Collection
     */
    public function setVisibility($visibility)
    {
        $this->addFieldToFilter('visibility', $visibility);

        if ($this->isUseDefaultFilterStrategy()) {
            parent::setVisibility($visibility);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function _renderFilters()
    {
        $this->_filters = [];
        return parent::_renderFilters();
    }

    /**
     * Specify category filter for product collection
     *
     * @param \Magento\Catalog\Model\Category $category
     * @return $this
     */
    public function addCategoryFilter(\Magento\Catalog\Model\Category $category)
    {
        if ($this->isUseDefaultFilterStrategy()) {
            $this->_productLimitationFilters['category_id'] = $category->getId();
        } else {
            $this->setFlag('has_category_filter', true);
            $this->_productLimitationPrice();
        }

        $this->addFieldToFilter('category_ids', $category->getId());

        return $this;
    }

    /**
     * @param false $printQuery
     * @param false $logQuery
     * @return $this|Collection
     * @throws LocalizedException
     */
    public function _loadEntities($printQuery = false, $logQuery = false)
    {
        $this->getEntity();

        $results = $this->searchResult->__toArray();
        $docs = $results['docs'] ?? [];

        if ($this->customerSession->getCustomerGroupId() == 0) {
            $this->doNonLoggedInLoadFlow($docs);
        } else {
            $this->doLoggedInLoadFlow($docs);
        }

        if ($this->getFlag('has_category_filter')) {
            $this->setFlag('has_category_filter', false);
        }

        return $this;
    }

    /**
     * @param mixed $field
     * @param null $condition
     * @return $this|Collection|\Magento\Framework\Data\Collection\AbstractDb
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($this->searchResult !== null) {
            throw new \RuntimeException('Illegal state');
        }

        if (!is_array($condition) || !in_array(key($condition), ['from', 'to'], true)) {
            $this->searchCriteriaBuilderProvider->addFilter($field, $condition);
        } else {
            if (isset($condition['from'])) {
                $this->searchCriteriaBuilderProvider->addFilter("{$field}.from", $condition['from']);
            }
            if (isset($condition['to'])) {
                $this->searchCriteriaBuilderProvider->addFilter("{$field}.to", $condition['to']);
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function _beforeLoad()
    {
        // prevent sorting direction override
        if (empty($this->searchOrders['entity_id'])) {
            // additional sorting with the fewest priority
            $this->setOrder('entity_id');
        }

        return parent::_beforeLoad();
    }

    private function getSearchResultApplier(SearchResultInterface $searchResult): SearchResultApplierInterface
    {
        return $this->searchResultApplier->getApplier(
            [
                'collection' => $this,
                'searchResult' => $searchResult,
                'orders' => $this->_orders,
                'size' => $this->getPageSize(),
                'currentPage' => (int)$this->_curPage,
            ]
        );
    }

    /**
     * @param SearchCriteriaBuilderProvider|null $searchCriteriaBuilder
     * @return MysqlSearchCriteriaResolver|ElasticSearchCriteriaResolver
     */
    private function getSearchCriteriaResolver(SearchCriteriaBuilderProvider $searchCriteriaBuilderProvider = null)
    {
        $builder = $searchCriteriaBuilderProvider
            ? $searchCriteriaBuilderProvider->create()
            : $this->searchCriteriaBuilderProvider->create();

        return $this->searchCriteriaResolver->getResolver(
            [
                'builder' => $builder,
                'collection' => $this,
                'searchRequestName' => $this->searchRequestName,
                'currentPage' => (int)$this->_curPage,
                'size' => $this->getPageSize(),
                'orders' => $this->searchOrders,
            ]
        );
    }

    /**
     * Additional sorting rule with search engine resolver.
     *
     * Resolve issue, when position of products is the same and ASC and DESC dir return the same value.
     */
    private function sortById(string $dir): void
    {
        if ($this->isUseLiveSearchEngine()) {
            /**
             * Temporary disable additional sorting for Live Search.
             *
             * LiveSearch can't sort by product_id due error
             * "attribute product_id is not defined as sortable in magento admin".
             * product_id is mapped by Core but doesn't have sorting permissions.
             *
             * Sorting by entity_id is restricted in
             * @see \Magento\LiveSearchAdapter\Model\QueryArgumentProcessor\SortQueryArgumentProcessor
             */
            return;
        }

        /**
         * Elastic search restrict to sort by entity_id. But allow to sort on mapped field.
         *
         * @see \Amasty\Shopby\Plugin\Elasticsearch\Model\Adapter\AdditionalFieldMapper
         */
        $this->setOrder('product_id', $dir);
    }

    private function doNonLoggedInLoadFlow(array $docs)
    {
        $position = 0;

        foreach ($docs as $doc) {
            $productData = $this->converter->convertProductData($doc);
            $productData[TechnicalProduct::IS_THEMATIC_PRODUCT_DATA_KEY] = true;
            $productData['entity_id'] = $productData['sku'];
            $productData['row_id'] = $productData['sku'];
            $productData['type_id'] = Configurable::TYPE_CODE;

            unset($productData['price']);
            unset($productData['special_price']);


            if ($this->getFlag('has_category_filter')) {
                $productData['cat_index_position'] = $position++;
                //$magentoProduct->setData('cat_index_position', $position++);
            }


            /** @var TechnicalProduct $product */
            $product = $this->getNewEmptyItem()->setData($productData);

            $variations = $product->getData('variants') ?? [];

            if (!empty($variations)) {
                $usedProducts = [];

                foreach ($variations as $variation) {
                    $variationData = $this->converter->convertProductData($variation);
                    $variationData[TechnicalProduct::IS_THEMATIC_PRODUCT_DATA_KEY] = true;
                    $variationData['name'] = $productData['name'];
                    $variationData['basecode'] = $productData['sku'];
                    $variationData[FinalPrice::BASECODE_ATTRIBUTE] = 1;

                    /** @var TechnicalProduct $variation */
                    $variation = $this->getNewEmptyItem()->setData($variationData);
                    $variation->setId($variationData['sku']);

                    $usedProducts[] = $variation;
                }

                $product->setData('_cache_instance_products', $usedProducts);
            }

            try {
                if(!isset($this->_items[$product->getId()])){
                    $this->addItem($product);
                }
            } catch (\Exception $exception) {
                $this->_logger->error($exception->getMessage(), ['method' => __METHOD__]);

                continue;
            }

            if (isset($this->_itemsById[$product->getId()])) {
                $this->_itemsById[$product->getId()][] = $product;
            } else {
                $this->_itemsById[$product->getId()] = [$product];
            }
        }
    }
    private function doLoggedInLoadFlow(array $docs)
    {
        $position = 0;

        foreach ($docs as $doc) {
            $productData = $this->converter->convertProductData($doc);
            $productData[TechnicalProduct::IS_THEMATIC_PRODUCT_DATA_KEY] = true;
            unset($productData['price']);
            unset($productData['special_price']);

            try {
                $magentoProduct = $this->productRepository->get($productData['sku']);
            } catch (Exception $exception) {
                $this->_logger->error($exception->getMessage(), ['method' => __METHOD__]);

                continue;
            }

            if ($this->getFlag('has_category_filter')) {
                $magentoProduct->setData('cat_index_position', $position++);
            }

            $magentoProduct->addData($productData);
            /** @var TechnicalProduct $product */
            $product = $this->getNewEmptyItem()->setData($magentoProduct->getData());

            $variations = $product->getData('variants') ?? [];

            if (!empty($variations)) {
                $usedProducts = [];

                foreach ($variations as $variation) {
                    $variationData = $this->converter->convertProductData($variation);
                    $variationData[TechnicalProduct::IS_THEMATIC_PRODUCT_DATA_KEY] = true;
                    /** @var TechnicalProduct $variation */
                    unset($variationData['price']);
                    unset($variationData['special_price']);

                    try {
                        $magentoVariation = $this->productRepository->get($variationData['sku']);
                    } catch (Exception $exception) {
                        $this->_logger->error($exception->getMessage(), ['method' => __METHOD__]);

                        continue;
                    }

                    $magentoVariation->addData($variationData);
                    $variation = $this->getNewEmptyItem()->setData($magentoVariation->getData());
                    $variation->unsetData('quantity_and_stock_status');
                    $usedProducts[] = $variation;
                }

                $product->setData('_cache_instance_products', $usedProducts);
            }

            try {
                if(!isset($this->_items[$product->getId()])){
                    $this->addItem($product);
                }
            } catch (\Exception $exception) {
                $this->_logger->error($exception->getMessage(), ['method' => __METHOD__]);

                continue;
            }

            if (isset($this->_itemsById[$product->getId()])) {
                $this->_itemsById[$product->getId()][] = $product;
            } else {
                $this->_itemsById[$product->getId()] = [$product];
            }
        }
    }
}
