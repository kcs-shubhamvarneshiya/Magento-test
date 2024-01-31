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
class Order extends Client
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
     * @param $customerids
     * @param $currentPage
     * @param $orderSize
     * @param $apiFilters
     * @return array|bool|float|int|mixed|string
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
    public function getOrders($customerid)
    {
        $action = 'customers/'.$customerid.'/orders';
        $result = $this->send($action);
        if (!$result) {
            return false;
        }

        return $result;
    }

    /**
    * @param $customerids
    * @param $apiFilters
    * @return array|bool|float|int|mixed|string
    */
    public function getAllOrders($customerIds, $apiFilters, $curPage, $pageSize)
    {
        $filters = (!empty($apiFilters))?'&'.http_build_query($apiFilters):'';
        $action = 'customers/orders?accounts='.$customerIds.$filters."&page=".$curPage."&pageSize=".$pageSize;
        $result = $this->send($action);
        if (!$result) {
            return false;
        }
        return $result;
    }

    /**
     * @param $customerId
     * @return array|bool|float|int|mixed|string
     */
    public function getOrderSummary($customerId)
    {
        $action = 'customers/orders/summary?account='.$customerId;
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
    public function getOrderSummarySearch($customerId, $searchTerm)
    {
        $searchTermString = ($searchTerm != NUll) ? '&searchTerm='. urlencode($searchTerm) : '';
        $action = 'customers/orders/summary/search?account='.$customerId.$searchTermString;
        $result = $this->send($action);
        if (!$result) {
            return false;
        }
        return $result;
    }

    public function getCurrentCustomerAccounts($customerIds,$salesRepAccounts=null,$subSalesRepAccounts=null){
        $action = 'accounts/authorized?accounts='.$customerIds.'&salesRepAccounts='.$salesRepAccounts.'&subSalesRepAccounts='.$subSalesRepAccounts;
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
    public function getSearchOrders($customerIds,$params, $curPage, $pageSize)
    {
        $action = 'customers/orders/search';
        $searchParams = [
            'accounts' =>[$customerIds],
            'searchTerm' => $params,
            'page' => $curPage,
            'pageSize' => $pageSize
        ];

        $result = $this->post($action, $searchParams);
        if (!$result) {
            return false;
        }

        return $result;
    }

    /**
     * @param $customerid
     * @return array|bool|float|int|mixed|string
     */
    public function getOrder($orderid)
    {
        $action = 'orders/'.$orderid;
        $result = $this->send($action);
        if (!$result) {
            return false;
        }
        return $result;
    }
    public function getSortOrders($customerIds, $status, $curPage, $pageSize, $sortby)
    {
        $action = 'customers/orders?sortBy='.$sortby.'&orderStatus='.$status.'&accounts='.$customerIds.'&page='.$curPage.'&pageSize='.$pageSize;
        
        $result = $this->send($action);
        if (!$result) {
            return false;
        }

        return $result;
    }
}
