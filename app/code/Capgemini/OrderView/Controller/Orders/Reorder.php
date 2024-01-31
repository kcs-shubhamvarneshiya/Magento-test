<?php

namespace Capgemini\OrderView\Controller\Orders;

use Magento\Sales\Controller\OrderInterface;

class Reorder extends \Magento\Sales\Controller\AbstractController\Reorder implements OrderInterface
{
    /*
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('* / * /history');
    }
    */
}
