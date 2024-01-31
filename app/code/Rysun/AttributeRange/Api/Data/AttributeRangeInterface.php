<?php
declare(strict_types=1);

namespace Rysun\AttributeRange\Api\Data;

interface AttributeRangeInterface
{

    const ATTRIBUTE_RANGE_DESC = 'attribute_range_desc';
    const SQL_SERV_ID = 'sql_serv_id';
    const ATTRIBUTE_ID = 'attribute_id';
    const ATTRIBUTERANGE_ID = 'attributerange_id';
    const MAX_VALUE = 'max_value';
    const MIN_VALUE = 'min_value';
    const SORT_ORDER = 'sort_order';

    /**
     * Get attributerange_id
     * @return string|null
     */
    public function getAttributerangeId();

    /**
     * Set attributerange_id
     * @param string $attributerangeId
     * @return \Rysun\AttributeRange\AttributeRange\Api\Data\AttributeRangeInterface
     */
    public function setAttributerangeId($attributerangeId);

    /**
     * Get attribute_range_desc
     * @return string|null
     */
    public function getAttributeRangeDesc();

    /**
     * Set attribute_range_desc
     * @param string $attributeRangeDesc
     * @return \Rysun\AttributeRange\AttributeRange\Api\Data\AttributeRangeInterface
     */
    public function setAttributeRangeDesc($attributeRangeDesc);

    /**
     * Get attribute_id
     * @return string|null
     */
    public function getAttributeId();

    /**
     * Set attribute_id
     * @param string $attributeId
     * @return \Rysun\AttributeRange\AttributeRange\Api\Data\AttributeRangeInterface
     */
    public function setAttributeId($attributeId);

    /**
     * Get min_value
     * @return string|null
     */
    public function getMinValue();

    /**
     * Set min_value
     * @param string $minValue
     * @return \Rysun\AttributeRange\AttributeRange\Api\Data\AttributeRangeInterface
     */
    public function setMinValue($minValue);

    /**
     * Get max_value
     * @return string|null
     */
    public function getMaxValue();

    /**
     * Set max_value
     * @param string $maxValue
     * @return \Rysun\AttributeRange\AttributeRange\Api\Data\AttributeRangeInterface
     */
    public function setMaxValue($maxValue);

    /**
     * Get sort_order
     * @return string|null
     */
    public function getSortOrder();

    /**
     * Set sort_order
     * @param string $sortOrder
     * @return \Rysun\AttributeRange\AttributeRange\Api\Data\AttributeRangeInterface
     */
    public function setSortOrder($sortOrder);

    /**
     * Get sql_serv_id
     * @return string|null
     */
    public function getSqlServId();

    /**
     * Set sql_serv_id
     * @param string $sqlServId
     * @return \Rysun\AttributeRange\AttributeRange\Api\Data\AttributeRangeInterface
     */
    public function setSqlServId($sqlServId);
}

