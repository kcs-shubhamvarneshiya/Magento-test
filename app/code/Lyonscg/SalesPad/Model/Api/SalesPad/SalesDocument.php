<?php

namespace Lyonscg\SalesPad\Model\Api\SalesPad;

use Lyonscg\SalesPad\Model\Api;
use Lyonscg\SalesPad\Model\Api\Logger;

class SalesDocument extends SalesPadAbstract
{
    const TYPE_ORDER   = 'ORDER';
    const TYPE_QUOTE   = 'QUOTE';
    const TYPE_INVOICE = 'INVOICE';

    const ACTION_PAYFABRIC    = 'api/PayFabricTransaction';

    protected $salesLineItem;

    public function __construct(
        Api $api,
        Logger $logger,
        SalesLineItem $salesLineItem
    ) {
        parent::__construct($api, $logger);
        $this->salesLineItem = $salesLineItem;
    }

    public function get($type, $salesDocId)
    {
        $action = sprintf('api/SalesDocument/%s/%s', $type, strval($salesDocId));
        return $this->_call($action, Api::GET);
    }

    public function search($odata)
    {
        $action = 'api/SalesDocumentSearch';
        $result = $this->_call($action, Api::GET, [], $odata);
        if (!$result) {
            return false;
        }
        return $result;
    }

    public function getBySalesDocId($type, $salesDocId, $customerNum = false)
    {
        $filter = sprintf("Sales_Doc_Type eq '%s' and Sales_Doc_Num eq '%s'", $type, strval($salesDocId));
        if ($customerNum !== false) {
            $filter = sprintf("%s and Customer_Num eq '%s'", $filter, strval(trim($customerNum)));
        }
        $orders = $this->search($filter);
        if (is_array($orders) && !empty($orders) && isset($orders['Items']) && !empty($orders['Items'])) {
            return $orders['Items'][0];
        } else {
            return false;
        }
    }

    public function getByPrevSalesDocId($type, $prevSalesDocId, $customerNum = false)
    {
        $filter = sprintf("Sales_Doc_Type eq '%s' and Prev_Sales_Doc_Num eq '%s'", $type, strval($prevSalesDocId));
        if ($customerNum !== false) {
            $filter = sprintf("%s and Customer_Num eq '%s'", $filter, strval(trim($customerNum)));
        }
        $orders = $this->search($filter);
        if (is_array($orders) && !empty($orders) && isset($orders['Items'])) {
            return $orders['Items'];
        } else {
            return false;
        }
    }

    public function getByCustomerNum($type, $customerNum, $limit = 0, $page = 1, $extraFilters = [])
    {
        $extraFiltersInline = "";
        if (!empty($extraFilters)) {
            foreach ($extraFilters as $extraFilter) {
                $extraFiltersInline .= " and " .
                    $extraFilter['field'] . " " .
                    $extraFilter['condition'] . " '" .
                    $extraFilter['value'] . "'";
            }
        }
        $filter = sprintf("Sales_Doc_Type eq '%s' %s and Customer_Num eq '%s'", $type, $extraFiltersInline, strval($customerNum));
        $orderby = 'Created_On desc';
        $odata = [
            'filter' => $filter,
            'orderby' => $orderby,
            'inlinecount' => 'allpages',
        ];
        if ($limit > 0) {
            $odata['top'] = $limit;
        }
        if ($page >= 1 && $limit > 0) {
            $odata['skip'] = ($page-1) * $limit;
        }
        $result = $this->search($odata);
        // return relevant data
        return [
            'page' => $page,
            'page_size' => $limit,
            'items' => $result['Items'] ?? false,
            'count' => $result['Count'] ?? false
        ];
    }

    public function create($data)
    {
        $action = 'api/SalesDocument';
        return $this->_call($action, Api::POST, $data);
    }

    public function createWithLines($data)
    {
        $action = 'api/SalesDocument/WithLines';
        $lineHeaders = [
            'UpdateFlags' => 'SetLineNum'
        ];
        return $this->_call($action, Api::POST, $data, false, $lineHeaders);
    }

    public function update($type, $salesDocId, $data)
    {
        if ($type == self::TYPE_ORDER) {
            throw new \Exception('Update not supported for ORDER type');
        }
        $action = sprintf('api/SalesDocument/%s/%s', $type, strval($salesDocId));
        return $this->_call($action, Api::PUT, $data);
    }

    public function delete($salesDocId, $type)
    {
        if ($type == self::TYPE_ORDER) {
            throw new \Exception('Delete not supported for ORDER type');
        }
        $action = sprintf('api/SalesDocument/%s/%s', $type, strval($salesDocId));
        return $this->_call($action, Api::DELETE);
    }

    public function transfer($salesDocId, $type)
    {
        if ($type != self::TYPE_QUOTE) {
            throw new \Exception('Transfer not supported for type "' . $type . '"');
        }
        $action = sprintf('api/SalesDocument/%s/%s/Transfer', $type, $salesDocId);
        return $this->_call($action, Api::PUT);
    }

    public function deleteAllItems($salesDocId, $type)
    {
        if ($type != self::TYPE_QUOTE) {
            throw new \Exception('DeleteLineItems not supported for type "' . $type . '"');
        }

        $lineItems = $this->salesLineItem->search($type, $salesDocId);
        if (!$lineItems || !is_array($lineItems)) {
            $this->logger->debug('Failed to retrieve line items before delete for ' . $salesDocId);
            return false;
        }

        // no need to delete if no items exist
        if (!isset($lineItems['Items']) || empty($lineItems['Items'])) {
            $this->logger->debug('No line items to delete for ' . $salesDocId);
            return true;
        }

        $itemsToDelete = [];

        foreach ($lineItems['Items'] as $lineItemData) {
            $itemsToDelete[] = [
                'Line_Num' => $lineItemData['Line_Num'],
                'Component_Seq_Num' => $lineItemData['Component_Seq_Num']
            ];
        }

        $items = [
            'LineItems' => $itemsToDelete
        ];

        $action = sprintf('api/SalesDocument/%s/%s/DeleteLineItems', $type, $salesDocId);
        return $this->_call($action, Api::DELETE, $items);
    }

    public function callPayfabric(array $data)
    {
        return $this->_call(self::ACTION_PAYFABRIC, Api::POST, $data);
    }
}
