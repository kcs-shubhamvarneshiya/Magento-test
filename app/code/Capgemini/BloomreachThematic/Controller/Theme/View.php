<?php

namespace Capgemini\BloomreachThematic\Controller\Theme;

use Capgemini\BloomreachThematic\Helper\Output;
use Capgemini\BloomreachThematic\Service\Thematic\Search;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Controller\Category\View as Orig;
use Magento\Catalog\Helper\Category as CategoryHelper;
use Magento\Catalog\Model\Category\Attribute\LayoutUpdateManager;
use Magento\Catalog\Model\Design;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Product\ProductList\ToolbarMemorizer;
use Magento\Catalog\Model\Session;
use Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Theme\Block\Html\Breadcrumbs;
use Psr\Log\LoggerInterface;

class View extends Orig
{
    const CATEGORY_FOR_REGISTRY_PARAM_NAME = 'category_for_registry';

    /**
     * @var ToolbarMemorizer|mixed
     */
    private mixed $toolbarMemorizer;
    /**
     * @var mixed|LoggerInterface
     */
    private mixed $logger;
    /**
     * @var Search
     */
    private Search $thematicSearch;
    /**
     * @var Output
     */
    private Output $outputHelper;
    /**
     * @var CategoryRepositoryInterface
     */
    private $category;

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
        Output                      $outputHelper,
        ToolbarMemorizer            $toolbarMemorizer = null,
        ?LayoutUpdateManager        $layoutUpdateManager = null,
        CategoryHelper              $categoryHelper = null,
        LoggerInterface             $logger = null
    ) {
        parent::__construct($context,
            $catalogDesign,
            $catalogSession,
            $coreRegistry,
            $storeManager,
            $categoryUrlPathGenerator,
            $resultPageFactory,
            $resultForwardFactory,
            $layerResolver,
            $categoryRepository,
            $toolbarMemorizer,
            $layoutUpdateManager,
            $categoryHelper,
            $logger
        );
        $this->thematicSearch = $thematicSearch;
        $this->outputHelper = $outputHelper;
    }

    /**
     * @return bool|\Magento\Catalog\Api\Data\CategoryInterface|\Magento\Catalog\Model\Category
     */
    protected function _initCategory()
    {
        $this->toolbarMemorizer = ObjectManager::getInstance()->get(ToolbarMemorizer::class);
        $this->logger = ObjectManager::getInstance()->get(LoggerInterface::class);
        $categoryId = $this->getRequest()->getParam(self::CATEGORY_FOR_REGISTRY_PARAM_NAME);
        $this->getRequest()->setParam(self::CATEGORY_FOR_REGISTRY_PARAM_NAME, null);

        try {
            $this->category = $this->categoryRepository->get($categoryId, $this->_storeManager->getStore()->getId());
        } catch (NoSuchEntityException $e) {
            return false;
        }

        if ($this->category->getParentId() == 1) {
            $searchResult = $this->thematicSearch->search();
            $results = $searchResult->__toArray();
            $pageHeader = $results['page_header'] ?? [];
            $data = [
                'name'                  => $pageHeader['h1'] ?? '',
                'meta_title'            => $pageHeader['title'] ?? '',
                'meta_description'      => $pageHeader['meta_description'] ?? '',
                'meta_keywords'         => $pageHeader['meta_keywords'] ?? '',
                'content_placement_1'   => $pageHeader['content_placement_1'] ?? '',
                'content_placement_2'   => $pageHeader['content_placement_2'] ?? '',
                'content_placement_3'   => $pageHeader['content_placement_3'] ?? '',
            ];
            $this->category->addData($data);
            $outputHelper = $this->outputHelper->prepareDescription($this->category);
            $this->category->setData('description', $outputHelper);
        }

        $this->_catalogSession->setLastVisitedCategoryId($this->category->getId());
        $this->_coreRegistry->register('current_category', $this->category);
        $this->toolbarMemorizer->memorizeParams();
        try {
            $this->_eventManager->dispatch(
                'catalog_controller_category_init_after',
                ['category' => $this->category, 'controller_action' => $this]
            );
        } catch (LocalizedException $e) {
            $this->logger->critical($e);
            return false;
        }

        return $this->category;
    }

    public function execute()
    {
        $parent =  parent::execute();

        if ($parent instanceof Page) {
            /** @var Breadcrumbs $breadcrumbsBlock */
            $breadcrumbsBlock = $parent->getLayout()->getBlock('breadcrumbs');

            try {
                $pageName = $this->outputHelper->getPageName();
            } catch (\Exception $exception) {
                $pageName = null;
            }

            if ($breadcrumbsBlock && $pageName) {
                $breadcrumbsBlock->addCrumb(
                    'thematic',
                    [
                        'label' => $pageName,
                        'title' => $pageName
                    ]
                );
            }

            $bloomreachContentPlacement3 = $parent->getLayout()->getBlock('bloomreach.thematic.content.placement.3');
            if ($bloomreachContentPlacement3) {
                $bloomreachContentPlacement3->setData('content_placement_3', $this->category->getData('content_placement_3'));
            }
        }

        return $parent;
    }
}
