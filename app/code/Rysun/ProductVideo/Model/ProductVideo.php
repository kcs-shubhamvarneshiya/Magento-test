<?php
declare(strict_types=1);

namespace Rysun\ProductVideo\Model;

use Magento\Framework\Model\AbstractModel;
use Rysun\ProductVideo\Api\Data\ProductVideoInterface;

class ProductVideo extends AbstractModel implements ProductVideoInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Rysun\ProductVideo\Model\ResourceModel\ProductVideo::class);
    }

    /**
     * @inheritDoc
     */
    public function getProductvideoId()
    {
        return $this->getData(self::PRODUCTVIDEO_ID);
    }

    /**
     * @inheritDoc
     */
    public function setProductvideoId($productvideoId)
    {
        return $this->setData(self::PRODUCTVIDEO_ID, $productvideoId);
    }

    /**
     * @inheritDoc
     */
    public function getVideoCaption()
    {
        return $this->getData(self::VIDEO_CAPTION);
    }

    /**
     * @inheritDoc
     */
    public function setVideoCaption($videoCaption)
    {
        return $this->setData(self::VIDEO_CAPTION, $videoCaption);
    }

    /**
     * @inheritDoc
     */
    public function getVideoDescription()
    {
        return $this->getData(self::VIDEO_DESCRIPTION);
    }

    /**
     * @inheritDoc
     */
    public function setVideoDescription($videoDescription)
    {
        return $this->setData(self::VIDEO_DESCRIPTION, $videoDescription);
    }

    /**
     * @inheritDoc
     */
    public function getVideoUrl()
    {
        return $this->getData(self::VIDEO_URL);
    }

    /**
     * @inheritDoc
     */
    public function setVideoUrl($videoUrl)
    {
        return $this->setData(self::VIDEO_URL, $videoUrl);
    }

    /**
     * @inheritDoc
     */
    public function getVideoFileName()
    {
        return $this->getData(self::VIDEO_FILE_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setVideoFileName($videoFileName)
    {
        return $this->setData(self::VIDEO_FILE_NAME, $videoFileName);
    }

    /**
     * @inheritDoc
     */
    public function getVideoType()
    {
        return $this->getData(self::VIDEO_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setVideoType($videoType)
    {
        return $this->setData(self::VIDEO_TYPE, $videoType);
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
    public function getSqlServId()
    {
        return $this->getData(self::SQL_SERV_ID);
    }

    /**
     * @inheritDoc
     */
    public function setSqlServId($sqlServId)
    {
        return $this->setData(self::SQL_SERV_ID, $sqlServId);
    }
}

