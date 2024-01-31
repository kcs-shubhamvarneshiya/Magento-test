<?php

namespace Lyonscg\CircaLighting\ViewModel;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Noon\HideDefaultStoreCode\Service\Config;

class WebsiteSwitcher implements ArgumentInterface
{
    const MAGENTO_DEFAULT_STORE_CODE = 'default';
    const IMPLIED_DEFAULT_STORE_CODE    = 'us';
    const LINK_PATTERN_WITHOUT_STORE_CODE ='<a href="%s">';
    const LINK_PATTERN_WITH_STORE_CODE ='<a href="%s">';
    const LINK_PATTERN_WITH_STORE_CODE_UK_EU ='<a href="%s%s/">';
    const WEBSITE_SWITCHER_OPTIONS_CONFIG_PATH = 'lyonscg_circalightning_website_switcher/general/switcher_options';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var string
     */
    private $currentStoreCode = '';

    /**
     * @var string
     */
    private $defaultStoreCode = '';

    /**
     * @var array
     */
    private $storeDataUsedInTemplate = [];

    /**
     * @var callable
     */
    private $websitesIterator;

    /**
     * WebsiteSwitcher Constructor.
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(StoreManagerInterface $storeManager, ScopeConfigInterface $scopeConfig)
    {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->websitesIterator = function ($mainArray, $conditionsArray): \Generator
        {
            foreach ($mainArray as $key => $value) {
                if (!in_array($key, $conditionsArray)) {
                    continue;
                }

                yield $key => $value;
            }
        };

        $this->init();
    }

    private function init()
    {
        try {
            $currentStore = $this->storeManager->getStore();
            $this->currentStoreCode = $currentStore->getCode();
            $isRequestSecure = $currentStore->isFrontUrlSecure();
        } catch (\Magento\Framework\Exception\NoSuchEntityException $exception) {
            $isRequestSecure = true;
        }
        $this->defaultStoreCode = $this->storeManager->getDefaultStoreView()->getCode();
        $baseUrlConfigPath = $isRequestSecure ? Store::XML_PATH_SECURE_BASE_URL : Store::XML_PATH_UNSECURE_BASE_URL;
        $websites = $this->storeManager->getWebsites(false, true);
        uasort($websites, function ($a, $b) {

            return $a->getSortOrder() <=> $b->getSortOrder();
        });
        $allowedWebsiteCodes = explode(
            ',',
            $this->scopeConfig->getValue(self::WEBSITE_SWITCHER_OPTIONS_CONFIG_PATH)
        );
        $websitesIterator = $this->websitesIterator;


        if ($this->scopeConfig->getValue(Store::XML_PATH_STORE_IN_URL)) {
            foreach ($websitesIterator($websites, $allowedWebsiteCodes) as $code => $website) {
                $storeCode = $website->getDefaultStore()->getCode();
                $baseUrl = $this->scopeConfig->getValue($baseUrlConfigPath, ScopeInterface::SCOPE_WEBSITE, $code);
                if($storeCode == 'default'){
                    $this->storeDataUsedInTemplate[$storeCode]['link'] = sprintf(self::LINK_PATTERN_WITH_STORE_CODE, $baseUrl);
                } else {
                    $this->storeDataUsedInTemplate[$storeCode]['link'] = sprintf(self::LINK_PATTERN_WITH_STORE_CODE_UK_EU, $baseUrl, $storeCode);
                }
                $this->storeDataUsedInTemplate[$storeCode]['uppercase'] = strtoupper($storeCode);
            }

            if ($this->scopeConfig->getValue(Config::XML_PATH_HIDE_DEFAULT_STORE_CODE)) {
                $this->storeDataUsedInTemplate[$this->defaultStoreCode]['link'] = str_replace(
                    '/' . $this->defaultStoreCode . '/',
                    '/',
                    $this->storeDataUsedInTemplate[$this->defaultStoreCode]['link']
                );
            }
        } else {
            foreach ($websitesIterator($websites, $allowedWebsiteCodes) as $code => $website) {
                $storeCode = $website->getDefaultStore()->getCode();
                $baseUrl = $this->scopeConfig->getValue($baseUrlConfigPath, ScopeInterface::SCOPE_WEBSITE, $code);
                $this->storeDataUsedInTemplate[$storeCode]['link'] = sprintf(self::LINK_PATTERN_WITHOUT_STORE_CODE, $baseUrl);
                $this->storeDataUsedInTemplate[$storeCode]['uppercase'] = strtoupper($storeCode);
            }
        }

        if ($this->currentStoreCode && isset($this->storeDataUsedInTemplate[$this->currentStoreCode])) {
            $this->storeDataUsedInTemplate[$this->currentStoreCode]['link'] = '';
        }

        if (isset($this->storeDataUsedInTemplate[self::MAGENTO_DEFAULT_STORE_CODE])) {
            $this->storeDataUsedInTemplate[self::MAGENTO_DEFAULT_STORE_CODE]['uppercase'] = strtoupper(self::IMPLIED_DEFAULT_STORE_CODE);
        }

    }

    /**
     * @return string
     */
    public function getCurrentStoreCode(): string
    {
        return $this->currentStoreCode;
    }

    /**
     * @return string
     */
    public function getDefaultStoreCode(): string
    {
        return $this->defaultStoreCode;
    }

    /**
     * @return array
     */
    public function getStoreDataUsedInTemplate(): array
    {
        return $this->storeDataUsedInTemplate;
    }
}
