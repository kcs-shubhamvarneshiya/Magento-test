<?php

namespace Lyonscg\Catalog\Plugin\App\Action;

use Magento\Company\Api\CompanyManagementInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Http\Context as HttpContext;

class ContextPlugin
{
    public const IS_TRADE_CUSTOMER_CONTEXT = 'is_trade_customer';

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var HttpContext
     */
    private $httpContext;

    /**
     * @var CompanyManagementInterface
     */
    private $companyManagement;

    /**
     * @param Session $customerSession
     * @param HttpContext $httpContext
     * @param CompanyManagementInterface $companyManagement
     */
    public function __construct(
        Session $customerSession,
        HttpContext $httpContext,
        CompanyManagementInterface $companyManagement
    ) {
        $this->customerSession = $customerSession;
        $this->httpContext = $httpContext;
        $this->companyManagement = $companyManagement;
    }

    /**
     * Add context about whether current customer is a trade customer
     *
     * @param ActionInterface $subject
     */
    public function beforeExecute(ActionInterface $subject)
    {
        $isTrade = $this->customerSession->isLoggedIn()
            && $this->companyManagement->getByCustomerId($this->customerSession->getCustomerId());

        $this->httpContext->setValue(self::IS_TRADE_CUSTOMER_CONTEXT, $isTrade, false);
    }
}
