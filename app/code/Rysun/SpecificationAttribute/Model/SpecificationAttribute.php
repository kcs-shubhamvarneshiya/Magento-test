<?php
declare(strict_types=1);

namespace Rysun\SpecificationAttribute\Model;

use Magento\Framework\Model\AbstractModel;
use Rysun\SpecificationAttribute\Api\Data\SpecificationAttributeInterface;

class SpecificationAttribute extends AbstractModel implements SpecificationAttributeInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Rysun\SpecificationAttribute\Model\ResourceModel\SpecificationAttribute::class);
    }

    /**
     * @inheritDoc
     */
    public function getSpecificationattributeId()
    {
        return $this->getData(self::SPECIFICATIONATTRIBUTE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setSpecificationattributeId($specificationattributeId)
    {
        return $this->setData(self::SPECIFICATIONATTRIBUTE_ID, $specificationattributeId);
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
    public function getAttributeCode()
    {
        return $this->getData(self::ATTRIBUTE_CODE);
    }

    /**
     * @inheritDoc
     */
    public function setAttributeCode($attributeCode)
    {
        return $this->setData(self::ATTRIBUTE_CODE, $attributeCode);
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

