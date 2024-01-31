<?php

namespace Lyonscg\SalesPad\Plugin;

use Magento\Company\Api\CompanyRepositoryInterface;
use Magento\Company\Api\Data\CompanyInterface;
use Magento\Company\Api\Data\CompanyExtensionInterface;
use Magento\Company\Api\Data\CompanyExtensionInterfaceFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;

class CompanyRepositoryPlugin
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    protected $companyExtensionFactory;

    protected $customerRepository;

    public function __construct(
        CompanyExtensionInterfaceFactory $companyExtensionFactory,
        CustomerRepositoryInterface $customerRepository,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->companyExtensionFactory = $companyExtensionFactory;
        $this->customerRepository = $customerRepository;
    }

    public function afterGet(CompanyRepositoryInterface $subject, CompanyInterface $company)
    {
        $extensionAttributes = $company->getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->companyExtensionFactory->create();
        }

        try {
            $admin = $this->customerRepository->getById($company->getSuperUserId());
            // CRC-638 Moved sales_pad_customer_num customer attribute from a custom to an extension one.
            if ($admin->getExtensionAttributes() !== null
                && $admin->getExtensionAttributes()->getSalesPadCustomerNum()) {
                $customerNum = $admin->getExtensionAttributes()->getSalesPadCustomerNum();
                $extensionAttributes->setSalesPadCustomerNum($customerNum);
            }
        } catch (\Exception $e) {
            $this->logger->critical("Sales Pad Customer Number");
            $this->logger->critical($e);
        }
        return $company->setExtensionAttributes($extensionAttributes);
    }
}
