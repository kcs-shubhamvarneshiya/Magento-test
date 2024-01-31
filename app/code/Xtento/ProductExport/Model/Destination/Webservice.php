<?php

/**
 * Product:       Xtento_ProductExport
 * ID:            1950
 * Last Modified: 2016-04-14T15:37:57+00:00
 * File:          Model/Destination/Http.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Model\Destination;

use Bloomreach\Connector\Block\ConfigurationSettingsInterface;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\ScopeInterface;
use Xtento\ProductExport\Model\DestinationFactory;
use GuzzleHttp\ClientFactory;
use Xtento\ProductExport\Model\Log;

class Webservice extends AbstractClass
{
    private const XML_CONFIG_PATH_FOR_DATA_FEED_CONNECTION_SECTION = 'data_feed/connection/';
    private const FIELD_ENVIRONMENT = 'environment';
    private const ENVIRONMENT_DEPENDENT_FIELD_ENDPOINT = 'endpoint';
    private const ENVIRONMENT_DEPENDENT_FIELD_API_KEY = 'api_key';
    private const URI_TEMPLATE = 'accounts/%s/catalogs/%s/products';
    private const BYTES_IN_MEGABYTE = 1048576;

    private ClientFactory $clientFactory;

    public function __construct(
        Context $context,
        Registry $registry,
        ObjectManagerInterface $objectManager,
        ScopeConfigInterface $scopeConfig,
        DateTime $dateTime,
        Filesystem $filesystem,
        EncryptorInterface $encryptor,
        DestinationFactory $destinationFactory,
        ClientFactory $clientFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $objectManager,
            $scopeConfig,
            $dateTime,
            $filesystem,
            $encryptor,
            $destinationFactory,
            $resource,
            $resourceCollection,
            $data
        );

        $this->clientFactory = $clientFactory;
    }

    /**
     * @throws LocalizedException
     */
    public function sendToBloomreach($fileArray)
    {
        extract($this->deriveConfigData());

        $client = $this->clientFactory->create([
            'config' => [
                'base_uri'        => $endpoint,
                'allow_redirects' => false,
                'timeout'         => 120
            ]
        ]);
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json-patch+jsonlines',
            ]
        ];
        $uri = sprintf(self::URI_TEMPLATE, $accountId, $domainKey);

        foreach ($fileArray as $fileContent) {
            $options['body'] = $fileContent;

            try {
                $start = microtime(true);
                $response = $client->request('PUT', $uri, $options);
                $finish = microtime(true);
            } catch (GuzzleException $e) {
                $finish = microtime(true);
                $response = $e;
            }

            $this->logResults([
                'size'  => strlen($fileContent),
                'start' => $start,
                'finish' => $finish,
                'response' => $response
            ]);

            if ($response instanceof GuzzleException) {

                throw new LocalizedException(__($response->getMessage()));
            }
        }
    }

    /*
     * !!!!! Do not modify below this line !!!!!
     */
    public function testConnection()
    {
        $this->initConnection();
        if (!$this->getDestination()->getBackupDestination()) {
            $this->getDestination()->setLastResult($this->getTestResult()->getSuccess())->setLastResultMessage($this->getTestResult()->getMessage())->save();
        }
        return $this->getTestResult();
    }

    public function initConnection()
    {
        $this->setDestination($this->destinationFactory->create()->load($this->getDestination()->getId()));
        $testResult = new \Magento\Framework\DataObject();
        $this->setTestResult($testResult);
        if (!@method_exists($this, $this->getDestination()->getCustomFunction())) {
            $this->getTestResult()->setSuccess(false)->setMessage(__('Custom function/method \'%1\' not found in %2.', $this->getDestination()->getCustomFunction(), __FILE__));
        } else {
            $this->getTestResult()->setSuccess(true)->setMessage(__('Custom function/method found and ready to use.', __FILE__));
        }
        return true;
    }

    public function saveFiles($fileArray)
    {
        if (empty($fileArray)) {
            return [];
        }
        // Init connection
        $this->initConnection();
        // Call custom function
        @$this->{$this->getDestination()->getCustomFunction()}($fileArray);
        return array_keys($fileArray);
    }

    private function deriveConfigData()
    {
        $storeId = $this->_registry->registry('productexport_profile')->getStoreId() ?? null;
        $environment = $this->getConnectionSectionData(self::FIELD_ENVIRONMENT);

        return [
            'endpoint' => $this->getConnectionSectionData(
                self::ENVIRONMENT_DEPENDENT_FIELD_ENDPOINT,
                $environment
            ),
            'apiKey' => $this->getConnectionSectionData(
                self::ENVIRONMENT_DEPENDENT_FIELD_API_KEY,
                $environment
            ),
            'accountId' => $this->scopeConfig->getValue(
                ConfigurationSettingsInterface::SETTINGS_ACC_ID,
                ScopeInterface::SCOPE_STORE,
                $storeId
            ),
            'domainKey' => $this->scopeConfig->getValue(
                ConfigurationSettingsInterface::SETTINGS_DOMAIN_KEY,
                ScopeInterface::SCOPE_STORE,
                $storeId
            )
        ];
    }

    private function getConnectionSectionData($field, $prefix = '')
    {
        $prefix = $prefix ? $prefix . '_' : '';
        $xmlPath = sprintf(
            '%s%s%s',
            self::XML_CONFIG_PATH_FOR_DATA_FEED_CONNECTION_SECTION,
            $prefix,
            $field
        );

        return $this->scopeConfig->getValue($xmlPath);
    }

    private function logResults($dataToLog)
    {
        extract($dataToLog);

        $statusCode = null;

        if (!$response instanceof GuzzleException) {
            $statusCode = $response->getStatusCode();
            $reason = $response->getReasonPhrase();
            $result = $response->getBody()->getContents();
        } else {
            $result = $response->getMessage();
        }

        $isSuccess = $statusCode === 200;

        $logString = 'Status: ' . ($isSuccess ? 'SUCCESS' : 'FAILURE') . PHP_EOL;
        $logString .= $statusCode ? 'Http: ' . $statusCode . ' ' . $reason . PHP_EOL : '';
        $logString .= 'Result: ' . $result . PHP_EOL;
        $logString .= 'Total Time (sec.): ' . round($finish - $start, 6) . PHP_EOL;
        $logString .= 'Data Size: ' . round($size / self::BYTES_IN_MEGABYTE, 3) . 'MB' . PHP_EOL;

        $logEntry = $this->_registry->registry('productexport_log');
        $logEntry->setResult($isSuccess ? Log::RESULT_SUCCESSFUL : Log::RESULT_FAILED);
        $logEntry->addResultMessage(__(
            'Destination "%1" (ID: %2): %3',
            $this->getDestination()->getName(),
            $this->getDestination()->getId(),
            htmlentities($logString)
        ));
    }
}
