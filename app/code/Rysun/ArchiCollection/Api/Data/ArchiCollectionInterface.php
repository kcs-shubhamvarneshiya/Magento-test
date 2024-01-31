<?php
declare(strict_types=1);

namespace Rysun\ArchiCollection\Api\Data;

interface ArchiCollectionInterface
{

    const IS_ACTIVE = 'is_active';
    const DATE_LAST_UPDATED = 'date_last_updated';
    const WEB_NAME = 'web_name';
    const ARCHI_COLLECTION_ID = 'archi_collection_id';
    const COLLECTION_ID = 'collection_id';
    const COLLECTION_DESC = 'collection_desc';
    const CATEGORY_ID = 'category_id';
    const COLLECTION_NAME = 'collection_name';
    const COLLECTION_DESC_SECOND = 'collection_desc_second';
    const DATE_CREATED = 'date_created';
    const SORT_ORDER = 'sort_order';

    /**
     * Get archi_collection_id
     * @return string|null
     */
    public function getArchiCollectionId();

    /**
     * Set archi_collection_id
     * @param string $archiCollectionId
     * @return \Rysun\ArchiCollection\ArchiCollection\Api\Data\ArchiCollectionInterface
     */
    public function setArchiCollectionId($archiCollectionId);

    /**
     * Get collection_id
     * @return string|null
     */
    public function getCollectionId();

    /**
     * Set collection_id
     * @param string $collectionId
     * @return \Rysun\ArchiCollection\ArchiCollection\Api\Data\ArchiCollectionInterface
     */
    public function setCollectionId($collectionId);

    /**
     * Get collection_name
     * @return string|null
     */
    public function getCollectionName();

    /**
     * Set collection_name
     * @param string $collectionName
     * @return \Rysun\ArchiCollection\ArchiCollection\Api\Data\ArchiCollectionInterface
     */
    public function setCollectionName($collectionName);

    /**
     * Get collection_desc
     * @return string|null
     */
    public function getCollectionDesc();

    /**
     * Set collection_desc
     * @param string $collectionDesc
     * @return \Rysun\ArchiCollection\ArchiCollection\Api\Data\ArchiCollectionInterface
     */
    public function setCollectionDesc($collectionDesc);

    /**
     * Get collection_desc_second
     * @return string|null
     */
    public function getCollectionDescSecond();

    /**
     * Set collection_desc_second
     * @param string $collectionDescSecond
     * @return \Rysun\ArchiCollection\ArchiCollection\Api\Data\ArchiCollectionInterface
     */
    public function setCollectionDescSecond($collectionDescSecond);

    /**
     * Get web_name
     * @return string|null
     */
    public function getWebName();

    /**
     * Set web_name
     * @param string $webName
     * @return \Rysun\ArchiCollection\ArchiCollection\Api\Data\ArchiCollectionInterface
     */
    public function setWebName($webName);

    /**
     * Get sort_order
     * @return string|null
     */
    public function getSortOrder();

    /**
     * Set sort_order
     * @param string $sortOrder
     * @return \Rysun\ArchiCollection\ArchiCollection\Api\Data\ArchiCollectionInterface
     */
    public function setSortOrder($sortOrder);

    /**
     * Get category_id
     * @return string|null
     */
    public function getCategoryId();

    /**
     * Set category_id
     * @param string $categoryId
     * @return \Rysun\ArchiCollection\ArchiCollection\Api\Data\ArchiCollectionInterface
     */
    public function setCategoryId($categoryId);

    /**
     * Get is_active
     * @return string|null
     */
    public function getIsActive();

    /**
     * Set is_active
     * @param string $isActive
     * @return \Rysun\ArchiCollection\ArchiCollection\Api\Data\ArchiCollectionInterface
     */
    public function setIsActive($isActive);

    /**
     * Get date_created
     * @return string|null
     */
    public function getDateCreated();

    /**
     * Set date_created
     * @param string $dateCreated
     * @return \Rysun\ArchiCollection\ArchiCollection\Api\Data\ArchiCollectionInterface
     */
    public function setDateCreated($dateCreated);

    /**
     * Get date_last_updated
     * @return string|null
     */
    public function getDateLastUpdated();

    /**
     * Set date_last_updated
     * @param string $dateLastUpdated
     * @return \Rysun\ArchiCollection\ArchiCollection\Api\Data\ArchiCollectionInterface
     */
    public function setDateLastUpdated($dateLastUpdated);
}

