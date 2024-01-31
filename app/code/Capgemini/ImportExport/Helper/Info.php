<?php
namespace Capgemini\ImportExport\Helper;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

class Info extends \Magento\Framework\App\Helper\AbstractHelper
{
    const COL_CATEGORY = \Magento\CatalogImportExport\Model\Import\Product::COL_CATEGORY;

    const CAT_NEW_INTRODUCTIONS = 'New Introductions';
    const CAT_ANNEX = 'Annex';
    const CAT_LAST_CALL = 'Last Call';
    const CAT_STYLE = 'Style';
    const CAT_DESIGNER = 'Our Designers';

    const COL_DISCOUNT = 'discount';
    const COL_DESIGNER = 'designer';
    const COL_STYLE = 'style';
    const COL_NEWITEM = 'newitem';
    const COL_FUNCTION = 'function';
    const COL_ANNEX = 'annex';

    const FORCE_RESAVE_PARENT_CONFIG_PATH = 'importexport/general/force_resave_parent';

    const DEFAULT_IMPORT_PRODUCT_ENTITY_CODE = 'catalog_product';

    const CUSTOM_IMPORT_PRODUCT_ENTITY_CODE = 'catalog_product_circa';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var array
     */
    private $websitesData = [];

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;


    /**
     * Info constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(Context $context, StoreManagerInterface $storeManager,
                                CategoryRepositoryInterface $categoryRepository)
    {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->categoryRepository = $categoryRepository;
    }


    public function getRequest()
    {
        return parent::_getRequest();
    }

    /**
     * @param $config_path
     * @return mixed
     */
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $updateInfo
     * @return array
     */
    public function mapFeedCategoryToMagentoCategory($updateInfo)
    {
        $magentoCategories = [];
        $feedCategory = !empty($updateInfo[self::COL_CATEGORY]) ? trim($updateInfo[self::COL_CATEGORY]) : '';
        $function = !empty($updateInfo['function']) ? trim($updateInfo['function']) : '';
        $defaultCategoryNames = $this->retrieveWebsitesData($updateInfo['product_websites_local'], 'default_root_category_names');
        foreach ($defaultCategoryNames as $defaultCategoryName) {
            $categoryMap = [
                'Ceiling Lights' => $defaultCategoryName . '/Ceiling/View All Ceiling',
                'Wall Lights' => $defaultCategoryName . '/Wall/View All',
                'Table Lamps' => $defaultCategoryName . '/Table/View All',
                'Floor Lamps' => $defaultCategoryName . '/Floor/View All',
                'Fans' => $defaultCategoryName . '/Fans/View All',
                'Outdoor Lighting' => $defaultCategoryName . '/Outdoor/View All',
                'Light Bulbs' => $defaultCategoryName . '/Bulbs',
                'Shades' => 'Shades',
                'Undercabinet' => 'Undercabinet'
            ];

            if (isset($categoryMap[$feedCategory])) {
                $magentoCategories[self::COL_CATEGORY] = (isset($magentoCategories[self::COL_CATEGORY])) ? $magentoCategories[self::COL_CATEGORY] . ',' . $categoryMap[$feedCategory] : $categoryMap[$feedCategory];
            }
        }

        $additionalCategories = $this->getAdditionalCategories($updateInfo, $feedCategory);
        if (!empty($additionalCategories)) {
            if (count($magentoCategories) > 0) {
                $magentoCategories[self::COL_CATEGORY] .= ',' . $additionalCategories;
            } else {
                $magentoCategories[self::COL_CATEGORY] = $additionalCategories;
            }
        }

        if (count($magentoCategories) > 0) {
            return $magentoCategories;
        } else {
            return $updateInfo;
        }
    }

    /**
     * @param array $updateInfo
     * @param string|boolean $feedCategory
     * @return string|array
     */
    function getAdditionalCategories($updateInfo, $feedCategory = false)
    {
        $additionalCategories = [];
        $discount = $updateInfo[self::COL_DISCOUNT] ?? false;
        $styles = $updateInfo[self::COL_STYLE] ?? false;
        $newitem = $updateInfo[self::COL_NEWITEM] ?? false;
        $designer = $updateInfo[self::COL_DESIGNER] ?? false;
        $function = $updateInfo[self::COL_FUNCTION] ?? false;
        $annex = $updateInfo[self::COL_ANNEX] ?? false;

        $functionMap = ['Functional' => 'Task'];

        if ($function && isset($functionMap[$function])) {
            $function = $functionMap[$function];
        }

        $defaultCategoryNames = $this->retrieveWebsitesData($updateInfo['product_websites_local'], 'default_root_category_names');
        foreach ($defaultCategoryNames as $defaultCategoryName) {
            $categoryMap = [
                'Ceiling Lights' => 'Ceiling',
                'Wall Lights' => 'Wall',
                'Table Lamps' => 'Table',
                'Floor Lamps' => 'Floor',
                'Fans' => 'Fans',
                'Outdoor Lighting' => 'Outdoor'
            ];

            if (!isset($categoryMap[$feedCategory])) {
                return [];
            }

            $category = $categoryMap[$feedCategory];

            if ($function) {
                // ex: Default Category/Ceiling/Pendant
                $additionalCategories[] = $defaultCategoryName . '/' . $category . '/' . $function;
            }

            if ($newitem) {
                $additionalCategories[] = $defaultCategoryName . '/' . $category . '/' . self::CAT_NEW_INTRODUCTIONS;
            }

            if ($styles) {
                $stylesArr = explode(',', $styles);
            } else {
                $stylesArr = [];
            }

            if ($discount && $discount == '2') {
                $additionalCategories[] = $defaultCategoryName . '/' . self::CAT_LAST_CALL;
                $additionalCategories[] = $defaultCategoryName . '/' . self::CAT_LAST_CALL . '/View All';
                if ($category) {
                    $additionalCategories[] = $defaultCategoryName . '/' . self::CAT_LAST_CALL . '/' . $category;
                }
            }

            if ($annex) {
                $additionalCategories[] = $defaultCategoryName . '/' . self::CAT_ANNEX;
                if ($category) {
                    $additionalCategories[] = $defaultCategoryName . '/' . self::CAT_ANNEX . '/' . $category;
                    if ($function) {
                        $additionalCategories[] = $defaultCategoryName . '/' . self::CAT_ANNEX . '/' . $category . '/' . $function;
                    }
                }
            }

            if ($newitem) {
                $additionalCategories[] = $defaultCategoryName . '/' . self::CAT_NEW_INTRODUCTIONS;
                if ($category) {
                    $additionalCategories[] = $defaultCategoryName . '/' . self::CAT_NEW_INTRODUCTIONS . '/' . $category . ' - View All';
                    if ($function) {
                        $additionalCategories[] = $defaultCategoryName . '/' . self::CAT_NEW_INTRODUCTIONS . '/' . $category . ' - View All' . '/' . $function;
                    }
                }
            }

            foreach ($stylesArr as $styleItem) {
                if ($styleItem) {
                    $additionalCategories[] = $defaultCategoryName . '/' . self::CAT_STYLE . '/' . $styleItem;
                    if ($category) {
                        $additionalCategories[] = $defaultCategoryName . '/' . self::CAT_STYLE . '/' . $styleItem . '/' . $category . ' - View All';
                        /*
                        if ($function) {
                            $additionalCategories[] = $defaultCategoryName . '/' . self::CAT_STYLE . '/' . $style . '/' . $category . ' - View All' . '/' . $function;
                        }
                        */
                    }
                }
            }

            if ($designer) {
                $additionalCategories[] = $defaultCategoryName . '/' . self::CAT_DESIGNER . '/' . $designer;
                if ($category) {
                    $additionalCategories[] = $defaultCategoryName . '/' . self::CAT_DESIGNER . '/' . $designer . '/' . $category . ' - View All';
                    if ($function) {
                        $additionalCategories[] = $defaultCategoryName . '/' . self::CAT_DESIGNER . '/' . $designer . '/' . $category . ' - View All' . '/' . $function;
                    }
                    if ($newitem) {
                        $additionalCategories[] =
                            $defaultCategoryName . '/' . self::CAT_DESIGNER . '/' . $designer . '/' . $category . ' - View All' . '/' . self::CAT_NEW_INTRODUCTIONS;
                    }
                }
            }
        }

        return implode(',', $additionalCategories);
    }

    /**
     * @param $codesString
     * @param $key
     * @return mixed|null
     */
    public function retrieveWebsitesData($codesString, $key)
    {
        if (!isset($this->websitesData[$codesString][$key])) {
            $this->recordWebsitesData($codesString);
        }

        return $this->websitesData[$codesString][$key] ?? null;
    }

    /**
     * @return mixed
     */
    public function isForceResaveEnabled()
    {
        return $this->scopeConfig->getValue(self::FORCE_RESAVE_PARENT_CONFIG_PATH);
    }

    /**
     * @return int
     */
    public function getDefaultWebsiteId(): int
    {
        return $this->storeManager->getDefaultStoreView()->getWebsiteId();
    }

    private function recordWebsitesData($codesString) {
        $codes = explode(',', $codesString);
        foreach ($codes as $code) {
            try {
                $website = $this->storeManager->getWebsite($code);
            } catch (LocalizedException $exception) {
                $this->_logger->error('Capgemeni_ImportExport: Couldn\'t get website with ' . $code . 'code.');

                continue;
            }

            try {
                $defaultRootCategoryId = $website->getDefaultStore()->getRootCategoryId();
                $defaultCategory = $this->categoryRepository->get($defaultRootCategoryId);
                $defaultRootCategoryName = $defaultCategory->getName();
            } catch (NoSuchEntityException $exception) {
                $this->_logger->error('Capgemeni_ImportExport: Couldn\'t get default root category for website with ' . $code . 'code.');

                continue;
            }

            $this->websitesData[$codesString]['website_ids'][] = $website->getId();
            $this->websitesData[$codesString]['default_root_category_names'][] = $defaultRootCategoryName;
        }
    }
}
