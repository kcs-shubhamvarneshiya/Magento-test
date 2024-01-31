<?php

namespace Lyonscg\OrderSearch\Plugin\Block\Widget\Guest;

class FormPlugin
{
    /**
     * Replace form url with custom controller
     * @param \Magento\Sales\Block\Widget\Guest\Form $subject
     * @param $result
     * @return mixed
     */
    public function afterGetActionUrl(\Magento\Sales\Block\Widget\Guest\Form $subject, $result)
    {
        return $subject->getUrl('ordersearch/guest/view');
    }
}
