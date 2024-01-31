<?php

namespace Lyonscg\CircaLighting\Plugin;

use Magento\Backend\Controller\Adminhtml\System\Store\Save;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Cache\Type\Config as ConfigCache;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Lyonscg\CircaLighting\ViewModel\WebsiteSwitcher as Switcher;
use Magento\PageCache\Model\Cache\Type as PageCache;

class WebsiteSwitcher
{
    /**
     * @var WriterInterface
     */
    private $configWriter;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var TypeListInterface
     */
    private $cacheTypeList;

    public function __construct(
        WriterInterface $configWriter,
        ScopeConfigInterface $scopeConfig,
        TypeListInterface $cacheTypeList
    ) {
        $this->configWriter = $configWriter;
        $this->scopeConfig = $scopeConfig;
        $this->cacheTypeList = $cacheTypeList;
    }

    public function afterExecute(Save $subject, Redirect $result)
    {
        if ($subject->getRequest()->isPost() && ($postData = $subject->getRequest()->getPostValue())) {
            if($postData['website']['is_default'] ?? false) {
                $this->manageConfiguration($postData['website']['code']);
            }

        }

        return $result;
    }

    private function manageConfiguration($defaultWebsiteCode)
    {
        $allowedWebsiteCodes = explode(
            ',',
            $this->scopeConfig->getValue(Switcher::WEBSITE_SWITCHER_OPTIONS_CONFIG_PATH)
        );

        if (!in_array($defaultWebsiteCode, $allowedWebsiteCodes)) {
            array_push($allowedWebsiteCodes, $defaultWebsiteCode);
            $allowedWebsiteCodes = implode(',', $allowedWebsiteCodes);
            $this->configWriter->save(
                Switcher::WEBSITE_SWITCHER_OPTIONS_CONFIG_PATH,
                $allowedWebsiteCodes
            );
            $this->cacheTypeList->cleanType(ConfigCache::TYPE_IDENTIFIER);
            $this->cacheTypeList->cleanType(PageCache::TYPE_IDENTIFIER);
        }
    }
}
