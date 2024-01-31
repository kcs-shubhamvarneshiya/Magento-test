<?php

namespace Lyonscg\SalesPad\Model\Api\SalesPad;

use Lyonscg\SalesPad\Model\Api;

class SalesLineItem extends SalesPadAbstract
{
    const TYPE_ORDER   = 'ORDER';
    const TYPE_QUOTE   = 'QUOTE';
    const TYPE_INVOICE = 'INVOICE';

    public function get($type, $salesDocId)
    {
        $filter = sprintf(
            "Sales_Doc_Type eq '%s' and Sales_Doc_Num eq '%s'",
            $type,
            trim(strval($salesDocId))
        );
        return $this->_call('api/SalesLineItem', Api::GET, [], $filter);
    }

    public function search($type, $salesDocId)
    {
        $filter = sprintf(
            "Sales_Doc_Type eq '%s' and Sales_Doc_Num eq '%s'",
            $type,
            trim(strval($salesDocId))
        );
        return $this->_call('api/SalesLineItemSearch', Api::GET, [], $filter);
    }

    public function getByPrevSalesDocId($type, $prevSalesDocId)
    {
        $filter = sprintf(
            "Sales_Doc_Type eq '%s' and Prev_Sales_Doc_Num eq '%s'",
            $type,
            trim(strval($prevSalesDocId))
        );
        return $this->_call('api/SalesLineItemSearch', Api::GET, [], $filter);
    }

    public function create($data)
    {
        return $this->_call('api/SalesLineItem', Api::POST, $data);
    }

    public function update($type, $data)
    {
        $salesDocId = $data['Sales_Doc_Num'] ?? false;
        $lineNum = $data['Line_Num'] ?? false;
        $componentSeqNum = $data['Component_Seq_Num'] ?? false;
        $action = sprintf(
            'api/SalesLineItem/%s/%s/%s/%s',
            $type,
            trim(strval($salesDocId)),
            trim(strval($lineNum)),
            trim(strval($componentSeqNum))
        );
        return $this->_call($action, API::PUT, $data);
    }

    public function delete($type, $salesDocId, $lineNum, $componentSeqNum)
    {
        if ($type == self::TYPE_ORDER) {
            throw new \Exception('Delete not supported for ORDER item type');
        }

        $action = sprintf(
            'api/SalesLineItem/%s/%s/%s/%s',
            $type,
            trim(strval($salesDocId)),
            trim(strval($lineNum)),
            trim(strval($componentSeqNum))
        );
        return $this->_call($action, API::DELETE);
    }
}
