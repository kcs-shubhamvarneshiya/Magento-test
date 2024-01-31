<?php
/**
 * Capgemini_Payfabric
 *
 * @category   Capgemini
 * @author    Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2020 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\Payfabric\Model;

class Curl extends \Magento\Framework\HTTP\Adapter\Curl
{

    protected function _applyConfig()
    {
        //curl_setopt_array($this->_getResource(), $this->_options);

        if (empty($this->_config)) {
            return $this;
        }

        $verifyPeer = isset($this->_config['verifypeer']) ? $this->_config['verifypeer'] : 0;
        curl_setopt($this->_getResource(), CURLOPT_SSL_VERIFYPEER, $verifyPeer);

        $verifyHost = isset($this->_config['verifyhost']) ? $this->_config['verifyhost'] : 0;
        curl_setopt($this->_getResource(), CURLOPT_SSL_VERIFYHOST, $verifyHost);

        foreach ($this->_config as $param => $curlOption) {
            if (array_key_exists($param, $this->_allowedParams)) {
                curl_setopt($this->_getResource(), $this->_allowedParams[$param], $this->_config[$param]);
            }
        }
        return $this;
    }
    public function write($method, $url, $http_ver = '1.1', $headers = [], $body = '')
    {
        if ($url instanceof \Laminas\Uri\Uri) {
            $url = $url->toString();
        }
        $this->_applyConfig();

        $header = isset($this->_config['header']) ? $this->_config['header'] : true;
        $options = array(
            CURLOPT_URL                     => $url,
            CURLOPT_RETURNTRANSFER          => true,
            CURLOPT_HEADER                  => $header
        );
        if ($method == \Laminas\Http\Request::METHOD_POST) {
            $options[CURLOPT_POST]          = true;
            $options[CURLOPT_POSTFIELDS]    = $body;
        } elseif ($method == \Laminas\Http\Request::METHOD_GET) {
            $options[CURLOPT_HTTPGET]       = true;
        }
        if (is_array($headers)) {
            $options[CURLOPT_HTTPHEADER]    = $headers;
        }

        curl_setopt_array($this->_getResource(), $options);

        return $body;
    }

    /**
     * Read response from server
     *
     * @return string
     */
    public function read()
    {
        $response = curl_exec($this->_getResource());

        // Remove 100 and 101 responses headers
        while (\Laminas\Http\Response::fromString($response)->getStatusCode() == 100
            || \Laminas\Http\Response::fromString($response)->getStatusCode() == 101) {
            $response = preg_split('/^\r?$/m', $response, 2);
            $response = trim($response[1]);
        }

        if (stripos($response, "Transfer-Encoding: chunked\r\n") === true) {
            $response = str_ireplace("Transfer-Encoding: chunked\r\n", '', $response);
        }
        $messageBody = \Laminas\Http\Response::fromString($response)->getBody();

        return $messageBody;
    }


    public function close()
    {
        curl_close($this->_getResource());
        $this->_resource = null;
        return $this;
    }

    /**
     * Returns a cURL handle on success
     *
     * @return resource
     */
    protected function _getResource()
    {
        if (is_null($this->_resource)) {
            $this->_resource = curl_init();
        }
        return $this->_resource;
    }

    /**
     * Get last error number
     *
     * @return int
     */
    public function getErrno()
    {
        return curl_errno($this->_getResource());
    }

    /**
     * Get string with last error for the current session
     *
     * @return string
     */
    public function getError()
    {
        return curl_error($this->_getResource());
    }

    /**
     * Get information regarding a specific transfer
     *
     * @param int $opt CURLINFO option
     * @return mixed
     */
    public function getInfo($opt = 0)
    {
        if (!$opt) {
            return curl_getinfo($this->_getResource());
        }

        return curl_getinfo($this->_getResource(), $opt);
    }
}
