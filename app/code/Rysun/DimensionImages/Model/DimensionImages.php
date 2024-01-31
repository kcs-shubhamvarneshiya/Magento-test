<?php
declare(strict_types=1);

namespace Rysun\DimensionImages\Model;

use Magento\Framework\Model\AbstractModel;
use Rysun\DimensionImages\Api\Data\DimensionImagesInterface;

class DimensionImages extends AbstractModel implements DimensionImagesInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Rysun\DimensionImages\Model\ResourceModel\DimensionImages::class);
    }

    /**
     * @inheritDoc
     */
    public function getDimensionimagesId()
    {
        return $this->getData(self::DIMENSIONIMAGES_ID);
    }

    /**
     * @inheritDoc
     */
    public function setDimensionimagesId($dimensionimagesId)
    {
        return $this->setData(self::DIMENSIONIMAGES_ID, $dimensionimagesId);
    }

    /**
     * @inheritDoc
     */
    public function getSqlServProdId()
    {
        return $this->getData(self::SQL_SERV_PROD_ID);
    }

    /**
     * @inheritDoc
     */
    public function setSqlServProdId($sqlServProdId)
    {
        return $this->setData(self::SQL_SERV_PROD_ID, $sqlServProdId);
    }

    /**
     * @inheritDoc
     */
    public function getImageId()
    {
        return $this->getData(self::IMAGE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setImageId($imageId)
    {
        return $this->setData(self::IMAGE_ID, $imageId);
    }

    /**
     * @inheritDoc
     */
    public function getImageCaption()
    {
        return $this->getData(self::IMAGE_CAPTION);
    }

    /**
     * @inheritDoc
     */
    public function setImageCaption($imageCaption)
    {
        return $this->setData(self::IMAGE_CAPTION, $imageCaption);
    }

    /**
     * @inheritDoc
     */
    public function getImageDescription()
    {
        return $this->getData(self::IMAGE_DESCRIPTION);
    }

    /**
     * @inheritDoc
     */
    public function setImageDescription($imageDescription)
    {
        return $this->setData(self::IMAGE_DESCRIPTION, $imageDescription);
    }

    /**
     * @inheritDoc
     */
    public function getImageUrl()
    {
        return $this->getData(self::IMAGE_URL);
    }

    /**
     * @inheritDoc
     */
    public function setImageUrl($imageUrl)
    {
        return $this->setData(self::IMAGE_URL, $imageUrl);
    }

    /**
     * @inheritDoc
     */
    public function getImageFileName()
    {
        return $this->getData(self::IMAGE_FILE_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setImageFileName($imageFileName)
    {
        return $this->setData(self::IMAGE_FILE_NAME, $imageFileName);
    }

    /**
     * @inheritDoc
     */
    public function getImageAltTag()
    {
        return $this->getData(self::IMAGE_ALT_TAG);
    }

    /**
     * @inheritDoc
     */
    public function setImageAltTag($imageAltTag)
    {
        return $this->setData(self::IMAGE_ALT_TAG, $imageAltTag);
    }

    /**
     * @inheritDoc
     */
    public function getImageType()
    {
        return $this->getData(self::IMAGE_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setImageType($imageType)
    {
        return $this->setData(self::IMAGE_TYPE, $imageType);
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
}

