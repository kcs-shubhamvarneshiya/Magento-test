<?php

namespace Lyonscg\SalesPad\Api\Data\Api;

interface ErrorLogInterface
{
    const LOG_ID = 'log_id';
    const CREATED_AT = 'created_at';
    const URL = 'url';
    const REQUEST = 'request';
    const RESPONSE = 'response';
    const IS_EXCEPTION = 'is_exception';
    const RESPONSE_CODE = 'response_code';
    const COUNTER = 'counter';
    const RELATED_ENTITY_TYPE = 'related_entity_type';
    const RELATED_ENTITY_ID = 'related_entity_id';

    /**
     * @return mixed
     */
    public function getLogId();

    /**
     * @param $id
     * @return $this
     */
    public function setLogId($logId);

    /**
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * @param $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @param $url
     * @return $this
     */
    public function setUrl($url);

    /**
     * @return string
     */
    public function getRequest();

    /**
     * @param $request
     * @return $this
     */
    public function setRequest($request);

    /**
     * @return string
     */
    public function getResponse();

    /**
     * @param $response
     * @return $this
     */
    public function setResponse($response);

    /**
     * @return boolean
     */
    public function getIsException();

    /**
     * @param $isException
     * @return $this
     */
    public function setIsException($isException);

    /**
     * @return string
     */
    public function getResponseCode();

    /**
     * @param $responseCode
     * @return $this
     */
    public function setResponseCode($responseCode);

    /**
     * @return int
     */
    public function getCounter();

    /**
     * @param $counter
     * @return $this
     */
    public function setCounter($counter);

    /**
     * @return string
     */
    public function getRelatedEntityType();

    /**
     * @param $relatedEntityType
     * @return $this
     */
    public function setRelatedEntityType($relatedEntityType);

    /**
     * @return int
     */
    public function getRelatedEntityId();

    /**
     * @param $relatedEntityId
     * @return $this
     */
    public function setRelatedEntityId($relatedEntityId);
}
