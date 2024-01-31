<?php
declare(strict_types=1);

namespace Rysun\ArchiCollection\Api\Data;

interface ArchiPlatformInterface
{

    const IS_ACTIVE = 'is_active';
    const DATE_LAST_UPDATED = 'date_last_updated';
    const WEB_NAME = 'web_name';
    const PLATFORM_DESC = 'platform_desc';
    const COLLECTION_ID = 'collection_id';
    const PLATFORM_ID = 'platform_id';
    const PLATFORM_NAME = 'platform_name';
    const CATEGORY_ID = 'category_id';
    const ARCHI_PLATFORM_ID = 'archi_platform_id';
    const DATE_CREATED = 'date_created';
    const SORT_ORDER = 'sort_order';
    const PLATFORM_DESC_SECOND = 'platform_desc_second';

    /**
     * Get archi_platform_id
     * @return string|null
     */
    public function getArchiPlatformId();

    /**
     * Set archi_platform_id
     * @param string $archiPlatformId
     * @return \Rysun\ArchiCollection\ArchiPlatform\Api\Data\ArchiPlatformInterface
     */
    public function setArchiPlatformId($archiPlatformId);

    /**
     * Get platform_id
     * @return string|null
     */
    public function getPlatformId();

    /**
     * Set platform_id
     * @param string $platformId
     * @return \Rysun\ArchiCollection\ArchiPlatform\Api\Data\ArchiPlatformInterface
     */
    public function setPlatformId($platformId);

    /**
     * Get platform_name
     * @return string|null
     */
    public function getPlatformName();

    /**
     * Set platform_name
     * @param string $platformName
     * @return \Rysun\ArchiCollection\ArchiPlatform\Api\Data\ArchiPlatformInterface
     */
    public function setPlatformName($platformName);

    /**
     * Get platform_desc
     * @return string|null
     */
    public function getPlatformDesc();

    /**
     * Set platform_desc
     * @param string $platformDesc
     * @return \Rysun\ArchiCollection\ArchiPlatform\Api\Data\ArchiPlatformInterface
     */
    public function setPlatformDesc($platformDesc);

    /**
     * Get platform_desc_second
     * @return string|null
     */
    public function getPlatformDescSecond();

    /**
     * Set platform_desc_second
     * @param string $platformDescSecond
     * @return \Rysun\ArchiCollection\ArchiPlatform\Api\Data\ArchiPlatformInterface
     */
    public function setPlatformDescSecond($platformDescSecond);

    /**
     * Get web_name
     * @return string|null
     */
    public function getWebName();

    /**
     * Set web_name
     * @param string $webName
     * @return \Rysun\ArchiCollection\ArchiPlatform\Api\Data\ArchiPlatformInterface
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
     * @return \Rysun\ArchiCollection\ArchiPlatform\Api\Data\ArchiPlatformInterface
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
     * @return \Rysun\ArchiCollection\ArchiPlatform\Api\Data\ArchiPlatformInterface
     */
    public function setCategoryId($categoryId);

    /**
     * Get collection_id
     * @return string|null
     */
    public function getCollectionId();

    /**
     * Set collection_id
     * @param string $collectionId
     * @return \Rysun\ArchiCollection\ArchiPlatform\Api\Data\ArchiPlatformInterface
     */
    public function setCollectionId($collectionId);

    /**
     * Get is_active
     * @return string|null
     */
    public function getIsActive();

    /**
     * Set is_active
     * @param string $isActive
     * @return \Rysun\ArchiCollection\ArchiPlatform\Api\Data\ArchiPlatformInterface
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
     * @return \Rysun\ArchiCollection\ArchiPlatform\Api\Data\ArchiPlatformInterface
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
     * @return \Rysun\ArchiCollection\ArchiPlatform\Api\Data\ArchiPlatformInterface
     */
    public function setDateLastUpdated($dateLastUpdated);
}

