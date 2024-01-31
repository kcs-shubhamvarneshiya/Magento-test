<?php

namespace Capgemini\OrderView\Plugin;

class FilterLinks
{
    public function afterGetHref(\Magento\Customer\Block\Account\SortLink $subject, $result)
    {
        $filterPaths = ["orderview/orders/history","orderview/invoices/history","orderview/creditmemos/history","customer/account","orderview/payments/history"];
        if($accountId = $subject->getRequest()->getParam('account_id')){
            $path = $subject->getPath();
            if(in_array($path, $filterPaths))
            {
                return $subject->getUrl($path,['account_id'=>$accountId]);
            }
        }
        return $result;
    }
}
