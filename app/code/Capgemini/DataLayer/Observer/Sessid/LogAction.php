<?php

namespace Capgemini\DataLayer\Observer\Sessid;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class LogAction implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        ObserveSessid::setActionStatus(static::ACTION);
    }
}
