<?php
declare(strict_types=1);

namespace Rysun\ArchiCollection\Model;

use Magento\Framework\Model\AbstractModel;
use Rysun\ArchiCollection\Api\Data\ArchiCollectionInterface;

class ArchiCollection extends AbstractModel implements ArchiCollectionInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Rysun\ArchiCollection\Model\ResourceModel\ArchiCollection::class);
    }

    /**
     * @inheritDoc
     */
    public function getArchiCollectionId()
    {
        return $this->getData(self::ARCHI_COLLECTION_ID);
    }

    /**
     * @inheritDoc
     */
    public function setArchiCollectionId($archiCollectionId)
    {
        return $this->setData(self::ARCHI_COLLECTION_ID, $archiCollectionId);
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
    public function getCollectionName()
    {
        return $this->getData(self::COLLECTION_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setCollectionName($collectionName)
    {
        return $this->setData(self::COLLECTION_NAME, $collectionName);
    }

    /**
     * @inheritDoc
     */
    public function getCollectionDesc()
    {
        return $this->getData(self::COLLECTION_DESC);
    }

    /**
     * @inheritDoc
     */
    public function setCollectionDesc($collectionDesc)
    {
        return $this->setData(self::COLLECTION_DESC, $collectionDesc);
    }

    /**
     * @inheritDoc
     */
    public function getCollectionDescSecond()
    {
        return $this->getData(self::COLLECTION_DESC_SECOND);
    }

    /**
     * @inheritDoc
     */
    public function setCollectionDescSecond($collectionDescSecond)
    {
        return $this->setData(self::COLLECTION_DESC_SECOND, $collectionDescSecond);
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

