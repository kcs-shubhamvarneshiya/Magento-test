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
class Creditmemo extends Client
{
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
     * @param $customerid
     * @return array|bool|float|int|mixed|string
     */
    public function getCreditmemos($customerid)
    {
        $action = 'customers/'.$customerid.'/credits';
        $result = $this->send($action);
        if (!$result) {
            return false;
        }

        return $result;
    }

    /**
    * @param $customerid
    * @return array|bool|float|int|mixed|string
    */
    public function getAllCreditmemos($customerIds, $curPage, $pageSize)
    {
        $action = 'customers/credits?accounts='.$customerIds.'&page='.$curPage.'&pageSize='.$pageSize;
        $result = $this->send($action);
        if (!$result) {
            return false;
        }

        return $result;
    }
    /**
    * @param $customerid
    * @return array|bool|float|int|mixed|string
    */
    public function getCreditMemoSummary($customerId)
    {
        $action = 'customers/credits/summary?account='.$customerId;
        $result = $this->send($action);
        if (!$result) {
            return false;
        }

        return $result;
    }

    /**
     * @param $customerId
     * @param $searchTerm
     * @return array|bool|float|int|mixed|string
     */
    public function getCreditMemoSummarySearch($customerId, $searchTerm)
    {
        $searchTermStrings = ($searchTerm != NUll) ? '&searchTerm='. urlencode($searchTerm) : '';
        $action = 'customers/credits/summary/search?account='.$customerId.$searchTermStrings;
        $result = $this->send($action);
        if (!$result) {
            return false;
        }
        return $result;
    }

    /**
     * @param $customerid
     * @return array|bool|float|int|mixed|string
     */
    public function getSearchCreditmemos($customerIds,$params, $curPage, $pageSize)
    {
        /*$action = 'customers/credits/search?account='.$customerIds.'&searchTerm='.$params.'&page='.$curPage.'&pageSize='.$pageSize;
        $result = $this->send($action);*/
        $action = 'customers/credits/search';
        $searchParams = [
            'accounts' =>[$customerIds],
            'searchTerm' => $params,
            'page' => $curPage,
            'pageSize' => $pageSize      
        ];
        $result = $this->post($action,$searchParams);
        if (!$result) {
            return false;
        }

        return $result;
    }

    /**
     * @param $customerid
     * @return array|bool|float|int|mixed|string
     */
    public function getCreditmemo($orderid)
    {
        $action = 'customers/credits/'.$orderid;
        $result = $this->send($action);
        if (!$result) {
            return false;
        }
        return $result;
    }

    public function getSortCreditmemos($customerIds, $curPage, $pageSize, $sortby)
    {
       
        $action = 'customers/credits?sortBy='.$sortby.'&accounts='.$customerIds.'&page='.$curPage.'&pageSize='.$pageSize;
     
       $result = $this->send($action);
    
        if (!$result) {
            return false;
        }

        return $result;
    }
}
