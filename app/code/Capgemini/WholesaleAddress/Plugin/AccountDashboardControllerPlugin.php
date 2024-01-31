<?php

namespace Capgemini\WholesaleAddress\Plugin;

use Magento\Customer\Controller\Account\Index;
use Magento\Framework\View\Result\Page;

/**
 * Plugin to add wholesale handler to account dashboard page
 */
class AccountDashboardControllerPlugin
{
    /**
     * @var \Capgemini\WholesaleAddress\ViewModel\WholesaleDetector
     */
    protected $wholesaleDetector;

    /**
     * @param \Capgemini\WholesaleAddress\ViewModel\WholesaleDetector $wholesaleDetector
     */
    public function __construct(\Capgemini\WholesaleAddress\ViewModel\WholesaleDetector $wholesaleDetector)
    {
        $this->wholesaleDetector = $wholesaleDetector;
    }

    /**
     * Add wholesale handler
     *
     * @param Index $subject
     * @param Page $result
     * @return Page
     */
    public function afterExecute(Index $subject, Page $result): Page
    {
        if ($this->wholesaleDetector->isWholesaleCustomer()) {
            $result->addHandle('customer_account_index_wholesale');
        }
        return $result;
    }
}
