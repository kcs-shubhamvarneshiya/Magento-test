<?php

namespace Lyonscg\SalesPad\Model\Api;

use Lyonscg\SalesPad\Api\Data\Api\ErrorLogInterface;

class ErrorLog extends \Magento\Framework\Model\AbstractModel implements ErrorLogInterface
{
    protected function _construct()
    {
        $this->_init(\Lyonscg\SalesPad\Model\ResourceModel\Api\ErrorLog::class);
    }

    public function getLogId()
    {
        return $this->getData(self::LOG_ID);
    }

    public function setLogId($logId)
    {
        return $this->setData(self::LOG_ID, $logId);
    }

    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    public function getUrl()
    {
        return $this->getData(self::URL);
    }

    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    public function getRequest()
    {
        return $this->getData(self::REQUEST);
    }

    public function setRequest($request)
    {
        return $this->setData(self::REQUEST, $request);
    }

    public function getResponse()
    {
        return $this->getData(self::RESPONSE);
    }

    public function setResponse($response)
    {
        return $this->setData(self::RESPONSE, $response);
    }

    public function getIsException()
    {
        return $this->getData(self::IS_EXCEPTION);
    }

    public function setIsException($isException)
    {
        return $this->setData(self::IS_EXCEPTION, $isException);
    }

    public function getResponseCode()
    {
        return $this->getData(self::RESPONSE_CODE);
    }

    public function setResponseCode($responseCode)
    {
        return $this->setData(self::RESPONSE_CODE, $responseCode);
    }

    public function getCounter()
    {
        return $this->getData(self::COUNTER);
    }

    public function setCounter($counter)
    {
        return $this->setData(self::COUNTER, $counter);
    }

    public function getRelatedEntityType()
    {
        return $this->getData(self::RELATED_ENTITY_TYPE);
    }

    public function setRelatedEntityType($relatedEntityType)
    {
        return $this->setData(self::RELATED_ENTITY_TYPE, $relatedEntityType);
    }

    public function getRelatedEntityId()
    {
        return $this->getData(self::RELATED_ENTITY_ID);
    }

    public function setRelatedEntityId($relatedEntityId)
    {
        return $this->setData(self::RELATED_ENTITY_ID, $relatedEntityId);
    }
}
