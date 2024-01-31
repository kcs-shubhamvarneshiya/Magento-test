<?php

namespace Capgemini\BloomreachSearchProxy\Helper;
use Bloomreach\Connector\Block\ConfigurationSettingsInterface;
use Capgemini\BloomreachThematic\Helper\Data as Orig;

class Data extends Orig
{
    const XML_CONFIG_PATH_SITE_SEARCH_PROXY_ENABLED =
        ConfigurationSettingsInterface::SITESEARCH_PATH . '/enabled_proxy_search';

    public function getIsSearchProxyEnabled()
    {
        return $this->getStoreScopeConfigValue(self::XML_CONFIG_PATH_SITE_SEARCH_PROXY_ENABLED);
    }
}
