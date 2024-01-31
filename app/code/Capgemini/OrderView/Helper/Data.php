<?php

namespace Capgemini\OrderView\Helper;

use Capgemini\CompanyType\Model\Config;
use Lyonscg\SalesPad\Helper\Customer as CustomerHelper;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var CustomerHelper
     */
    protected $customerHelper;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    protected Config $companyTypeConfig;

    /**
     * Data constructor.
     * @param Context $context
     * @param CustomerHelper $customerHelper
     * @param CustomerSession $customerSession
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        Context $context,
        CustomerHelper $customerHelper,
        CustomerSession $customerSession,
        CustomerRepositoryInterface $customerRepository,
        Config  $companyTypeConfig
    ) {
        parent::__construct($context);
        $this->customerHelper = $customerHelper;
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->companyTypeConfig = $companyTypeConfig;
    }

    public function getCustomerNumber()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return false;
        }
        return $this->customerHelper->getCustomerNum($this->customerSession->getCustomerId());
    }

    public function getCustomer()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return false;
        }

        return $this->customerRepository->getbyId($this->customerSession->getCustomerId());
    }

    public function getCustomerType()
    {
        // Getting customer type for determining which API to call.
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($this->getCustomer());
        return $customerType;
    }
}
