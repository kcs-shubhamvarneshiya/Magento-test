<?php
declare(strict_types=1);

namespace Rysun\DimensionImages\Api\Data;

interface DimensionImagesInterface
{

    const IMAGE_DESCRIPTION = 'image_description';
    const IMAGE_CAPTION = 'image_caption';
    const IS_ACTIVE = 'is_active';
    const DIMENSIONIMAGES_ID = 'dimensionimages_id';
    const IMAGE_ALT_TAG = 'image_alt_tag';
    const IMAGE_URL = 'image_url';
    const IMAGE_FILE_NAME = 'image_file_name';
    const IMAGE_ID = 'image_id';
    const SQL_SERV_PROD_ID = 'sql_serv_prod_id';
    const IMAGE_TYPE = 'image_type';

    /**
     * Get dimensionimages_id
     * @return string|null
     */
    public function getDimensionimagesId();

    /**
     * Set dimensionimages_id
     * @param string $dimensionimagesId
     * @return \Rysun\DimensionImages\DimensionImages\Api\Data\DimensionImagesInterface
     */
    public function setDimensionimagesId($dimensionimagesId);

    /**
     * Get sql_serv_prod_id
     * @return string|null
     */
    public function getSqlServProdId();

    /**
     * Set sql_serv_prod_id
     * @param string $sqlServProdId
     * @return \Rysun\DimensionImages\DimensionImages\Api\Data\DimensionImagesInterface
     */
    public function setSqlServProdId($sqlServProdId);

    /**
     * Get image_id
     * @return string|null
     */
    public function getImageId();

    /**
     * Set image_id
     * @param string $imageId
     * @return \Rysun\DimensionImages\DimensionImages\Api\Data\DimensionImagesInterface
     */
    public function setImageId($imageId);

    /**
     * Get image_caption
     * @return string|null
     */
    public function getImageCaption();

    /**
     * Set image_caption
     * @param string $imageCaption
     * @return \Rysun\DimensionImages\DimensionImages\Api\Data\DimensionImagesInterface
     */
    public function setImageCaption($imageCaption);

    /**
     * Get image_description
     * @return string|null
     */
    public function getImageDescription();

    /**
     * Set image_description
     * @param string $imageDescription
     * @return \Rysun\DimensionImages\DimensionImages\Api\Data\DimensionImagesInterface
     */
    public function setImageDescription($imageDescription);

    /**
     * Get image_url
     * @return string|null
     */
    public function getImageUrl();

    /**
     * Set image_url
     * @param string $imageUrl
     * @return \Rysun\DimensionImages\DimensionImages\Api\Data\DimensionImagesInterface
     */
    public function setImageUrl($imageUrl);

    /**
     * Get image_file_name
     * @return string|null
     */
    public function getImageFileName();

    /**
     * Set image_file_name
     * @param string $imageFileName
     * @return \Rysun\DimensionImages\DimensionImages\Api\Data\DimensionImagesInterface
     */
    public function setImageFileName($imageFileName);

    /**
     * Get image_alt_tag
     * @return string|null
     */
    public function getImageAltTag();

    /**
     * Set image_alt_tag
     * @param string $imageAltTag
     * @return \Rysun\DimensionImages\DimensionImages\Api\Data\DimensionImagesInterface
     */
    public function setImageAltTag($imageAltTag);

    /**
     * Get image_type
     * @return string|null
     */
    public function getImageType();

    /**
     * Set image_type
     * @param string $imageType
     * @return \Rysun\DimensionImages\DimensionImages\Api\Data\DimensionImagesInterface
     */
    public function setImageType($imageType);

    /**
     * Get is_active
     * @return string|null
     */
    public function getIsActive();

    /**
     * Set is_active
     * @param string $isActive
     * @return \Rysun\DimensionImages\DimensionImages\Api\Data\DimensionImagesInterface
     */
    public function setIsActive($isActive);
}

