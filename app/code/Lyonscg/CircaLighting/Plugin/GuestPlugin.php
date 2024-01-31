<?php

namespace Lyonscg\CircaLighting\Plugin;

/*
 * Plugin changes breadcrumb on {url}/sales/guest/form
 * */

class GuestPlugin
{
    /**
     * Get Breadcrumbs for current controller action
     *
     * @param \Magento\Framework\View\Result\Page $resultPage
     * @return void
     */
    public function aroundGetBreadcrumbs(\Magento\Sales\Helper\Guest $subject, \Closure $proceed, \Magento\Framework\View\Result\Page $resultPage)
    {
        $proceed($resultPage);

        $breadcrumbs = $resultPage->getLayout()->getBlock('breadcrumbs');
        if (!$breadcrumbs) {
            return;
        }

        $breadcrumbs->addCrumb(
            'cms_page',
            ['label' => __('Order Status'), 'title' => __('Order Status')]
        );
    }
}
