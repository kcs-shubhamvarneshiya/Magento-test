<?php

namespace Capgemini\BloomreachThematic\Service\Thematic;

use Capgemini\BloomreachThematic\Helper\Converter;
use Capgemini\BloomreachThematic\Helper\Data as ModuleHelper;
use Capgemini\BloomreachThematic\Service\Thematic;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Api\Search\SearchResultFactory;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Search\Response\Aggregation;
use Magento\Framework\Search\Response\Aggregation\ValueFactory;
use Magento\Framework\Search\Response\AggregationFactory;
use Magento\Framework\Search\Response\BucketFactory;
use Magento\Framework\Serialize\SerializerInterface;

class Search
{
    private SearchResultFactory $searchResultFactory;
    private ValueFactory $aggregationValueFactory;
    private AggregationFactory $aggregationFactory;
    private BucketFactory $bucketFactory;
    private RequestBuilder $requestBuilder;
    private SerializerInterface $serializer;
    private Thematic $thematic;
    private ModuleHelper $moduleHelper;
    private Converter $converter;
    private array $cachedSearchResults = [];

    public function __construct(
        SearchResultFactory $searchResultFactory,
        ValueFactory $aggregationValueFactory,
        AggregationFactory $aggregationFactory,
        BucketFactory $bucketFactory,
        SerializerInterface $serializer,
        Converter $converter,
        RequestBuilder $requestBuilder,
        Thematic $thematic,
        ModuleHelper $moduleHelper
    ) {
        $this->searchResultFactory = $searchResultFactory;
        $this->aggregationValueFactory = $aggregationValueFactory;
        $this->aggregationFactory = $aggregationFactory;
        $this->bucketFactory = $bucketFactory;
        $this->serializer = $serializer;
        $this->converter = $converter;
        $this->requestBuilder = $requestBuilder;
        $this->thematic = $thematic;
        $this->moduleHelper = $moduleHelper;
    }

    /**
     * @param array $requestParams
     * @param bool $useCache
     * @return SearchResultInterface
     */
    public function search(array $requestParams = [], bool $useCache = true): SearchResultInterface
    {
        $requestParams = $requestParams ?: $this->moduleHelper->getRequestParams();
        $hash = md5($this->serializer->serialize($requestParams));

        if ($useCache === true) {
            $cashedSearchResult = $this->cachedSearchResults[$hash] ?? null;

            if ($cashedSearchResult !== null) {

                return $cashedSearchResult;
            }
        }

        $searchResult = $this->searchResultFactory->create();

        try {
            $thematicRequest = $this->requestBuilder->build($requestParams);
            $thematicData = $this->thematic->getThematicData($thematicRequest);
        } catch (Exception|GuzzleException $exception) {
            $this->moduleHelper->logWarning($exception->getMessage());

            return clone $searchResult;
        }

        $size = $thematicData['response']['numFound'];
        $standardFacets = $thematicData['facet_counts']['facet_fields'] ?? [];
        $priceFacet = [];
        $priceRanges = $thematicData['facet_counts']['facet_ranges'] ?? null;
        $priceStats = $thematicData['stats']['stats_fields'] ?? null;

        if ($priceRanges && $priceStats) {
            $priceStats['price']['count'] = $size;
            $priceStats['price'] = [$priceStats['price']];
            $rangeEnd = floor($priceStats['price'][0]['max']);
            $auxiliary = pow(10, 1 - strlen($rangeEnd));
            $rangeEnd = ceil($rangeEnd * $auxiliary) / $auxiliary;
            $priceRanges['price'][count($priceRanges['price']) - 1]['end'] = $rangeEnd;
            $priceFacet = array_merge_recursive($priceRanges, $priceStats);
        }

        $aggregations = $this->getAggregations(array_merge($standardFacets, $priceFacet));

        $searchResult->setAggregations($aggregations);
        $searchResult->setTotalCount($size);
        $searchResult->setData('docs', $thematicData['response']['docs']);

        if (isset($thematicData['page_header'])) {
            $searchResult->setData('page_header', $thematicData['page_header']);
        }

        if (isset($thematicData['metadata'])) {
            $searchResult->setData('metadata', $thematicData['metadata']);
        }

        $this->cachedSearchResults[$hash] = $searchResult;

        return $searchResult;
    }

    /**
     * @param $facets
     * @return Aggregation
     */
    private function getAggregations($facets): Aggregation
    {
        $buckets = [];

        foreach ($facets as $facetFieldName => $facetFieldData) {
            if (!$bucketName = $this->converter->getBucketName($facetFieldName)) {

                continue;
            }

            $values = [];

            foreach ($facetFieldData as $facetFieldDatum) {
                $rowAggregationValue = $this->converter->convertFacetData($facetFieldName, $facetFieldDatum);
                $values[] = $this->aggregationValueFactory->create($rowAggregationValue);
            }

            $buckets[$bucketName] = $this->bucketFactory->create([
                'name' => $bucketName,
                'values' => $values
            ]);
        }

        return $this->aggregationFactory->create(['buckets' => $buckets]);
    }
}
