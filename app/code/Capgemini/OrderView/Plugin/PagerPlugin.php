<?php

namespace Capgemini\OrderView\Plugin;

use Magento\Theme\Block\Html\Pager;


class PagerPlugin
{
    /**
    * @param Form $subject
    * @param $limit
    * @return array
    */
    public function afterGetLimitUrl(Pager $subject, $result, $limit)
    {   
        $urlParams = [];
        $urlParams['_current'] = false;
        $urlParams['_escape'] = true;
        $urlParams['_use_rewrite'] = true;
        $urlParams['_fragment'] = $subject->getFragment();
        $urlParams['_query'] = $this->getPageLimitParams($subject,$limit);
        $urlParams['_query'][$subject->getPageVarName()] = 1;
        if ($subject->getRequest()->getParam('account_id')){
            $urlParams['account_id'] = $subject->getRequest()->getParam('account_id');
        }
        if ($subject->getRequest()->getParam('search')){
            $urlParams['search'] = $subject->getRequest()->getParam('search');
        }
        return $subject->getUrl('*/*/*',$urlParams);
    }

    public function getPageLimitParams($subject, int $limit): array
    {
        $data = [$subject->getLimitVarName() => $limit];

        $currentPage = $subject->getCurrentPage();
        $availableCount = (int) ceil($subject->getTotalNum() / $limit);
        if ($currentPage !== 1 && $availableCount < $currentPage) {
            $data = array_merge($data, [$subject->getPageVarName() => $availableCount === 1 ? null : $availableCount]);
        }

        return $data;
    }
}




