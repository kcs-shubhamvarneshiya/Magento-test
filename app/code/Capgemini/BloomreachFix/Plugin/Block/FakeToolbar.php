<?php

namespace Capgemini\BloomreachFix\Plugin\Block;

use Bloomreach\Connector\Block\ConfigurationSettingsInterface;
use Magento\Catalog\Block\Product\ProductList\Toolbar;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\LayeredNavigation\Block\Navigation as Subject;
use Magento\Store\Model\ScopeInterface;

class FakeToolbar
{
    /**
     * @var Http
     */
    private $request;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var array
     */
    private $brCheckItems = [
        'catalogsearch_result_index' => ConfigurationSettingsInterface::SITESEARCH_ENABLED,
        'catalog_category_view'      => ConfigurationSettingsInterface::COLLECTIONS_ENABLED
    ];

    /**
     * @param Http $request
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(Http $request, ScopeConfigInterface $scopeConfig)
    {
        $this->request = $request;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param Subject $subject
     * @return array
     */
    public function beforeToHtml(Subject $subject): array
    {
        $fullActionName = $this->request->getFullActionName();
        $isBrCase = false;

        foreach ($this->brCheckItems as $action => $isEnableConfPath) {
            if ($fullActionName === $action) {
                if ($this->scopeConfig->getValue($isEnableConfPath, ScopeInterface::SCOPE_STORE)) {
                    $isBrCase = true;

                    break;
                }
            }
        }

        if ($isBrCase === false) {

            return [];
        }

        try {
            $layout = $subject->getLayout();
        } catch (\Exception $exception) {

            return [];
        }

        if ($layout->getBlock('product_list_toolbar')) {

            return [];
        }

        if ($layout->getBlock('product_list_toolbar_fake')) {

            return [];
        }

        $toolBarBlock = $layout->createBlock(Toolbar::class, 'product_list_toolbar_fake');
        $toolBarBlock->setCollection($subject->getLayer()->getProductCollection());

        return [];
     }
}
