<?php

namespace Lyonscg\SalesPad\Model\Api\SalesPad;

use Lyonscg\SalesPad\Model\Api;
use Lyonscg\SalesPad\Model\Api\Logger;

abstract class SalesPadAbstract
{
    /**
     * Prevent failure data from stacking up too much
     */
    const FAILURE_MAX = 20;

    /**
     * @var Api
     */
    protected $api;

    /**
     * @var Logger
     */
    protected $logger;

    protected $failures = [];

    /**
     * SalesPadAbstract constructor.
     * @param Api $api
     * @param Logger $logger
     */
    public function __construct(
        Api $api,
        Logger $logger
    ) {
        $this->api = $api;
        $this->logger = $logger;
    }

    protected function _call($action, $method, $data = [], $odata = false, $extraHeaders = false)
    {
        if ($odata !== false) {
            if (!is_array($odata)) {
                $odata = ['filter' => $odata];
            }
            //$query = '?$' . http_build_query(['filter' => $filter]);
            $query = '?$' . $this->_buildOdata($odata);
            $action .= $query;
        }
        try {
            if (is_array($extraHeaders) && !empty($extraHeaders)) {
                $response = $this->api->callApiWithAdditionalHeaders($action, $method, $extraHeaders, $data);
            } else {
                $response = $this->api->callApi($action, $method, $data);
            }
            if (!$response) {
                $this->logger->debug("Call to $action failed to return response object");
                return false;
            }
            $responseCode = $response->getStatusCode();
            if ($responseCode == 200 || $responseCode == 201 || ($responseCode == 409 && $action == SalesDocument::ACTION_PAYFABRIC)) {
                return $this->api->extractJson($response);
            } else {
                $this->logger->debug(
                    "API call $action returned status code $responseCode"
                );
                $this->failures[] = $responseCode . ': ' . $response->getBody();
                // prevent failure data from stacking up too much if there is a long running process that is doing lots of api calls
                if (count($this->failures) > self::FAILURE_MAX) {
                    $this->logger->debug('Too many failures: ' . count($this->failures) . ', cleaning up:');
                    $this->logger->debug($this->failures);
                    // make sure to note that the failures were automatically cleared
                    $this->failures = ['Check application log files for more information'];
                }
            }
        } catch (\Exception $e) {
            return false;
        }
        return false;
    }

    protected function _buildOdata(array $odata)
    {
        $parts = [];
        foreach ($odata as $key => $value) {
            $parts[] = $key . '=' . urlencode($value);
        }
        return implode('&$', $parts);
    }

    /**
     * @return array
     */
    public function getFailures()
    {
        return $this->failures;
    }

    /**
     * Clear failures
     * @return $this
     */
    public function clearFailures()
    {
        $this->failures = [];
        return $this;
    }
}
