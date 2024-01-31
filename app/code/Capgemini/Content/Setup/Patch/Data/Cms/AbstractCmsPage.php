<?php
/**
 * Capgemini_Content
 */

namespace Capgemini\Content\Setup\Patch\Data\Cms;

use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Cms\Model\PageFactory;
use Magento\Cms\Model\BlockFactory;
use Magento\UrlRewrite\Model\UrlRewriteFactory;
use Magento\UrlRewrite\Model\UrlRewrite;

/**
 * Class AbstractCmsPage
 */
abstract class AbstractCmsPage extends AbstractCms
{

    /**
     * @var \Magento\Cms\Model\PageFactory
     */
    protected $pageFactory;

    /**
     * @var UrlRewriteFactory
     */
    protected $urlRewriteFactory;

    /**
     * AbstractCmsPage constructor.
     * @param StoreRepositoryInterface $storeRepository
     * @param BlockFactory $blockFactory
     * @param PageFactory $pageFactory
     */
    public function __construct(
        StoreRepositoryInterface $storeRepository,
        BlockFactory $blockFactory,
        PageFactory $pageFactory,
        UrlRewriteFactory $urlRewriteFactory
    ) {
        $this->storeRepository = $storeRepository;
        $this->pageFactory     = $pageFactory;
        $this->urlRewriteFactory = $urlRewriteFactory;

        parent::__construct($storeRepository, $blockFactory);
    }


    /**
     * Create and new page or update if it exists
     *
     * @param array $pageData
     */
    protected function upsertPage(array $pageData): void
    {
        $cmsPage = $this->createPage();
        if (isset($pageData['stores']) && isset($pageData['stores'][0])) {
            $cmsPage->setStoreId($pageData['stores'][0]);
        }
        if (isset($pageData['store_id'])) {
            $cmsPage->setStoreId($pageData['store_id']);
        }
        $cmsPage->load($pageData['identifier'], 'identifier');
        if (!$cmsPage->getId()) {
            $this->deleteUrlRewrite($pageData['identifier'], $cmsPage->getStoreId());
            $cmsPage->setData($pageData);
        } else {
            $cmsPage->addData($pageData);
        }

        try {
            $cmsPage->save();
        } catch (\Exception $e) {
        }
    }

    /**
     * Create page model instance
     *
     * @return \Magento\Cms\Model\Page
     */
    protected function createPage()
    {
        return $this->pageFactory->create();
    }

    /**
     * Delete CMS Page URL rewrite if it was created before
     *
     * @param int $storeId
     * @param string $requestPath
     * @throws \Exception
     */
    protected function deleteUrlRewrite($requestPath, $storeId)
    {
        /** @var UrlRewrite $urlRewriteModel */
        $urlRewriteModel = $this->urlRewriteFactory->create();
        $urlRewriteModel->setStoreId($storeId);
        $urlRewriteModel->load($requestPath,'request_path');
        if ($urlRewriteModel->getId()) {
            $urlRewriteModel->delete();
        }
    }
}
