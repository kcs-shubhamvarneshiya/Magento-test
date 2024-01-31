<?php

namespace Capgemini\BloomreachCategoryProxy\Helper;

use Bloomreach\Connector\Block\ConfigurationSettingsInterface;
use Capgemini\BloomreachThematic\Helper\Data as Orig;

class Data extends Orig
{
    const XML_CONFIG_PATH_COLLECTIONS_PROXY_ENABLED =
        ConfigurationSettingsInterface::COLLECTIONS_PATH . '/enabled_proxy_category';

    public function getIsCategoryProxyEnabled()
    {
        return $this->getStoreScopeConfigValue(self::XML_CONFIG_PATH_COLLECTIONS_PROXY_ENABLED);
    }
}
