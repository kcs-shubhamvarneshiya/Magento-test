<?php

namespace Lyonscg\SalesPad\Model\Api;

use Lyonscg\SalesPad\Model\Api;

class Session
{
    const API_SESSION_PING = 'api/Session/Ping';
    const API_SESSION_ACTIVE_USERS = 'api/Session/ActiveUsers';
    const STATUS_CODE = 'StatusCode';
    const EXT_USER_COUNT = 'ActiveExternalUserCount';

    /**
     * @var \Lyonscg\SalesPad\Model\Api
     */
    protected $api;

    protected $logger;

    protected $sessionId;

    protected $sessionCollectionFactory;

    protected $sessionFactory;

    protected $sessionResource;

    /**
     * Session constructor.
     * @param Api $api
     * @param Logger $logger
     */
    public function __construct(
        Api $api,
        \Lyonscg\SalesPad\Model\Api\Logger $logger,
        \Lyonscg\SalesPad\Model\ResourceModel\Session\CollectionFactory $collectionFactory,
        \Lyonscg\SalesPad\Model\SessionFactory $sessionFactory,
        \Lyonscg\SalesPad\Model\ResourceModel\Session $sessionResource
    ) {
        $this->api = $api;
        $this->logger = $logger;
        $this->sessionId = null;
        $this->sessionCollectionFactory = $collectionFactory;
        $this->sessionFactory = $sessionFactory;
        $this->sessionResource = $sessionResource;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function ping()
    {
        $response = $this->api->callApi(self::API_SESSION_PING, Api::GET);
        if ($response->getStatusCode() !== 200) {
            return false;
        }
        $responseBody = $this->api->extractJson($response);
        return ($responseBody[self::STATUS_CODE] ?? -1) == 0;
    }

    /**
     * @param $sessionId string
     * @return bool|integer
     * @throws \Exception
     */
    public function activeUsers($sessionId)
    {
        if ($sessionId === false) {
            $sessionId = $this->sessionId;
        }
        $response = $this->api->callApiWithSession(self::API_SESSION_ACTIVE_USERS, API::GET, $sessionId);
        if ($response->getStatusCode() !== 200) {
            return false;
        }
        $responseBody = $this->api->extractJson($response);
        return (intval(trim($responseBody[self::EXT_USER_COUNT])) ?? false);
    }

    public function setSessionId($cacheCheck = true)
    {
        if ($cacheCheck) {
            $session = $this->_getDbSession();
            if ($session->getId() && $this->api->ping($session->getSessionId())) {
                $this->_updateSession($session);
                return $this;
            } else {
                $this->_updateSession($session, false);
            }
        }

        $this->sessionId = $this->_retrieveSessionId();
        $this->_setDbSessionId();
        return $this;
    }

    /**
     * @return \Lyonscg\SalesPad\Model\ResourceModel\Session\Collection
     */
    protected function _getCollection()
    {
        return $this->sessionCollectionFactory->create();
    }

    /**
     * @return \Lyonscg\SalesPad\Model\ResourceModel\Session
     */
    protected function _getDbSession()
    {
        $collection = $this->_getCollection();
        /** @var \Lyonscg\SalesPad\Model\ResourceModel\Session $session */
        $session = $collection->addActiveFilter()->getFirstItem();
        return $session;
    }

    protected function _updateSession($session, $state = true)
    {
        if (!$session->getId()) {
            return;
        }
        $active = $state ? 1 : 0;
        $this->session = $session;
        $session->setActive($active);
        $session->setStamp(date('Y-m-d H:i:s'));
        $this->sessionResource->save($session);
    }

    /**
     * @return bool|string
     */
    protected function _retrieveSessionId()
    {
        $attempts = 3;
        for ($attempt = 1; $attempt <= $attempts; $attempt++) {
            try {
                $this->logger->debug("get session attempt: $attempt");
                $sessionId = $this->api->requestSessionId();
                if ($sessionId !== false) {
                    return $sessionId;
                } else {
                    sleep(1);
                    continue;
                }
            } catch (\Exception $e) {
                $this->logger->debug("exception getting session: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                sleep(1);
                continue;
            }
        }
        $this->logger->debug("unable to get salespad session");
        return false;
    }

    /**
     * @throws \Exception
     */
    protected function _setDbSessionId()
    {
        $currentUsers = $this->activeUsers($this->sessionId);
        if ($currentUsers === false) {
            $currentUsers = 1;
        }
        /** @var \Lyonscg\SalesPad\Model\Session $session */
        $session = $this->sessionFactory->create();
        $session->setSessionId($this->sessionId);
        $session->setActive(1);
        $session->setCurrentUsers($currentUsers);
        $this->sessionResource->save($session);
    }
}
