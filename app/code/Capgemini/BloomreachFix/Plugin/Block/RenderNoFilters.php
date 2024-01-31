<?php

namespace Capgemini\BloomreachFix\Plugin\Block;

use Bloomreach\Connector\Block\ConfigurationSettingsInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\LayeredNavigation\Block\Navigation;
use Magento\Store\Model\ScopeInterface;

class RenderNoFilters
{
    private ScopeConfigInterface $scopeConfig;
    private Http $request;

    public function __construct(Http $request, ScopeConfigInterface $scopeConfig)
    {
        $this->request = $request;
        $this->scopeConfig = $scopeConfig;
    }

    public function afterCanShowBlock(Navigation $subject, bool $result)
    {
        $fullActionName = $this->request->getFullActionName();

        $searchCondition = 'catalogsearch_result_index' === $fullActionName &&
            $this->scopeConfig->isSetFlag(
                ConfigurationSettingsInterface::SITESEARCH_ENABLED,
                ScopeInterface::SCOPE_STORE
            );
        $categoryCondition = 'catalog_category_view' === $fullActionName &&
            $this->scopeConfig->isSetFlag(
                ConfigurationSettingsInterface::COLLECTIONS_ENABLED,
                ScopeInterface::SCOPE_STORE
            );
        $fullCondition = $searchCondition || $categoryCondition;

        return $result && !$fullCondition;
    }
}
