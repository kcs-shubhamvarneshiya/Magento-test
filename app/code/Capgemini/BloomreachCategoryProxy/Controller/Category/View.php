<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Capgemini\BloomreachCategoryProxy\Controller\Category;

use Capgemini\BloomreachCategoryProxy\Helper\Data as ModuleHelper;
use Capgemini\BloomreachThematic\Helper\Output;
use Capgemini\BloomreachThematic\Service\Thematic\Search;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Helper\Category as CategoryHelper;
use Magento\Catalog\Model\Category\Attribute\LayoutUpdateManager;
use Magento\Catalog\Model\Design;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Product\ProductList\ToolbarMemorizer;
use Magento\Catalog\Model\Session;
use Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Capgemini\BloomreachThematic\Controller\Theme\View as ThematicView;
use Magento\Catalog\Controller\Category\View as BaseView;

class View extends ThematicView
{
    /**
     * @var Http
     */
    protected $_request;
    private ModuleHelper $moduleHelper;
    private CustomerSession $customerSession;

    public function __construct(
        Context $context,
        Design                      $catalogDesign,
        Session                     $catalogSession,
        Registry                    $coreRegistry,
        StoreManagerInterface       $storeManager,
        CategoryUrlPathGenerator    $categoryUrlPathGenerator,
        PageFactory                 $resultPageFactory,
        ForwardFactory              $resultForwardFactory,
        Resolver                    $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        Search                      $thematicSearch,
        Output                      $description,
        ModuleHelper                $moduleHelper,
        CustomerSession             $customerSession,
        ToolbarMemorizer            $toolbarMemorizer = null,
        ?LayoutUpdateManager        $layoutUpdateManager = null,
        CategoryHelper              $categoryHelper = null,
        LoggerInterface             $logger = null
    ) {
        parent::__construct(
            $context,
            $catalogDesign,
            $catalogSession,
            $coreRegistry,
            $storeManager,
            $categoryUrlPathGenerator,
            $resultPageFactory,
            $resultForwardFactory,
            $layerResolver,
            $categoryRepository,
            $thematicSearch,
            $description,
            $toolbarMemorizer,
            $layoutUpdateManager,
            $categoryHelper,
            $logger
        );

        $this->moduleHelper = $moduleHelper;
        $this->customerSession = $customerSession;
    }

    public function execute()
    {
        if ($this->moduleHelper->getIsCategoryProxyEnabled()) {
            $categoryId = $this->getRequest()->getParam('id');

            $this->moduleHelper->setRequestType('search');
            $this->moduleHelper->setSearchType('category');
            $this->moduleHelper->setPageKey($categoryId);
            $this->moduleHelper->setUserId($this->customerSession->getCustomerId());

            $this->_request
                ->setParam(ThematicView::CATEGORY_FOR_REGISTRY_PARAM_NAME, $categoryId)
                ->setRouteName('capgemini_bloomreach_category_proxy')
                ->setParam('id', $categoryId);
        }

        return BaseView::execute();
    }

    protected function _initCategory()
    {
        if (!$this->moduleHelper->getIsCategoryProxyEnabled()) {

            return BaseView::_initCategory();
        }

        return parent::_initCategory();
    }
}
