<?php

namespace Lyonscg\SalesPad\Api;

use Exception;
use Lyonscg\SalesPad\Api\Data\Api\ErrorLogInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\NoSuchEntityException;

interface ErrorLogRepositoryInterface
{
    /**
     * @param $logId
     * @return ErrorLogInterface
     * @throws NoSuchEntityException
     */
    public function getById($logId);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param $url
     * @param $request
     * @param $response
     * @param $responseCode
     * @return ErrorLogInterface
     */
    public function create($url, $request, $response, $responseCode);

    /**
     * @param $url
     * @param $request
     * @param Exception $e
     * @return ErrorLogInterface
     */
    public function createFromException($url, $request, Exception $e);

    /**
     * @param $maxAge - age in days
     * @return int
     */
    public function deleteOldEntries($maxAge);

    /**
     * @param $url
     * @param $request
     * @return void
     */
    public function deleteOnSuccess($url, $request);

    /**
     * @throws NoSuchEntityException
     * @throws Exception
     */
    public function deleteById(int $id);
}
