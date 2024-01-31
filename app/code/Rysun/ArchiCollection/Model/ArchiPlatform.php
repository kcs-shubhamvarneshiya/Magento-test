<?php
declare(strict_types=1);

namespace Rysun\ArchiCollection\Model;

use Magento\Framework\Model\AbstractModel;
use Rysun\ArchiCollection\Api\Data\ArchiPlatformInterface;

class ArchiPlatform extends AbstractModel implements ArchiPlatformInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Rysun\ArchiCollection\Model\ResourceModel\ArchiPlatform::class);
    }

    /**
     * @inheritDoc
     */
    public function getArchiPlatformId()
    {
        return $this->getData(self::ARCHI_PLATFORM_ID);
    }

    /**
     * @inheritDoc
     */
    public function setArchiPlatformId($archiPlatformId)
    {
        return $this->setData(self::ARCHI_PLATFORM_ID, $archiPlatformId);
    }

    /**
     * @inheritDoc
     */
    public function getPlatformId()
    {
        return $this->getData(self::PLATFORM_ID);
    }

    /**
     * @inheritDoc
     */
    public function setPlatformId($platformId)
    {
        return $this->setData(self::PLATFORM_ID, $platformId);
    }

    /**
     * @inheritDoc
     */
    public function getPlatformName()
    {
        return $this->getData(self::PLATFORM_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setPlatformName($platformName)
    {
        return $this->setData(self::PLATFORM_NAME, $platformName);
    }

    /**
     * @inheritDoc
     */
    public function getPlatformDesc()
    {
        return $this->getData(self::PLATFORM_DESC);
    }

    /**
     * @inheritDoc
     */
    public function setPlatformDesc($platformDesc)
    {
        return $this->setData(self::PLATFORM_DESC, $platformDesc);
    }

    /**
     * @inheritDoc
     */
    public function getPlatformDescSecond()
    {
        return $this->getData(self::PLATFORM_DESC_SECOND);
    }

    /**
     * @inheritDoc
     */
    public function setPlatformDescSecond($platformDescSecond)
    {
        return $this->setData(self::PLATFORM_DESC_SECOND, $platformDescSecond);
    }

    /**
     * @inheritDoc
     */
    public function getWebName()
    {
        return $this->getData(self::WEB_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setWebName($webName)
    {
        return $this->setData(self::WEB_NAME, $webName);
    }

    /**
     * @inheritDoc
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * @inheritDoc
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * @inheritDoc
     */
    public function getCategoryId()
    {
        return $this->getData(self::CATEGORY_ID);
    }

    /**
     * @inheritDoc
     */
    public function setCategoryId($categoryId)
    {
        return $this->setData(self::CATEGORY_ID, $categoryId);
    }

    /**
     * @inheritDoc
     */
    public function getCollectionId()
    {
        return $this->getData(self::COLLECTION_ID);
    }

    /**
     * @inheritDoc
     */
    public function setCollectionId($collectionId)
    {
        return $this->setData(self::COLLECTION_ID, $collectionId);
    }

    /**
     * @inheritDoc
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * @inheritDoc
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * @inheritDoc
     */
    public function getDateCreated()
    {
        return $this->getData(self::DATE_CREATED);
    }

    /**
     * @inheritDoc
     */
    public function setDateCreated($dateCreated)
    {
        return $this->setData(self::DATE_CREATED, $dateCreated);
    }

    /**
     * @inheritDoc
     */
    public function getDateLastUpdated()
    {
        return $this->getData(self::DATE_LAST_UPDATED);
    }

    /**
     * @inheritDoc
     */
    public function setDateLastUpdated($dateLastUpdated)
    {
        return $this->setData(self::DATE_LAST_UPDATED, $dateLastUpdated);
    }
}

