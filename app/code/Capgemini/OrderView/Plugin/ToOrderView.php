<?php

namespace Capgemini\OrderView\Plugin;

use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\View\Result\Page;
use Magento\Sales\Controller\Guest\Form;

class ToOrderView
{
    /**
     * @param Form $subject
     * @param Redirect|Page $result
     * @return Redirect|Page
     */
    public function afterExecute(Form $subject, $result)
    {
        if ($result instanceof Redirect) {
            $result->setPath( 'orderview/orders/history');
        }

        return $result;
    }
}
