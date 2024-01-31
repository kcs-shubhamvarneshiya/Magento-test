<?php
/**
 * Capgemini_PartnersInsight
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\PartnersInsight\Model\Api;

use Capgemini\PartnersInsight\Model\Config;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\UrlInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;

/**
 * Rest API client
 */
class Client
{
    const ENCTYPE = 'application/json;charset=UTF-8';

    /**
     * @var Curl
     */
    protected $curl;
    /**
     * @var Json
     */
    protected $jsonSerializer;
    /**
     * @var UrlInterface
     */
    protected $url;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var Config
     */
    protected $config;

    /**
     * Constructor
     *
     * @param Curl $curl
     * @param Json $jsonSerializer
     * @param Config $config
     * @param UrlInterface $url
     * @param LoggerInterface $logger
     */
    public function __construct(
        Curl $curl,
        Json $jsonSerializer,
        Config $config,
        UrlInterface $url,
        LoggerInterface $logger
    ) {

        $this->curl = $curl;
        $this->jsonSerializer = $jsonSerializer;
        $this->url = $url;
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * Send clients/integrate request
     */
    public function send(string $action = null)
    {
        $url = $this->getRequestUrl();
        if ($action != null) {
            $url = $url.$action;
        }


        $this->curl->setHeaders($this->getHeaders());
        try {
            if ($this->config->getPiConfig('api_use_mock')) {
                $this->curl->setOption(CURLOPT_SSL_VERIFYHOST, false);
                $this->curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
                if ($this->config->getPiConfig('api_mock_username')
                    && $this->config->getPiConfig('api_mock_password')) {
                    $this->curl->setCredentials(
                        $this->config->getPiConfig('api_mock_username'),
                        $this->config->getPiConfig('api_mock_password')
                    );
                }
            }
            $this->curl->get($url);
            $response = $this->curl->getBody();
            $this->logger->error(" Customer API");
            $this->logger->error($response);
            $this->logger->error($url);
            $this->logger->error(" Customer API End");
            $response = $this->jsonSerializer->unserialize($response);
            return $response;
        } catch (\Exception $e) {
            $this->logger->error('PI API error: ' . $e->__toString());
            return null;
        }
    }

    /**
     * Send clients/integrate request
     */
    public function post(string $action = null, array $params = null)
    {
        $url = $this->getRequestUrl();

        if ($action != null) {
            $url = $url.$action;
        }

        $this->logger->warning('PI API URL WITH ACTION: ' . $url);


        $this->curl->setHeaders($this->getHeaders());
        try {
            if ($this->config->getPiConfig('api_use_mock')) {
                $this->curl->setOption(CURLOPT_SSL_VERIFYHOST, false);
                $this->curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
                if ($this->config->getPiConfig('api_mock_username')
                    && $this->config->getPiConfig('api_mock_password')) {
                    $this->curl->setCredentials(
                        $this->config->getPiConfig('api_mock_username'),
                        $this->config->getPiConfig('api_mock_password')
                    );
                }
            }
            $this->curl->addHeader("Content-Type", "application/json");

            $this->curl->post($url, $this->jsonSerializer->serialize($params));
            $response = $this->curl->getBody();
            $response = $this->jsonSerializer->unserialize($response);
            return $response;
        } catch (\Exception $e) {
            $this->logger->error('PI API error: ' . $e->__toString());
            return null;
        }
    }

    /**
     * Get resource URL from config
     *
     * @return string
     */
    protected function getRequestUrl()
    {
        $params = $this->getParams();
        if ($this->config->getPiConfig('api_use_mock')) {
            return $this->url->getUrl('partners-insight/api/mock', $params);
        } else {
            $url = $this->config->getPiConfig('api_endpoint');
//            $paramList = [];
//            foreach ($params as $key => $value) {
//                $paramList[] = urlencode($key) . '=' . urlencode($value);
//            }
//            $url .= '?' . implode('&', $paramList);
            return $url;
        }
    }

    /**
     * Prepare request params
     *
     * @return array
     */
    protected function getParams()
    {
        return [
            'resource' => $this->config->getPiConfig('api_resource'),
            'userName' => $this->config->getPiConfig('api_username'),
        ];
    }

    /**
     * Prepare request headers
     *
     * @return array
     */
    protected function getHeaders()
    {
        return [
            'accept' => 'application/json',
            'X-Api-Key' => $this->config->getPiConfig('api_x_api_key'),
        ];
    }
}
