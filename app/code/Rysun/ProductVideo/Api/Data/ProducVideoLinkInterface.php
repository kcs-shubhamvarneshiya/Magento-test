<?php
declare(strict_types=1);

namespace Rysun\ProductVideo\Api\Data;

interface ProducVideoLinkInterface
{

    const IS_ACTIVE = 'is_active';
    const SORT_ORDER = 'sort_order';
    const PRODUCVIDEOLINK_ID = 'producvideolink_id';
    const VIDEO_ID = 'video_id';
    const SQL_SERV_PROD_ID = 'sql_serv_prod_id';

    /**
     * Get producvideolink_id
     * @return string|null
     */
    public function getProducvideolinkId();

    /**
     * Set producvideolink_id
     * @param string $producvideolinkId
     * @return \Rysun\ProductVideo\ProducVideoLink\Api\Data\ProducVideoLinkInterface
     */
    public function setProducvideolinkId($producvideolinkId);

    /**
     * Get sql_serv_prod_id
     * @return string|null
     */
    public function getSqlServProdId();

    /**
     * Set sql_serv_prod_id
     * @param string $sqlServProdId
     * @return \Rysun\ProductVideo\ProducVideoLink\Api\Data\ProducVideoLinkInterface
     */
    public function setSqlServProdId($sqlServProdId);

    /**
     * Get video_id
     * @return string|null
     */
    public function getVideoId();

    /**
     * Set video_id
     * @param string $videoId
     * @return \Rysun\ProductVideo\ProducVideoLink\Api\Data\ProducVideoLinkInterface
     */
    public function setVideoId($videoId);

    /**
     * Get sort_order
     * @return string|null
     */
    public function getSortOrder();

    /**
     * Set sort_order
     * @param string $sortOrder
     * @return \Rysun\ProductVideo\ProducVideoLink\Api\Data\ProducVideoLinkInterface
     */
    public function setSortOrder($sortOrder);

    /**
     * Get is_active
     * @return string|null
     */
    public function getIsActive();

    /**
     * Set is_active
     * @param string $isActive
     * @return \Rysun\ProductVideo\ProducVideoLink\Api\Data\ProducVideoLinkInterface
     */
    public function setIsActive($isActive);
}

