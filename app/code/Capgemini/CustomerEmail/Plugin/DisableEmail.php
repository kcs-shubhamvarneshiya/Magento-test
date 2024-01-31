<?php

namespace Capgemini\CustomerEmail\Plugin;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\EmailNotification;
use Magento\Customer\Model\EmailNotificationInterface;

class DisableEmail
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    public function __construct(\Magento\Framework\App\Request\Http $request)
    {
        $this->request = $request;
    }

    public function aroundNewAccount(
        EmailNotification $subject,
        callable $proceed,
        CustomerInterface $customer,
        $type = EmailNotificationInterface::NEW_ACCOUNT_EMAIL_REGISTERED,
        $backUrl = '',
        $storeId = null,
        $sendemailStoreId = null
    ) {
        $companyData = $this->request->getPost('company', []);

        if (!is_array($companyData) || empty($companyData)) {
            $proceed($customer, $type, $backUrl, $storeId, $sendemailStoreId);
        }
    }
}
