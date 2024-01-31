<?php

namespace Capgemini\PartnersInsight\Model\Api;

use Capgemini\PartnersInsight\Model\Config;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\UrlInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;

/**
 * Rest API client
 */
class Payment extends Client
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
     * @param $accountId
     * @param $pageSize
     * @param $currentPage
     * @param $apiFilters
     * @return array|bool|float|int|mixed|string
     */
    public function getPaymentList($accountId, $curPage, $pageSize, $apiFilters = null)
    {
        $filters = (!empty($apiFilters))?'&'.http_build_query($apiFilters):'';
        $action = 'customers/payments?accountId='.$accountId.$filters."&page=".$curPage."&pageSize=".$pageSize;
        $result = $this->send($action);
        if (!$result) {
            return false;
        }
        return $result;

    }

    /**
     * @param $accountId
     * @param $searchTerm
     * @param $pageSize
     * @param $currentPage
     * @param $apiFilters
     * @return array|bool|float|int|mixed|string
     */
    public function getSearchPayment($accountId, $searchTerm, $curPage, $pageSize , $apiFilters =null)
    {
        $filters = (!empty($apiFilters))?'&'.http_build_query($apiFilters):'';
        $action = 'customers/payments/search?accountId='.$accountId."&searchTerm=".urlencode($searchTerm).$filters."&page=".$curPage."&pageSize=".$pageSize;
        $result = $this->send($action);
        if (!$result) {
            return false;
        }
        return $result;

    }
    public function getSortPayment($accountId,$curPage, $pageSize, $sortby)
    {
      
        $action = 'customers/payments?accountId='.$accountId."&page=".$curPage."&pageSize=".$pageSize."&sortBy=".$sortby;
        $result = $this->send($action);
        if (!$result) {
            return false;
        }
        return $result;

    }
}
