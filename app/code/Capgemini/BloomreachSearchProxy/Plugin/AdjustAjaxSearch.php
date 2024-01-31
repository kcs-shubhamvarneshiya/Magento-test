<?php

namespace Capgemini\BloomreachSearchProxy\Plugin;

use Amasty\Scroll\Plugin\Ajax\AjaxAbstract;
use Magento\Framework\App\View;
use Magento\Framework\View\Result\Page;

class AdjustAjaxSearch extends AjaxAbstract
{
    public function aroundRenderLayout(
        View $subject,
        \Closure $proceed,
        $output = ''
    ) {
        $page = $subject->getPage();

        if ($page instanceof Page
            && $this->isAjax()
            && $this->request->getRouteName() === 'capgemini_bloomreach_search_proxy'
        ) {
            $this->request->setRouteName('catalogsearch');
        }

        return $proceed();
    }
}
