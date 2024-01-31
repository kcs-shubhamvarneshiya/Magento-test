<?php

namespace Lyonscg\CircaLighting\Plugin;

use Exception;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Store\App\Request\StorePathInfoValidator;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Noon\HideDefaultStoreCode\Service\Config;

class StorePathInfoValidatorPlugin
{
    /**
     * @var string
     */
    private $defaultStoreViewCode = '';

    /**
     * @var bool
     */
    private $isNeedToExecute = false;

    /**
     * @var array
     */
    private $storeCodes = [];

    /**
     * @var array
     */
    private $pathsArray = [];

    /**
     * StorePathInfoValidatorPlugin constructor.
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(StoreManagerInterface $storeManager, ScopeConfigInterface $scopeConfig)
    {
        try {
            $this->defaultStoreViewCode = $storeManager->getDefaultStoreView()->getCode();
        } catch (Exception $exception) {

            return;
        }
        $this->isNeedToExecute = !!$this->defaultStoreViewCode
            || !!$scopeConfig->getValue(Store::XML_PATH_STORE_IN_URL)
            || !!$scopeConfig->getValue(Config::XML_PATH_HIDE_DEFAULT_STORE_CODE, ScopeInterface::SCOPE_STORE);

        if (!$this->isNeedToExecute) {

            return;
        }

        $this->storeCodes = array_keys($storeManager->getStores(false, true));
    }

    /**
     * @param StorePathInfoValidator $subject
     * @param Http $request
     * @param string $pathInfo
     * @return array
     */
    public function beforeGetValidStoreCode(
        StorePathInfoValidator $subject,
        Http $request,
        string $pathInfo = ''

    ): array
    {
        if (!$this->isNeedToExecute) {

            return [$request, $pathInfo];
        }

        if (isset($this->pathsArray[$pathInfo])) {

            return [$request, $this->pathsArray[$pathInfo]];
        }

        $pathToProcess = (!$pathInfo) ? $request->getRequestUri() : $pathInfo;

        $assumedStoreCode = $this->getStoreCode($pathToProcess);

        if (!$assumedStoreCode || $assumedStoreCode === 'admin') {

            return [$request, $pathInfo];
        }

        if (!in_array($assumedStoreCode, $this->storeCodes)) {
            $this->pathsArray[$pathInfo] = '/' . $this->defaultStoreViewCode . '/' . ltrim($pathToProcess, '/');
        } else {
            $this->pathsArray[$pathInfo] = $pathToProcess;
        }

        return [$request, $this->pathsArray[$pathInfo]];
    }

    /**
     * Get store code from path info string
     *
     * @param string $pathInfo
     * @return string
     */
    private function getStoreCode(string $pathInfo) : string
    {
        $pathParts = explode('/', ltrim($pathInfo, '/'), 2);
        return current($pathParts);
    }
}
