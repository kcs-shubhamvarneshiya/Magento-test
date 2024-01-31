<?php
/**
 * Capgemini_VcServiceProductPrice
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_VcServiceProductPrice
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */

declare(strict_types=1);

namespace Capgemini\VcServiceProductPrice\Service\Product;

use Capgemini\VcServiceProductPrice\Helper\Data as ConfigData;
use Magento\Framework\App\State;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\UrlInterface;
use Psr\Log\LoggerInterface;

class Price
{
    /**
     * @var ConfigData
     */
    protected ConfigData $configHelper;

    /**
     * @var Curl
     */
    protected Curl $curl;

    /**
     * @var Json
     */
    protected Json $jsonSerializer;

    /**
     * @var UrlInterface
     */
    protected UrlInterface $url;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var State
     */
    protected State $appState;

    /**
     * @var array
     */
    protected array $errors = [];

    /**
     * Constructor
     *
     * @param ConfigData $configHelper
     * @param Curl $curl
     * @param Json $jsonSerializer
     * @param UrlInterface $url
     * @param LoggerInterface $logger
     * @param State $appState
     */
    public function __construct(
        ConfigData $configHelper,
        Curl $curl,
        Json $jsonSerializer,
        UrlInterface $url,
        LoggerInterface $logger,
        State $appState
    ) {
        $this->configHelper = $configHelper;
        $this->curl = $curl;
        $this->jsonSerializer = $jsonSerializer;
        $this->url = $url;
        $this->logger = $logger;
        $this->appState = $appState;
    }

    /**
     * Validate method
     *
     * @param array $accountNumbers
     * @param array $skus
     * @param string $currency
     * @return array
     */
    public function validate(array $accountNumbers, array $skus, $currency): array
    {
        $response = [];
        $formattedResponse = null;
        $debugData = [];
        $this->errors = [];

        if (!$this->checkConfig()) {
            return $this->errors;
        }

        if (empty($skus)) {
            $this->errors[] = __('At least one sku is required');
        }

        try {
            $requestData = [
                'products' => $skus,
                'currency' => $currency,
                'accounts' =>  $accountNumbers
            ];

            $debugData['request'] = $requestData;

            $requestParams = $this->prepareRequest($requestData);

            $timeBeforeRequest = microtime(true);
            $responseData = $this->makeRequest($requestParams);
            $timeAfterRequest = microtime(true);

            $formattedResponse = $this->formatResponse($skus, $responseData);

            $debugData = array_merge($debugData, [
                'response' => $responseData,
                'time' => $timeAfterRequest - $timeBeforeRequest
            ]);
        } catch (\Exception $exception) {
            $this->errors[] = $exception->getMessage();
        }

        $debugData['errors'] = $this->errors;
        if ($this->isLoggingEnabled()) {
            $this->logger->notice(
                'Price Lookup Debug: ' . $this->jsonSerializer->serialize($debugData)
            );
        }

        return is_array($formattedResponse) ? $formattedResponse : $response;
    }

    /**
     * @param string $params
     * @return string
     */
    private function makeRequest($params): string
    {
        $login = $this->configHelper->getConfigEndpointLogin();
        $password = $this->configHelper->getConfigEndpointPassword();

        if ($login && $password) {
            $this->curl->setCredentials($login, $password);
        }

        $this->curl->post($this->configHelper->getConfigEndpoint(), $params);

        return $this->curl->getBody();
    }

    /**
     * Prepare params for URL request
     *
     * @var array $params
     */
    private function prepareQueryParams($params)
    {
        return $this->jsonSerializer->serialize($params);
    }

    /**
     * @param $skus
     * @param $response
     * @return array
     */
    private function formatResponse($skus, $response): array
    {
        $responseData = $this->jsonSerializer->unserialize($response);
        $formattedResponse = [];

        if (isset($responseData['errors']) && !empty($responseData['errors'])) {
            $this->errors = array_merge($this->errors, $responseData['errors']);
        }
        if (isset($responseData['data'])) {
            foreach ($responseData['data'] as $item) {
                $formattedResponse[$item['sku']] = $item['price'];
            }
        }
        foreach ($skus as $sku) {
            if (!isset($formattedResponse[$sku])) {
                $this->logger->error("Price Lookup request error for SKU: $sku");
            }
        }

        return $formattedResponse;
    }

    /**
     * @param $requestData
     * @return bool|string
     */
    private function prepareRequest($requestData)
    {
        $timeout = $this->configHelper->getConfigTimeout();
        if ($timeout) {
            $this->curl->setTimeout($timeout);
        }

        $this->curl->setHeaders([
            'accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Api-Key' => $this->configHelper->getConfigApiKey(),
        ]);

        return $this->prepareQueryParams($requestData);
    }

    /**
     * @return bool
     */
    private function checkConfig(): bool
    {
        $apiKey = $this->configHelper->getConfigApiKey();
        $endpoint = $this->configHelper->getConfigEndpoint();

        if (!$apiKey) {
            $this->errors[] = __('API key is not configured');
        }

        if (!$endpoint) {
            $this->errors[] = __('Endpoint is not configured');
        }

        return $apiKey && $endpoint;
    }

    /**
     * @return bool
     */
    private function isLoggingEnabled(): bool
    {
        return (bool) $this->configHelper->getConfigDebug();
    }
}
