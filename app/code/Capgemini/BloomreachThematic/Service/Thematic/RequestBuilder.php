<?php

namespace Capgemini\BloomreachThematic\Service\Thematic;

use Amasty\ShopbyBase\Model\FilterSettingRepository;
use Amasty\ShopbyBase\Model\Source\DisplayMode;
use Capgemini\BloomreachThematic\Helper\Converter;
use Capgemini\BloomreachThematic\Helper\Data as ModuleHelper;
use Capgemini\BloomreachThematic\Service\Thematic\Request as ThematicRequest;
use Magento\Catalog\Block\Product\ProductList\Toolbar;

class RequestBuilder
{
    private ModuleHelper $moduleHelper;
    private Converter $converter;
    private FilterSettingRepository $filterSettingRepository;
    private Toolbar $toolbar;

    public function __construct(
        ModuleHelper $moduleHelper,
        Converter $converter,
        FilterSettingRepository $filterSettingRepository,
        Toolbar $toolbar
    ) {
        $this->moduleHelper = $moduleHelper;
        $this->converter = $converter;
        $this->filterSettingRepository = $filterSettingRepository;
        $this->toolbar = $toolbar;
    }

    public function build($incomingRequestParams = null): ThematicRequest
    {
        $processedIncReqParams = $this->processIncReqParams($incomingRequestParams);

        $thematicRequest = new ThematicRequest();
        $thematicRequest->addParam('account_id', $this->moduleHelper->getAccountId());
        $thematicRequest->addParam('auth_key', $this->moduleHelper->getAuthKey());
        $thematicRequest->addParam('domain_key', $this->moduleHelper->getDomainKey());
        $thematicRequest->addParam('request_id', $this->moduleHelper->getRequestId());
        $thematicRequest->addParam('url', $this->moduleHelper->getUrl());
        $thematicRequest->addParam('ref_url', $this->moduleHelper->getRefUrl());
        $thematicRequest->addParam('_br_uid_2', $this->moduleHelper->getTrackingCookie());
        $thematicRequest->addParam('request_type', $this->moduleHelper->getRequestType());
        $thematicRequest->addParam('search_type', $this->moduleHelper->getSearchType());
        $thematicRequest->addParam('q', $this->moduleHelper->getPageKey());
        $thematicRequest->addParam('rows', $this->moduleHelper->getRows());
        $thematicRequest->addParam('start', $processedIncReqParams['start']);
        $thematicRequest->addParam('fl', $this->moduleHelper->getProductAttributes());
        $thematicRequest->addParam('user_id', $this->moduleHelper->getUserId());
        $thematicRequest->addParam('user_ip', $this->moduleHelper->getUserIp());
        $thematicRequest->addParam('user_agent', $this->moduleHelper->getUserAgent());

        if (!empty($processedIncReqParams['sort'])) {
            $thematicRequest->addParam('sort', $processedIncReqParams['sort']);
        }

        if (!empty($processedIncReqParams['fq'])) {
            $thematicRequest->addParam('fq', $processedIncReqParams['fq']);
        }

        try {
            $priceDisplayMode = $this->filterSettingRepository
                ->getFilterSetting('price')
                ->getDisplayMode();
        } catch (\Exception $exception) {
            $this->moduleHelper->logWarning('Could not get price display mode.');
            $priceDisplayMode = null;
        }

        if ($priceDisplayMode === DisplayMode::MODE_SLIDER) {
            $thematicRequest->addParam('stats.field', 'price');
            $thematicRequest->addParam('facet.range', 'price');
        }

        $thematicRequest->addToNeedEncoding('url');
        $thematicRequest->addToNeedEncoding('ref_url');
        $thematicRequest->addToNeedEncoding('_br_uid_2');
        $thematicRequest->addToNeedEncoding('fl');
        $thematicRequest->addToNeedEncoding('user_agent');
        $thematicRequest->addToNeedEncoding('sort');

        if (!empty($processedIncReqParams['fq'])) {
            $thematicRequest->addToNeedEncoding('fq');
        }

        return $thematicRequest;
    }

    private function processIncReqParams($incomingParams)
    {
        $outputParams = [];

        $pageNumber = $incomingParams['p'] ?? 1;
        $pageSize = $this->moduleHelper->getRows();
        $outputParams['start'] = $pageSize * ($pageNumber -1);
        unset($incomingParams['p']);

        $sortParam = $this->toolbar->getCurrentOrder();
        if ($sortParam) {
            if (str_starts_with($sortParam, 'price')) {
                $sortParam = str_replace('_', ' ', $sortParam);
                $outputParams['sort'] = str_replace('price', 'low_price', $sortParam);
            } elseif ($sortParam !== 'position') {
                $outputParams['sort'] = $sortParam;
            } else {
                $outputParams['sort'] = null;
            }

            unset($incomingParams['product_list_order']);
        } else {
            $outputParams['sort'] = null;
        }

        $outputParams['fq'] = [];

        foreach ($incomingParams as $name => $value) {
            if (!in_array($name, $this->converter->getAvailableFacets())) {

                continue;
            }

            if ($name === 'price') {
                $rangeEdges = explode('-', $value);
                $rangeEdges[1] = $rangeEdges[1] ?? $rangeEdges[0];
                $outputParams['fq'][] = $name . ':' . sprintf('[%d TO %d]', $rangeEdges[0], $rangeEdges[1]);
            } else {
                $orString = $name . ':';
                $complex = explode(',', $value);
                foreach ($complex as $simple) {
                    $label = $this->converter->getFacetLabelByValue($name, $simple);
                    $label = str_replace('\\', '\\\\', $label);
                    $label = str_replace('"', '\"', $label);
                    $orString .= '"' . $label . '"' . ' OR ';
                }
                $orString = substr($orString, 0, -4);
                $outputParams['fq'][] = $orString;
            }
        }

        return $outputParams;
    }
}
