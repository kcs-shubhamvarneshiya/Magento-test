<?php
/**
 * Capgemini_CategoryAds
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\CategoryAds\Model\ResourceModel\CategoryAds;

use Capgemini\CategoryAds\Api\CategoryAdsRepositoryInterface;
use Capgemini\CategoryAds\Api\Data\CategoryAdsInterface;
use Capgemini\CategoryAds\Model\ResourceModel\CategoryAds\CategoryLink\CollectionFactory as CategoryLinkCollectionFactory;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Psr\Log\LoggerInterface;
use tests\unit\Magento\FunctionalTestFramework\Console\BaseGenerateCommandTest;


class Collection extends AbstractCollection
{
    /**
     * @var bool
     */
    protected $categoriesJoined = false;

    /**
     * @var CategoryLinkCollectionFactory
     */
    protected $categoryLinkCollectionFactory;

    /**
     * @var CategoryAdsRepositoryInterface
     */
    protected $categoryAdsRepositoryInterface;

    /**
     * @param CategoryLinkCollectionFactory $categoryLinkCollectionFactory
     * @param CategoryAdsRepositoryInterface $categoryAdsRepositoryInterface
     * @param EntityFactoryInterface $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     */
    public function __construct(
        CategoryLinkCollectionFactory $categoryLinkCollectionFactory,
        CategoryAdsRepositoryInterface $categoryAdsRepositoryInterface,
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager);
        $this->categoryLinkCollectionFactory = $categoryLinkCollectionFactory;
        $this->categoryAdsRepositoryInterface = $categoryAdsRepositoryInterface;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct() : void
    {
        $this->_init('Capgemini\CategoryAds\Model\CategoryAds',
            'Capgemini\CategoryAds\Model\ResourceModel\CategoryAds');
    }

    /**
     * @param array $categoryIds
     * @return $this
     */
    public function filterByCategoryIds(array $categoryIds)
    {
        if (!empty($categoryIds)) {
            $this->joinCategories();
            $this->getSelect()->where('category_links.category_id IN (?)', $categoryIds);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function joinCategories()
    {
        if (!$this->categoriesJoined) {
            $this->getSelect()->join(
                ['category_links' => $this->getTable('capgemini_plpad_category')],
                'main_table.entity_id = category_links.plpad_id',
                []
            )->group('main_table.entity_id');

            $this->categoriesJoined = true;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _afterLoad() : Collection
    {
        parent::_afterLoad();

        $categoriesLinkCollection = $this->categoryLinkCollectionFactory->create();
        $categoriesLinkCollection->addFieldToFilter('plpad_id', ['in' => $this->getAllIds()]);

        $categoryLinksByAds = [];
        foreach ($categoriesLinkCollection as $categoryLinks) {
            if (!(isset($categoryLinksByAds[$categoryLinks->getPlpadId()]))) {
                $categoryLinksByAds[$categoryLinks->getPlpadId()] = [];
            }

            $categoryLinksByAds[$categoryLinks->getPlpadId()][] = $categoryLinks;
        }
        /**
         * @var CategoryAdsInterface $item
         */
        foreach ($this->getItems() as $item) {
            $categories = (isset($categoryLinksByAds[$item->getId()])) ? $categoryLinksByAds[$item->getId()] : [];
            $this->categoryAdsRepositoryInterface->addCategoriesToAd($item, $categories);
        }

        return $this;
    }
}
