<?php

namespace Capgemini\BloomreachThematic\Controller;

use Capgemini\BloomreachThematic\Controller\Theme\View;
use Capgemini\BloomreachThematic\Helper\Data as ModuleHelper;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class Router implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    protected ActionFactory $actionFactory;
    /**
     * @var ModuleHelper
     */
    private ModuleHelper $moduleHelper;
    private CustomerSession $customerSession;
    private StoreManagerInterface $storeManager;

    /**
     * @param ActionFactory $actionFactory
     * @param ModuleHelper $moduleHelper
     * @param CustomerSession $customerSession
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ActionFactory $actionFactory,
        ModuleHelper $moduleHelper,
        CustomerSession $customerSession,
        StoreManagerInterface $storeManager
    ) {
        $this->actionFactory = $actionFactory;
        $this->moduleHelper = $moduleHelper;
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
    }

    /**
     * @param RequestInterface $request
     * @return false|ActionInterface
     */
    public function match(RequestInterface $request)
    {
        /** @var Http $request */
        $identifier = trim($request->getPathInfo(), '/');
        $urlPrefix = $this->moduleHelper->getThematicUrlPrefix() ?? '';
        if($urlPrefix !== '' && str_contains($identifier, $urlPrefix)) {
            try {
                $rootCategoryId = $this->storeManager->getStore()->getRootCategoryId();
            } catch (\Exception $exception) {
                $this->moduleHelper->logError($exception->getMessage());

                return false;
            }
            $pageTheme = substr($identifier, strpos($identifier, '/') + 1);
            $request->setParam(View::CATEGORY_FOR_REGISTRY_PARAM_NAME, $rootCategoryId)
                ->setModuleName('capgemini_bloomreach_thematic')
                ->setControllerName('theme')
                ->setActionName('view');
            $request->setAlias(UrlInterface::REWRITE_REQUEST_PATH_ALIAS, $identifier);
            $this->moduleHelper->setRequestType('thematic');
            $this->moduleHelper->setSearchType('keyword');
            $this->moduleHelper->setPageKey($pageTheme);
            $this->moduleHelper->setUserId($this->customerSession->getCustomerId());
        } else {
            return false;
        }
        return $this->actionFactory->create(Forward::class);
    }
}
