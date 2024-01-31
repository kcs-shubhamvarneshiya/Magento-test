<?php

namespace Rysun\DataTransfer\Cron;


use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Category;
use Magento\Framework\Registry;
use Magento\Catalog\Api\CategoryManagementInterface;
use Rysun\DataTransfer\Helper\Data;



class CategoryImpCron
{

    protected $storeManager;

    protected $categoryFactory;

    protected $categoryModel;

    protected $registry;

    protected $categoryManagement;

    protected $helper;

    const MAIN_CATEGORY_LAYOUT = 'architectural-main-category';

    const SUB_CATEGORY_LAYOUT_PLATFORM = 'architectural-sub-category';

    const SUB_CATEGORY_LAYOUT_PRODUCT = 'architectural-new-category';

    const CATEGORY_IMPORT_PATH = 'archi_import/general/category_import_path';

    public function __construct(
        StoreManagerInterface       $storeManager,
        CategoryFactory             $categoryFactory,
        Category                    $categoryModel,
        Registry                    $registry,
        CategoryManagementInterface $categoryManagement,
        Data                        $helper
    )
    {
        $this->storeManager = $storeManager;
        $this->categoryFactory = $categoryFactory;
        $this->categoryModel = $categoryModel;
        $this->registry = $registry;
        $this->categoryManagement = $categoryManagement;
        $this->helper = $helper;

    }

    public function execute()
    {
        //$fileName = '01_category.csv';
        //$filePath = "var/urapidflow/import/" . $fileName;

        //
        $filePath = $this->helper->getConfigValue(SELF::CATEGORY_IMPORT_PATH);
        $csv = array_map("str_getcsv", file($filePath, FILE_SKIP_EMPTY_LINES));

        $keys = array_shift($csv);

        // If NASM IDs exist truncate table
        // File uploaded will have IDs already in the database, not just new ones
        if (!empty($model)) {
            $tableName = $model->getResource()->getMainTable();
        }

        //echo "Called...";exit;
        foreach ($csv as $i => $row) {
            $err = '';
            if (count($row) > 0) {
                if (count($row) !== count($keys)) {
                    $empty_value_found = true;
                    $err .= "<br>Row " . $i . "\'s length does not match the header length: " . implode(', ', $row);
                } else {

                    //$rows[] = array_combine($headers, $encoded_row);\
                    $csv[$i] = array_combine($keys, $row);
                    if (empty($csv[$i]['pname'])
                        || empty($csv[$i]['csv_action'])
                        || empty($csv[$i]['option_combination_json'])
                        || empty($csv[$i]['status'])
                        || empty($csv[$i]['sql_serv_id'])
                        || empty($csv[$i]['sql_serv_prod_id'])
                    ) {
                        $empty_value_found = true;
                        $err .= "<br>Row " . $i . " hase empty field please check.";
                    }

                }
            }
        }

        echo ' Category creating..';
        $categoryCount = 0;
        foreach ($csv as $i => $row) {

            if ($row['csv_action'] == "C") {
                //echo 'creating';
                echo '.';
                $this->createCategory($row);
                $categoryCount++;
            }
            if ($row['csv_action'] == "U") {

                $existCat = $this->findMagentoIdBySqlServId($row['sql_serv_id']);
                if ($existCat) {
                    $this->updateCategory($existCat, $row);
                }

            }
            if ($row['csv_action'] == "D") {
                $this->deleteCategory($row);
            }

        }
    }

    /**
     * Delete Category based on sql serv Id
     */
    public function deleteCategory($categoryData)
    {

        //$this->registry->register("isSecureArea", true);
        $category = $this->findMagentoIdBySqlServId($categoryData['sql_serv_id']);
        if ($category) {

            /*
            $existCategory = $this->categoryModel->load($category->getId());
            $existCategory->setStoreId(0)
                        ->setIsActive(0);
            $existCategory->save();
            */

            $category->delete();

        }

    }

    /**
     * Update Category based on
     */
    public function updateCategory($category, $categoryData)
    {

        $categoryLayout = self::MAIN_CATEGORY_LAYOUT;

        if ($categoryData['path'] == "1") {

            $parentId = $this->storeManager->getStore()->getRootCategoryId();
            $parentCatId = $this->categoryFactory->create()->load($parentId);
            $categoryLayout = self::MAIN_CATEGORY_LAYOUT;
        } else if ($categoryData['list_page_type'] = 'Platform') {
            $categoryLayout = self::SUB_CATEGORY_LAYOUT_PLATFORM;
            $parentCatId = $this->findMagentoIdBySqlServId($categoryData['path']);
        } else {
            $categoryLayout = self::SUB_CATEGORY_LAYOUT_PRODUCT;
            $parentCatId = $this->findMagentoIdBySqlServId($categoryData['path']);
        }


        if (!$parentCatId->getId()) {
            return 'Error! Parent Not Found!';
        }

        $parentCategory = $this->categoryFactory->create()->load($parentCatId->getId());

        $existCategory = $this->categoryModel->load($category->getId());

        $categoryName = $this->sanitizeValue($categoryData['name']);
        $categoryDesc = $this->sanitizeValue($categoryData['desc']);
        $mediaAttribute = array('image', 'small_image', 'thumbnail');
        $existCategory->setName($categoryName)
            ->setUrlKey($categoryData['url_key'])
            ->setData('description', $categoryDesc)
            ->setStoreId(0)
            ->setPageLayout($categoryLayout)
            ->setIsArchitechData(1)
            ->setIncludeInMenu(0)
            ->setImage($categoryData['category_image'], $mediaAttribute, true, false)
            ->setAdditionalImage($categoryData['category_banner_image'], $mediaAttribute, true, false)
            ->setPosition($categoryData['sort_order'])
            ->setSqlServId($categoryData['sql_serv_id'])
            ->setIsActive($categoryData['is_active']);

        $existCategory->save();

        $isCategoryMoveSuccess = $this->categoryManagement->move($existCategory->getId(), $parentCatId->getId(), null);

        return "Updated";


    }

    public function createCategory($categoryData)
    {


        //Load parent category

        $categoryLayout = self::MAIN_CATEGORY_LAYOUT;

        if ($categoryData['path'] == "1") {

            $parentId = $this->storeManager->getStore()->getRootCategoryId();
            $parentCatId = $this->categoryFactory->create()->load($parentId);
            $categoryLayout = self::MAIN_CATEGORY_LAYOUT;
        } else if ($categoryData['list_page_type'] == 'Platform') {
            $categoryLayout = self::SUB_CATEGORY_LAYOUT_PLATFORM;
            $parentCatId = $this->findMagentoIdBySqlServId($categoryData['path']);
        } else {
            $categoryLayout = self::SUB_CATEGORY_LAYOUT_PRODUCT;
            $parentCatId = $this->findMagentoIdBySqlServId($categoryData['path']);
        }

        if (!$parentCatId->getId()) {
            return 'Error! Parent Not Found!';
        }
        //$parentCatId = $categoryData['path'];

        $parentCategory = $this->categoryFactory->create()->load($parentCatId->getId());
        $mediaAttribute = array('image', 'small_image', 'thumbnail');
        $categoryName = $this->sanitizeValue($categoryData['name']);
        $categoryDesc = $this->sanitizeValue($categoryData['desc']);
        $category = $this->categoryFactory->create();
        $category->setPath($parentCategory->getPath())
            ->setParentId($parentCatId->getId())
            ->setPath($parentCategory->getPath())
            ->setName($categoryName)
            ->setIncludeInMenu(0)
            ->setUrlKey($categoryData['url_key'])
            ->setData('description', $categoryDesc)
            ->setStoreId(0)
            ->setImage($categoryData['category_image'], $mediaAttribute, true, false)
            ->setAdditionalImage($categoryData['category_banner_image'], $mediaAttribute, true, false)
            ->setPageLayout($categoryLayout)
            ->setIsArchitechData(1)
            ->setSqlServId($categoryData['sql_serv_id'])
            ->setPosition($categoryData['sort_order'])
            ->setIsActive($categoryData['is_active']);
        $category->save();

        return true;

    }

    public function findMagentoIdBySqlServId($sqlServId)
    {

        $category = $this->categoryFactory->create();
        $cate = $category->getCollection()
            ->addAttributeToSelect('sql_serv_id')
            ->addAttributeToFilter('sql_serv_id', $sqlServId)
            ->getFirstItem();

        if ($cate->getId()) {
            return $cate;
        }

        return false;
    }

    public function checkIfExist($categoryName)
    {

        $category = $this->categoryFactory->create();
        $cate = $category->getCollection()
            ->addAttributeToFilter('name', $categoryName)
            ->getFirstItem();

        if ($cate->getId()) {
            return $cate;
        }

        return false;
    }

    public function sanitizeValue($string)
    {

        $updatedString = $string;

        $specialCharacters = [
            "&quote;" => '"',
            "&excl;" => '!',
            "&quot;" => '"',
            "&num;" => '"',
            "&percnt;" => '%',
            "&amp;" => '&',
            "&apos;" => "'",
            "&lpar;" => '(',
            "&rpar;" => ')',
            "&ast;" => '*',
            "&comma;" => ',',
            "&period;" => '.',
            "&sol;" => '/',
            "&colon;" => ':',
            "&semi;" => ';',
            "&quest;" => '?',
            "&commat;" => '@',
            "&lbrack;" => '[',
            //"&bsol;" => "\\",
            "&rbrack;" => ']',
            "&Hat;" => '^',
            "&lowbar;" => '_',
            "&grave;" => '`',
            "&lbrace;" => '{',
            "&vert;" => '|',
            "&rbrace;" => '}'
        ];

        foreach ($specialCharacters as $key => $specialString) {

            if (str_contains($string, $key)) {
                //echo ' matched ';
                //exit('coming here');
                $updatedString = str_replace($key, $specialString, $updatedString);
            }
        }
        return addslashes($updatedString);

    }

}
