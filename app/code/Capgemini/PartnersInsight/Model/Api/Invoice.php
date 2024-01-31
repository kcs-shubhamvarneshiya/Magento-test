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
class Invoice extends Client
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
    public function getInvoices($customerid)
    {
        $action = 'customers/'.$customerid.'/invoices';
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
    public function getAllInvoices($customerIds, $apiFilters, $curPage, $pageSize)
    {
        $filters = (!empty($apiFilters))?'&'.http_build_query($apiFilters):'';
        $action = 'customers/invoices?accounts='.$customerIds.$filters."&page=".$curPage."&pageSize=".$pageSize;
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
    public function getInvoiceSummary($customerIds)
    {
        $action = 'customers/invoices/summary?account='.$customerIds;
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
    public function getInvoiceSummarySearch($customerId, $searchTerm)
    {
        $searchTermStrings = ($searchTerm != NUll) ? '&searchTerm='. urlencode($searchTerm) : '';
        $action = 'customers/invoices/summary/search?account='.$customerId.$searchTermStrings;
        $result = $this->send($action);
        if (!$result) {
            return false;
        }
        return $result;
    }

    /**
     * @param $customerIds
     * @param $params
     * @param $curPage
     * @param $pageSize
     * @return array|bool|float|int|mixed|string
     */
    public function getSearchInvoices($customerIds,$params, $curPage, $pageSize)
    {
        $action = 'customers/invoices/search?account='.$customerIds.'&searchTerm='.urlencode($params)."&page=".$curPage."&pageSize=".$pageSize;
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
    public function getInvoice($orderid)
    {
        $action = 'customers/invoices/'.$orderid;
        $result = $this->send($action);
        if (!$result) {
            return false;
        }
        return $result;
    }
    
    public function getInvoiceSort($customerId, $status, $curPage, $pageSize, $sortby){

        $action = 'customers/invoices?accounts='.$customerId.'&status='.$status.'&page='.$curPage.'&pageSize='.$pageSize.'&sortBy='.$sortby;
       
        $result = $this->send($action);
       
        if (!$result) {
            return false;
        }

        return $result;
    }
}
