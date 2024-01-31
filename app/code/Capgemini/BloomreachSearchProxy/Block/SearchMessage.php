<?php

namespace Capgemini\BloomreachSearchProxy\Block;

use Capgemini\BloomreachThematic\Service\Thematic\Search;
use Magento\CatalogSearch\Helper\Data;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class SearchMessage extends Template
{
    protected Search $thematicSearch;
    protected Data $catalogSearchData;

    /**
     * @param Context $context
     * @param Search $thematicSearch
     * @param Data $catalogSearchData
     * @param array $data
     */
    public function __construct(
        Context $context,
        Search $thematicSearch,
        Data $catalogSearchData,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->thematicSearch = $thematicSearch;
        $this->catalogSearchData = $catalogSearchData;
    }

    /**
     * Get Query Correction Results
     *
     * @return array|null
     */
    public function getQueryCorrectionResults()
    {
        $searchResult = $this->thematicSearch->search();
        $results = $searchResult->__toArray();
        $instead = $results['metadata']['query']['modification']['value'] ?? null;
        $didYouMean = $results['metadata']['query']['didYouMean'] ?? null;

        return !$instead || !$didYouMean ? null : [
            'instead'    => $instead,
            'didYouMean' => $didYouMean
        ];
    }

    /**
     * Get search query
     *
     * @return string
     */
    public function getSearchQuery()
    {
        return $this->catalogSearchData->getEscapedQueryText();
    }

    /**
     * Get search URL
     *
     * @param $query
     */
    public function getSearchUrl($query)
    {
        return $this->catalogSearchData->getResultUrl($query);
    }
}
