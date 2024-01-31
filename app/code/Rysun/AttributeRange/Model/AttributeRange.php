<?php
declare(strict_types=1);

namespace Rysun\AttributeRange\Model;

use Magento\Framework\Model\AbstractModel;
use Rysun\AttributeRange\Api\Data\AttributeRangeInterface;

class AttributeRange extends AbstractModel implements AttributeRangeInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Rysun\AttributeRange\Model\ResourceModel\AttributeRange::class);
    }

    /**
     * @inheritDoc
     */
    public function getAttributerangeId()
    {
        return $this->getData(self::ATTRIBUTERANGE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setAttributerangeId($attributerangeId)
    {
        return $this->setData(self::ATTRIBUTERANGE_ID, $attributerangeId);
    }

    /**
     * @inheritDoc
     */
    public function getAttributeRangeDesc()
    {
        return $this->getData(self::ATTRIBUTE_RANGE_DESC);
    }

    /**
     * @inheritDoc
     */
    public function setAttributeRangeDesc($attributeRangeDesc)
    {
        return $this->setData(self::ATTRIBUTE_RANGE_DESC, $attributeRangeDesc);
    }

    /**
     * @inheritDoc
     */
    public function getAttributeId()
    {
        return $this->getData(self::ATTRIBUTE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setAttributeId($attributeId)
    {
        return $this->setData(self::ATTRIBUTE_ID, $attributeId);
    }

    /**
     * @inheritDoc
     */
    public function getMinValue()
    {
        return $this->getData(self::MIN_VALUE);
    }

    /**
     * @inheritDoc
     */
    public function setMinValue($minValue)
    {
        return $this->setData(self::MIN_VALUE, $minValue);
    }

    /**
     * @inheritDoc
     */
    public function getMaxValue()
    {
        return $this->getData(self::MAX_VALUE);
    }

    /**
     * @inheritDoc
     */
    public function setMaxValue($maxValue)
    {
        return $this->setData(self::MAX_VALUE, $maxValue);
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

