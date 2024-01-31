<?php


namespace Lyonscg\SalesPad\Model;

use Lyonscg\SalesPad\Api\ErrorLogRepositoryInterface;
use Lyonscg\SalesPad\Model\Api\Logger;
use Lyonscg\SalesPad\Model\Api\SalesPad\SalesDocument;
use Laminas\Http\ClientFactory;

class Api
{
    const POST    = \Laminas\Http\Request::METHOD_POST;
    const PUT     = \Laminas\Http\Request::METHOD_PUT;
    const GET     = \Laminas\Http\Request::METHOD_GET;
    const DELETE  = \Laminas\Http\Request::METHOD_DELETE;
    const ENCTYPE = 'application/json;charset=UTF-8';

    const API_SESSION_PING         = 'api/Session/Ping';
    const API_SESSION_ACTIVE_USERS = 'api/Session/ActiveUsers';
    const STATUS_CODE              = 'StatusCode';
    const EXT_USER_COUNT           = 'ActiveExternalUserCount';

    const API_SESSION      = 'api/Session';
    /**
     * @var ClientFactory
     */
    protected $httpClientFactory;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $serializer;

    /**
     * @var ErrorLogRepositoryInterface
     */
    protected $errorLogRepository;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var string|null
     */
    protected $sessionId = null;

    protected $sessionCollectionFactory;

    protected $sessionFactory;

    protected $sessionResource;

    /**
     * @var bool
     */
    protected $responseFromApiIsEmpty = false;

    /**
     * @var \Lyonscg\SalesPad\Helper\SyncStoreManager
     */
    protected $syncStoreManager;

    private $excludes = [
        self::API_SESSION,
        self::API_SESSION_ACTIVE_USERS,
        self::API_SESSION_PING,
        self::EXT_USER_COUNT,
        self::STATUS_CODE
    ];

    /**
     * Api constructor.
     * @param ClientFactory $httpClientFactory
     * @param \Magento\Framework\Serialize\Serializer\Json $serializer
     */
    public function __construct(
        ClientFactory $httpClientFactory,
        \Magento\Framework\Serialize\Serializer\Json $serializer,
        ErrorLogRepositoryInterface $errorLogRepository,
        Logger $logger,
        Config $config,
        \Lyonscg\SalesPad\Model\ResourceModel\Session\CollectionFactory $collectionFactory,
        \Lyonscg\SalesPad\Model\SessionFactory $sessionFactory,
        \Lyonscg\SalesPad\Model\ResourceModel\Session $sessionResource,
        \Lyonscg\SalesPad\Helper\SyncStoreManager $syncStoreManager
    ) {
        $this->httpClientFactory = $httpClientFactory;
        $this->serializer = $serializer;
        $this->errorLogRepository = $errorLogRepository;
        $this->logger = $logger;
        $this->config = $config;
        $this->sessionCollectionFactory = $collectionFactory;
        $this->sessionFactory = $sessionFactory;
        $this->sessionResource = $sessionResource;
        $this->syncStoreManager = $syncStoreManager;
    }

    /**
     * @param string $action
     * @param string $method
     * @param array $requestParams
     * @return \Laminas\Http\Response
     * @throws \Exception
     */
    public function callApi($action, $method, $requestParams = [])
    {
        try {
            $url = $this->_getUrl($action);
            return $this->_callApi($url, $method, $this->_getHeaders(), $requestParams);
        } catch (\Exception $e) {
            $this->logger->debug('Exception in callApi: ' . $e->getMessage());
            $this->errorLogRepository->createFromException($url, $requestParams, $e);
            return false;
        }
    }

    /**
     * @param $action
     * @param $method
     * @param $sessionId
     * @param array $requestParams
     * @return \Laminas\Http\Response
     * @throws \Exception
     */
    public function callApiWithSession($action, $method, $sessionId, $requestParams = [])
    {
        $url = $this->_getUrl($action);
        try {
            return $this->_callApi($url, $method, $this->_getHeaders($sessionId), $requestParams);
        } catch (\Exception $e) {
            $this->logger->debug('Exception in callApiWithSession: ' . $e->getMessage());
            $this->errorLogRepository->createFromException($url, $requestParams, $e);
            return false;
        }
    }

    /**
     * @param $action
     * @param $method
     * @param $headers
     * @param $requestParams
     * @return false|\Laminas\Http\Response
     * @throws \Exception
     */
    public function callApiWithAdditionalHeaders($action, $method, $headers, $requestParams = [])
    {
        $allHeaders = $this->_getHeaders();
        if (is_array($headers) && !empty($headers)) {
            $allHeaders = array_merge($allHeaders, $headers);
        }
        $url = $this->_getUrl($action);
        try {
            return $this->_callApi($url, $method, $allHeaders, $requestParams);
        } catch (\Exception $e) {
            $this->logger->debug('Exception in callApiWithAdditionalHeaders: ' . $e->getMessage());
            $this->errorLogRepository->createFromException($url, $requestParams, $e);
            return false;
        }
    }

    /**
     * @param $url
     * @param $method
     * @param $headers
     * @param $requestParams
     * @return \Laminas\Http\Response
     * @throws \Exception
     */
    protected function _callApi($url, $method, $headers, $requestParams = [])
    {
        if ($this->responseFromApiIsEmpty) {
            throw new \Exception('Unable to read response from SalesPad API');
        }
        if ($method === self::DELETE) {
            return $this->_callApiDelete($url, $headers, $requestParams);
        }
        /** @var \Laminas\Http\Client $client */
        $client = $this->httpClientFactory->create();

        $client->setUri(trim($url));
        $client->setMethod($method);
        $client->setHeaders($headers);
        $client->setOptions($this->_getConfig());

        $serializedParams = $this->serializer->serialize($requestParams);
        if (!empty($requestParams)) {
            $client->setRawBody($serializedParams)->setEncType(self::ENCTYPE);
        }

        try {
            $response = $client->send();
        } catch (\Exception $e) {
            $this->logger->debug('Request: ' . $url . ' : ' . $method . ' : ' . $e->getMessage());
            if (strpos($e->getMessage(), 'Unable to read response') !== false) {
                $this->responseFromApiIsEmpty = true;
            }
            throw $e;
        }

        $status = $response->getStatusCode();
        $this->logInteraction($url, $method, $serializedParams, $status, $response->getBody());

        if (!($status === 200 || $status === 201 ||
            ($status == 409 && $url == $this->config->getApiUrl() . '/' . SalesDocument::ACTION_PAYFABRIC))) {
            $this->_createErrorLogEntry($response, $client->getLastRawRequest(), $url);
        } else {
            $this->errorLogRepository->deleteOnSuccess($url, $client->getLastRawRequest());
        }

        unset($client);
        return $response;
    }

    protected function _createErrorLogEntry(\Laminas\Http\Response $response, $request, $url)
    {
        $this->errorLogRepository->create(
            $url,
            $request,
            $response->getBody(),
            $response->getStatusCode(),
        );
    }

    /**
     * @param $url
     * @param $headers
     * @param array $requestParams
     * @return \Laminas\Http\Response
     */
    protected function _callApiDelete($url, $headers, $requestParams = [])
    {
        // \Magento\Framework\HTTP\Adapter\Curl does not handle DELETE, it sends it as GET
        $processedHeaders = [];
        foreach ($headers as $k => $v) {
            $processedHeaders[] = $k . ': ' . $v;
        }
        if (!empty($requestParams)) {
            $processedHeaders[] = 'Content-Type: application/json';
        }
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => trim($url),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 0,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => $processedHeaders,
            CURLOPT_HEADER => true
        ]);

        $requestBody = '';
        if (!empty($requestParams)) {
            $requestBody = json_encode($requestParams);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
        }
        $response = curl_exec($curl);
        curl_close($curl);
        $response = \Laminas\Http\Response::fromString($response);

        $status = $response->getStatusCode();
        if (!($status === 200 || $status === 201)) {
            $this->_createErrorLogEntry($response, $requestBody, $url);
        }
        return $response;
    }

    /**
     * @param bool $sessionId
     * @return array
     * @throws \Exception
     */
    protected function _getHeaders($sessionId = false)
    {
        if ($sessionId === false) {
            $sessionId = $this->getSessionId();
        }
        $headers = [];
        $headers['Session-ID'] = $sessionId;
        return $headers;
    }

    /**
     * @return array
     */
    protected function _getConfig()
    {
        return [
            'maxredirects' => 0,
            'timeout'      => 120
        ];
    }

    /**
     * @param string $action
     * @return string
     */
    protected function _getUrl($action)
    {
        try {
            $storeState = $this->syncStoreManager->resolveStoreForSync();
        } catch (\Exception $exception) {
            $this->logger->debug($exception->getMessage() . ' Using the default store.');
            $storeState = [
                'resolved' => false,
                'current'  => false
            ];
        }

        return $this->syncStoreManager->emulate(
            function () use ($action) {
                return $this->config->getApiUrl() . '/' . $action;
            },
            $storeState['resolved'],
            $storeState['current']
        );
    }

    /**
     * @param \Laminas\Http\Response $response
     * @return array|bool|float|int|mixed|string|null
     */
    public function extractJson(\Laminas\Http\Response $response)
    {
        return $this->serializer->unserialize($response->getBody());
    }

    /**
     * @return bool|mixed|string|null
     */
    public function getSessionId()
    {
        if ($this->sessionId === null) {
            $this->setSessionId();
        }
        return $this->sessionId;
    }

    /**
     * @return string[]
     */
    protected function _getSessionRequestHeaders()
    {
        $user = $this->config->getApiUsername();
        $pass = $this->config->getApiPassword();
        $basic = base64_encode($user . ':' . $pass);
        return [
            'Authorization' => "Basic $basic"
        ];
    }

    /**
     * @return bool|mixed
     */
    protected function _requestSessionId()
    {
        $action = self::API_SESSION;
        $headers = $this->_getSessionRequestHeaders();
        $url = $this->_getUrl($action);
        try {
            $response = $this->_callApi($url, self::GET, $headers);
            if ($response) {
                $responseCode = $response->getStatusCode();
                if ($responseCode == 200) {
                    $responseBody = $this->extractJson($response);
                    return $responseBody['SessionID'] ?? false;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (\Exception $e) {
            $this->logger->debug("Exception while getting session: " . $e);
            return false;
        }
        return false;
    }

    /**
     * @param string $sessionId
     * @return bool
     * @throws \Exception
     */
    public function ping($sessionId)
    {
        $response = $this->callApiWithSession(self::API_SESSION_PING, Api::GET, $sessionId);
        if (!$response || $response->getStatusCode() !== 200) {
            return false;
        }
        $responseBody = $this->extractJson($response);
        return ($responseBody['StatusCode'] === 'OK');
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
        $response = $this->callApiWithSession(self::API_SESSION_ACTIVE_USERS, API::GET, $sessionId);
        if (!$response || $response->getStatusCode() !== 200) {
            return false;
        }
        $responseBody = $this->extractJson($response);
        return (intval(trim($responseBody[self::EXT_USER_COUNT])) ?? false);
    }

    public function setSessionId($cacheCheck = true)
    {
        if ($cacheCheck) {
            $session = $this->_getDbSession();
            if ($session->getId() && $this->ping($session->getSessionId())) {
                $this->_updateSession($session);
                $this->sessionId = $session->getSessionId();
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
        $session = $collection
            ->addActiveFilter()
            ->addApiUrlFilter($this->config->getApiUrl())
            ->setOrder('id', 'desc')
            ->getFirstItem();
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
                $sessionId = $this->_requestSessionId();
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
        $session->setApiUrl($this->config->getApiUrl());
        $this->sessionResource->save($session);
    }

    /**
     * @param $filterParts
     * @param $endpoint
     * @return array|bool|float|int|mixed|string|null
     */
    public function searchByFilters($filterParts, $endpoint)
    {
        if (empty($filterParts)) {
            return false;
        }

        $filter = '?$filter=' . rawurlencode(implode(' and ', $filterParts));
        $action = sprintf($endpoint, $filter);
        try {
            $response = $this->callApi($action, Api::GET);
            if (!$response) {
                $this->logger->debug("Call to $action failed to return response object");
                return false;
            }
            $responseCode = $response->getStatusCode();
            if ($responseCode == 200 || $responseCode == 201) {
                return $this->extractJson($response);
            } else {
                $this->logger->debug(
                    "API call $action returned status code $responseCode"
                );
            }
        } catch (\Exception $e) {
            return false;
        }
        return false;
    }

    private function logInteraction($url, $method, $request, $status, $response)
    {
        foreach ($this->excludes as $exclude) {
            if (strpos($url, $exclude) !== false) {
                return;
            }
        }

        $this->logger->debug([
            'URL'         => $url,
            'Method'      => $method,
            'pid'         => getmypid(),
            'trace'       => (new \Exception('trace'))->getTraceAsString(),
            'Request'     => $request,
            'Status Code' => $status,
            'Response'    => $response
        ]);
    }
}
