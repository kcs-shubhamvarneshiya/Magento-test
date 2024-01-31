<?php

namespace Lyonscg\MageWorxXmlSitemap\Model\ResourceModel\Catalog;

use Magento\CatalogUrlRewrite\Model\CategoryUrlRewriteGenerator;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\Store;

/**
 * Class Category
 * @package Lyonscg\MageWorxXmlSitemap\Model\ResourceModel\Catalog
 */
class Category extends \MageWorx\XmlSitemap\Model\ResourceModel\Catalog\Category
{
    /**
     * Get category collection array
     * Call this function while !isCollectionReaded() to read all collection
     *
     * @param null|string|bool|int|Store $storeId
     * @param int $limit
     * @param null $usePubInMediaUrls
     * @return DataObject[]|bool
     * @throws LocalizedException
     * @throws Zend_Db_Statement_Exception
     */
    public function getLimitedCollection($storeId, $limit, $usePubInMediaUrls = null)
    {
        $this->usePubInMediaUrls = $usePubInMediaUrls;

        $categories = [];

        /* @var $store Store */
        $store = $this->_storeManager->getStore($storeId);
        if (!$store) {
            return false;
        }

        if ($limit <= 0) {
            return false;
        }

        if (!isset($this->query)) {
            $connect       = $this->getConnection();
            $this->_select = $connect->select()->from(
                $this->getMainTable()
            )->where(
                $this->getIdFieldName() . '=?',
                $store->getRootCategoryId()
            );
            $categoryRow   = $connect->fetchRow($this->_select);

            if (!$categoryRow) {
                return false;
            }

            $this->_select = $connect->select()->from(
                ['e' => $this->getMainTable()],
                [$this->getIdFieldName(), 'updated_at']
            )->joinLeft(
                ['url_rewrite' => $this->getTable('url_rewrite')],
                'e.entity_id = url_rewrite.entity_id'
                . $connect->quoteInto(' AND url_rewrite.store_id = ?', $store->getId())
                . $connect->quoteInto(' AND url_rewrite.entity_type = ?', CategoryUrlRewriteGenerator::ENTITY_TYPE),
                ['url' => 'request_path']
            )->where(
                'e.path LIKE ?',
                $categoryRow['path'] . '/%'
            );

            if ($this->helperSitemap->isCategoryImages()) {
                $this->_joinAttribute($storeId, 'image', 'image');
                $this->_joinAttribute($storeId, 'name', 'name');
            }

            $this->_addFilter($storeId, 'is_active', 1);
            $this->_addFilter($storeId, 'in_xml_sitemap', 1);

            if ($this->_categoryResource->getAttribute('meta_robots')) {
                $metaRobotsExclusionList = $this->helperSitemap->getMetaRobotsExclusion();

                if ($metaRobotsExclusionList) {
                    $this->_addExtendedFilter($store->getId(), 'meta_robots', $metaRobotsExclusionList, 'nin');
                }
            }

            $this->eventManager->dispatch(
                'mageworx_xmlsitemap_category_generation_before',
                ['select' => $this->_select, 'store_id' => $storeId]
            );

            $this->query  = $connect->query($this->_select);
            $this->readed = false;
        }

        for ($i = 0; $i < $limit; $i++) {
            if (!$row = $this->query->fetch()) {
                $this->readed = true;
                break;
            }

            $category                       = $this->_prepareCategory($row);
            $categories[$category->getId()] = $category;
        }

        return $categories;
    }
}
