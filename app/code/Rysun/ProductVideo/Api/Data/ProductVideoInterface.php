<?php
declare(strict_types=1);

namespace Rysun\ProductVideo\Api\Data;

interface ProductVideoInterface
{

    const SQL_SERV_ID = 'sql_serv_id';
    const IS_ACTIVE = 'is_active';
    const VIDEO_TYPE = 'video_type';
    const VIDEO_FILE_NAME = 'video_file_name';
    const PRODUCTVIDEO_ID = 'productvideo_id';
    const VIDEO_URL = 'video_url';
    const VIDEO_DESCRIPTION = 'video_description';
    const VIDEO_CAPTION = 'video_caption';

    /**
     * Get productvideo_id
     * @return string|null
     */
    public function getProductvideoId();

    /**
     * Set productvideo_id
     * @param string $productvideoId
     * @return \Rysun\ProductVideo\ProductVideo\Api\Data\ProductVideoInterface
     */
    public function setProductvideoId($productvideoId);

    /**
     * Get video_caption
     * @return string|null
     */
    public function getVideoCaption();

    /**
     * Set video_caption
     * @param string $videoCaption
     * @return \Rysun\ProductVideo\ProductVideo\Api\Data\ProductVideoInterface
     */
    public function setVideoCaption($videoCaption);

    /**
     * Get video_description
     * @return string|null
     */
    public function getVideoDescription();

    /**
     * Set video_description
     * @param string $videoDescription
     * @return \Rysun\ProductVideo\ProductVideo\Api\Data\ProductVideoInterface
     */
    public function setVideoDescription($videoDescription);

    /**
     * Get video_url
     * @return string|null
     */
    public function getVideoUrl();

    /**
     * Set video_url
     * @param string $videoUrl
     * @return \Rysun\ProductVideo\ProductVideo\Api\Data\ProductVideoInterface
     */
    public function setVideoUrl($videoUrl);

    /**
     * Get video_file_name
     * @return string|null
     */
    public function getVideoFileName();

    /**
     * Set video_file_name
     * @param string $videoFileName
     * @return \Rysun\ProductVideo\ProductVideo\Api\Data\ProductVideoInterface
     */
    public function setVideoFileName($videoFileName);

    /**
     * Get video_type
     * @return string|null
     */
    public function getVideoType();

    /**
     * Set video_type
     * @param string $videoType
     * @return \Rysun\ProductVideo\ProductVideo\Api\Data\ProductVideoInterface
     */
    public function setVideoType($videoType);

    /**
     * Get is_active
     * @return string|null
     */
    public function getIsActive();

    /**
     * Set is_active
     * @param string $isActive
     * @return \Rysun\ProductVideo\ProductVideo\Api\Data\ProductVideoInterface
     */
    public function setIsActive($isActive);

    /**
     * Get sql_serv_id
     * @return string|null
     */
    public function getSqlServId();

    /**
     * Set sql_serv_id
     * @param string $sqlServId
     * @return \Rysun\ProductVideo\ProductVideo\Api\Data\ProductVideoInterface
     */
    public function setSqlServId($sqlServId);
}

