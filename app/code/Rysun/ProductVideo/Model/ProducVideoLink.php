<?php
declare(strict_types=1);

namespace Rysun\ProductVideo\Model;

use Magento\Framework\Model\AbstractModel;
use Rysun\ProductVideo\Api\Data\ProducVideoLinkInterface;

class ProducVideoLink extends AbstractModel implements ProducVideoLinkInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Rysun\ProductVideo\Model\ResourceModel\ProducVideoLink::class);
    }

    /**
     * @inheritDoc
     */
    public function getProducvideolinkId()
    {
        return $this->getData(self::PRODUCVIDEOLINK_ID);
    }

    /**
     * @inheritDoc
     */
    public function setProducvideolinkId($producvideolinkId)
    {
        return $this->setData(self::PRODUCVIDEOLINK_ID, $producvideolinkId);
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
    public function getVideoId()
    {
        return $this->getData(self::VIDEO_ID);
    }

    /**
     * @inheritDoc
     */
    public function setVideoId($videoId)
    {
        return $this->setData(self::VIDEO_ID, $videoId);
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

