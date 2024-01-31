<?php

namespace Lyonscg\SalesPad\Plugin\RequisitionList;

use Magento\Customer\Model\Session as CustomerSession;

class IndexControllerPlugin
{
    protected $quotePuller;

    protected $customerSession;

    protected $logger;

    public function __construct(
        \Lyonscg\SalesPad\Model\Sync\Quote\Pull $quotePuller,
        CustomerSession $customerSession,
        \Lyonscg\SalesPad\Model\Api\Logger $logger
    ) {
        $this->quotePuller = $quotePuller;
        $this->customerSession = $customerSession;
        $this->logger = $logger;
    }

    public function beforeExecute(\Magento\RequisitionList\Controller\Requisition\Index $subject)
    {
        if (!$this->customerSession->isLoggedIn()) {
            return null;
        }

        try {
            $this->quotePuller->executeRequisitionListIndex($this->customerSession->getCustomerData());
        } catch (\Throwable $e) {
            $this->logger->debug(
                'Error pulling quotes for customer ' . $this->customerSession->getCustomerId() . ': ' .
                    $e->getMessage() . "\n" . $e->getTraceAsString()
            );
        }
        return null;
    }
}
