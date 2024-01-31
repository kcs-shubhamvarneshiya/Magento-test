<?php
/**
 * Capgemini_CategoryAds
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\CategoryAds\ViewModel;

use Capgemini\CategoryAds\Api\CategoryAdsRepositoryInterface;
use Capgemini\CategoryAds\Api\Data\CategoryAdsInterface;
use Capgemini\CategoryAds\Model\CategoryAds as CategoryAdsModel;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Catalog\Model\Product\ProductList\ToolbarMemorizer;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Catalog\Model\Product\ProductList\Toolbar as ToolbarModel;

class CategoryAd implements ArgumentInterface
{
    public const PRODUCT_ITEMS_IN_GRID_ROW = 4;

    public const AD_SIZE_IN_ROW = 2;

    public const ALLOWED_POS_FOR_SECOND_AD = 15;

    public const ALLOWED_POS_FOR_SECOND_AD_WHEN_FIRST_EXIST = 11;

    public const ALLOWED_ROWS_FOR_FIRST_AD = [1, 2];

    public const ALLOWED_ROWS_FOR_SECOND_AD = [4, 5];

    /**
     * @var CategoryAdsRepositoryInterface
     */
    protected CategoryAdsRepositoryInterface $categoryAdsRepository;

    /**
     * @var Resolver
     */
    protected Resolver $layerResolver;

    /**
     * @var ToolbarMemorizer
     */
    protected ToolbarMemorizer $toolbarMemorizer;

    /**
     * @var FilterProvider
     */
    protected FilterProvider $filterProvider;

    /**
     * @var ToolbarModel
     */
    protected ToolbarModel $toolbar;

    /**
     * @var array
     */
    protected array $_rowMap = [];

    /**
     * @var int
     */
    protected int $itemsCount = 0;

    /**
     * @var bool
     */
    protected $_firsAdWasInjected;

    /**
     * @var bool
     */
    protected $_secondAdWasInjected;

    /**
     * @param CategoryAdsRepositoryInterface $categoryAdsRepository
     * @param Resolver $layerResolver
     * @param ToolbarMemorizer $toolbarMemorizer
     * @param FilterProvider $filterProvider
     * @param ToolbarModel $toolbar
     */
    public function __construct(
        CategoryAdsRepositoryInterface $categoryAdsRepository,
        Resolver $layerResolver,
        ToolbarMemorizer $toolbarMemorizer,
        FilterProvider $filterProvider,
        ToolbarModel $toolbar
    ) {
        $this->categoryAdsRepository = $categoryAdsRepository;
        $this->layerResolver = $layerResolver;
        $this->toolbarMemorizer = $toolbarMemorizer;
        $this->filterProvider = $filterProvider;
        $this->toolbar = $toolbar;
    }

    /**
     * @return bool
     */
    public function canShowAds(): bool
    {
        return $this->isFirstPage() && $this->isFilterActive() == false && $this->isSortActive() == false;
    }

    /**
     * @param string $content
     * @return string
     * @throws \Exception
     */
    public function filterContent(string $content): string
    {
        return $this->filterProvider->getPageFilter()->filter($content);
    }

    /**
     * Return content or false for provided product position
     *
     * @param int $productListPosition
     * @return string|bool
     */
    public function getCategoryAdSpot(int $productListPosition)
    {
        try {
            if ($this->getItemsCount() && $this->canShowAds()) {
                $count = count($this->getAdsForCategory());

                if (!$this->_rowMap) {
                    $rowMap = $this->getRowsMap(
                        $this->getItemsCount(),
                        $count > 0,
                        $count == 2
                    );
                    $this->_rowMap = $rowMap;
                }

                return $this->getContentForPosition($productListPosition);
            }
        } catch (\Exception $exception) {
        }

        return false;
    }

    /**
     * @param $productListPosition
     * @return false|string
     * @throws \Exception
     */
    private function getContentForPosition($productListPosition)
    {
        $allowedRowsForFirstAd = $this->getAllowedRowsForFirstAd();
        $allowedRowsForSecondAd = $this->getAllowedRowsForSecondAd();
        $allowedPositionalForSecondAdWhenFirst = $this->getAllowedPositionalForSecondAdWhenFirst();
        $allowedPositionalForSecondAd = $this->getAllowedPositionalForSecondAd();

        $currentProductRow = $this->_rowMap[$productListPosition];
        $categoriesAds = $this->getAdsForCategory();

        $firstAd = $this->getAdFromAdsByPosition(
            $categoriesAds,
            CategoryAdsModel::FIRST_POSITION
        );

        if ($firstAd && in_array($currentProductRow, $allowedRowsForFirstAd)) {
            if ($this->_firsAdWasInjected) {
                return false;
            }
            $this->_firsAdWasInjected = true;

            return $this->filterContent($firstAd->getContent());
        }

        $secondAd = $this->getAdFromAdsByPosition(
            $categoriesAds,
            CategoryAdsModel::SECOND_POSITION
        );
        if ($secondAd && in_array($currentProductRow, $allowedRowsForSecondAd)) {
            if ($this->_secondAdWasInjected) {
                return false;
            }

            $currentPositionsForSecondAd = $allowedPositionalForSecondAd;
            if ($this->_firsAdWasInjected) {
                $currentPositionsForSecondAd = $allowedPositionalForSecondAdWhenFirst;
            }

            if ($productListPosition == $currentPositionsForSecondAd) {
                $this->_secondAdWasInjected = true;

                return $this->filterContent($secondAd->getContent());
            }
        }

        return false;
    }

    /**
     * @return DataObject[]|null
     */
    public function getAdsForCategory(): ?array
    {
        $ads = null;
        if ($categoryId = $this->getCurrentCategory()->getId()) {
            $ads = $this->categoryAdsRepository->getAdsByCategory((int)$categoryId, true);
        }
        return $ads;
    }

    /**
     * @param DataObject[] $ads
     * @param int $position
     * @return DataObject[]|null
     */
    public function getAdFromAdsByPosition(array $ads, int $position): ?DataObject
    {
        foreach ($ads as $ad) {
            /**
             * @var $ad CategoryAdsInterface
             */
            if ($ad->getIsEnabled() && $ad->getPosition() == $position) {
                return $ad;
            }
        }
        return null;
    }

    /**
     * @param int $productNum
     * @param bool $hasFistAd
     * @param bool $hasSecondAd
     * @return array
     */
    public function getRowsMap(
        int $productNum,
        bool $hasFistAd = false,
        bool $hasSecondAd = false
    ): array {
        $rowsMap = [];
        $adsItems = 0;

        if ($hasFistAd) {
            $adsItems = $this->getItemsInRow();
        }

        if ($hasSecondAd) {
            $adsItems += $this->getItemsInRow();
        }

        $rows = ($productNum + $adsItems) / $this->getItemsInRow();

        $firstAdRow = $this->getAllowedRowsForFirstAd();
        $secondAdRow = $this->getAllowedRowsForSecondAd();

        $currentProduct = 1;

        for ($r = 1; $r <= $rows; $r++) {
            $itemsInCurrentRow = $this->getItemsInRow();
            for ($i = 1; $i <= $productNum; $i++) {
                $rowsMap[$currentProduct] = $r;
                if (in_array($r, $firstAdRow) && $hasFistAd
                || in_array($r, $secondAdRow) && $hasSecondAd) {
                    $itemsInCurrentRow -= $this->getAdSizeInRow();
                }
                $currentProduct++;
                if ($i >= $itemsInCurrentRow) {
                    break;
                }
            }
        }

        return $rowsMap;
    }

    /**
     * @return array
     */
    public function getAllowedRowsForFirstAd(): array
    {
        return self::ALLOWED_ROWS_FOR_FIRST_AD;
    }

    /**
     * @return array
     */
    public function getAllowedRowsForSecondAd(): array
    {
        return self::ALLOWED_ROWS_FOR_SECOND_AD;
    }

    /**
     * @return int
     */
    public function getAllowedPositionalForSecondAdWhenFirst(): int
    {
        return self::ALLOWED_POS_FOR_SECOND_AD_WHEN_FIRST_EXIST;
    }

    /**
     * @return int
     */
    public function getAllowedPositionalForSecondAd(): int
    {
        return self::ALLOWED_POS_FOR_SECOND_AD;
    }

    /**
     * @return int
     */
    public function getItemsInRow(): int
    {
        return self::PRODUCT_ITEMS_IN_GRID_ROW;
    }

    /**
     * @return int
     */
    public function getAdSizeInRow(): int
    {
        return self::AD_SIZE_IN_ROW;
    }

    /**
     * @return Category
     */
    public function getCurrentCategory(): Category
    {
        return $this->layerResolver->get()->getCurrentCategory();
    }

    /**
     * @return \Magento\Catalog\Model\Layer\Filter\Item[]
     */
    protected function isFilterActive()
    {
        return $this->layerResolver->get()->getState()->getFilters();
    }

    /**
     * @return bool|string|null
     */
    protected function isSortActive()
    {
        return $this->toolbarMemorizer->getOrder();
    }

    /**
     * @return bool
     */
    protected function isFirstPage()
    {
        return $this->toolbar->getCurrentPage() == 1;
    }

    /**
     * @return int
     */
    public function getItemsCount(): int
    {
        return $this->itemsCount;
    }

    /**
     * @param int $itemsCount
     */
    public function setItemsCount(int $itemsCount): void
    {
        $this->itemsCount = $itemsCount;
    }
}
