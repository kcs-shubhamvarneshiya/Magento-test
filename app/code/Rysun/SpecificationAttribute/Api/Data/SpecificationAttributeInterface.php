<?php
declare(strict_types=1);

namespace Rysun\SpecificationAttribute\Api\Data;

interface SpecificationAttributeInterface
{

    const SQL_SERV_ID = 'sql_serv_id';
    const ATTRIBUTE_ID = 'attribute_id';
    const COLLECTION_ID = 'collection_id';
    const ATTRIBUTE_CODE = 'attribute_code';
    const CATEGORY_ID = 'category_id';
    const SPECIFICATIONATTRIBUTE_ID = 'specificationattribute_id';

    /**
     * Get specificationattribute_id
     * @return string|null
     */
    public function getSpecificationattributeId();

    /**
     * Set specificationattribute_id
     * @param string $specificationattributeId
     * @return \Rysun\SpecificationAttribute\SpecificationAttribute\Api\Data\SpecificationAttributeInterface
     */
    public function setSpecificationattributeId($specificationattributeId);

    /**
     * Get category_id
     * @return string|null
     */
    public function getCategoryId();

    /**
     * Set category_id
     * @param string $categoryId
     * @return \Rysun\SpecificationAttribute\SpecificationAttribute\Api\Data\SpecificationAttributeInterface
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
     * @return \Rysun\SpecificationAttribute\SpecificationAttribute\Api\Data\SpecificationAttributeInterface
     */
    public function setCollectionId($collectionId);

    /**
     * Get attribute_id
     * @return string|null
     */
    public function getAttributeId();

    /**
     * Set attribute_id
     * @param string $attributeId
     * @return \Rysun\SpecificationAttribute\SpecificationAttribute\Api\Data\SpecificationAttributeInterface
     */
    public function setAttributeId($attributeId);

    /**
     * Get attribute_code
     * @return string|null
     */
    public function getAttributeCode();

    /**
     * Set attribute_code
     * @param string $attributeCode
     * @return \Rysun\SpecificationAttribute\SpecificationAttribute\Api\Data\SpecificationAttributeInterface
     */
    public function setAttributeCode($attributeCode);

    /**
     * Get sql_serv_id
     * @return string|null
     */
    public function getSqlServId();

    /**
     * Set sql_serv_id
     * @param string $sqlServId
     * @return \Rysun\SpecificationAttribute\SpecificationAttribute\Api\Data\SpecificationAttributeInterface
     */
    public function setSqlServId($sqlServId);
}

