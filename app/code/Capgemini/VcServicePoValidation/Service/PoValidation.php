<?php
/**
 * Capgemini_VcServicePoValidation
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\VcServicePoValidation\Service;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\UrlInterface;
use Psr\Log\LoggerInterface;

/**
 * PO Validation service client
 */
class PoValidation
{
    public const API_KEY_CONFIG_PATH = 'po_validation/client/api_key';
    public const ENDPOINT_CONFIG_PATH = 'po_validation/client/endpoint';
    public const TIMEOUT_CONFIG_PATH = 'po_validation/client/timeout';
    public const DEBUG_CONFIG_PATH = 'po_validation/client/debug';

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
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Constructor
     *
     * @param Curl $curl
     * @param Json $jsonSerializer
     * @param ScopeConfigInterface $scopeConfig
     * @param UrlInterface $url
     * @param LoggerInterface $logger
     */
    public function __construct(
        Curl $curl,
        Json $jsonSerializer,
        ScopeConfigInterface $scopeConfig,
        UrlInterface $url,
        LoggerInterface $logger
    ) {
        $this->curl = $curl;
        $this->jsonSerializer = $jsonSerializer;
        $this->scopeConfig = $scopeConfig;
        $this->url = $url;
        $this->logger = $logger;
    }

    /**
     * Validate PO number
     *
     * @param string $accountNumber
     * @param string $poNumber
     * @return bool
     * @throw LocalizedException | Exception
     */
    public function validate($accountNumber, $poNumber)
    {
        $url = $this->scopeConfig->getValue(self::ENDPOINT_CONFIG_PATH);
        $appKey = $this->scopeConfig->getValue(self::API_KEY_CONFIG_PATH);
        $timeout = $this->scopeConfig->getValue(self::TIMEOUT_CONFIG_PATH);
        $debug = $this->scopeConfig->getValue(self::DEBUG_CONFIG_PATH);

        if (!$url) {
            throw new LocalizedException(__('Endpoint is not configured'));
        }
        if (!$appKey) {
            throw new LocalizedException(__('API key is not configured'));
        }

        try {
            $this->curl->setHeaders([
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]);
            if ($timeout) {
                $this->curl->setTimeout($timeout);
            }
            $request = [[
                'apiKey' => $appKey,
                'customer' => $accountNumber,
                'poRef' => $poNumber,
            ]];
            $request = $this->jsonSerializer->serialize($request);
            if ($debug) {
                $this->logger->info('Request: ' . $request);
            }
            $timeStart = microtime(true);
            $this->curl->post($url, $request);
            $time = (microtime(true) - $timeStart);
            if ($this->curl->getStatus() == 200) {
                $response = $this->curl->getBody();
                if ($debug) {
                    $this->logger->info('Response: ' . $response . "; Time: $time sec.");
                }
                $result = $this->parseResponse($response, $poNumber);
                return $result;
            } else {
                throw new LocalizedException(__('Invalid response code: %code', ['code' => $this->curl->getStatus()]));
            }
        } catch (\Exception $e) {
            $this->logger->error('API error: ' . $e->__toString());
            throw $e;
        }
    }

    /**
     * Parse response and return isValidPo value
     *
     * @param $response
     * @return bool
     * @throw \Exception
     */
    protected function parseResponse($response, $poNumber)
    {
        $data = $this->jsonSerializer->unserialize($response);
        if (is_array($data) && isset($data[0]['isValidPo']) && isset($data[0]['poRef'])) {
            if ($data[0]['poRef'] == $poNumber) {
                return (bool)$data[0]['isValidPo'];
            } else {
                throw new \Exception('Invalid PO number : ' . $data[0]['poNumber']);
            }
        } else {
            throw new \Exception('Invalid response data : ' . $response);
        }
    }
}
