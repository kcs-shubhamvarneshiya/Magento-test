<?php

namespace Lyonscg\SalesPad\Helper;

use Lyonscg\SalesPad\Model\Api\Logger;
use Lyonscg\SalesPad\Api\CustomerLinkRepositoryInterface;
use Magento\Company\Api\CompanyRepositoryInterface;
use Magento\Company\Api\Data\CompanyInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Customer\Model\ResourceModel\Customer as CustomerResource;
use Magento\Framework\Exception\NoSuchEntityException;

class Customer extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var CustomerRegistry
     */
    protected $customerRegistry;

    /**
     * @var CustomerResource
     */
    protected $customerResourceModel;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var CompanyRepositoryInterface
     */
    protected $companyRepository;

    /**
     * @var CustomerLinkRepositoryInterface
     */
    protected $customerLinkRepository;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * Customer constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param CustomerRegistry $customerRegistry
     * @param CustomerResource $customerResourceModel
     * @param CustomerRepositoryInterface $customerRepository
     * @param CompanyRepositoryInterface $companyRepository
     * @param CustomerLinkRepositoryInterface $customerLinkRepository
     * @param Logger $logger
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        CustomerRegistry $customerRegistry,
        CustomerResource $customerResourceModel,
        CustomerRepositoryInterface $customerRepository,
        CompanyRepositoryInterface $companyRepository,
        CustomerLinkRepositoryInterface $customerLinkRepository,
        Logger $logger
    ) {
        parent::__construct($context);
        $this->customerRegistry = $customerRegistry;
        $this->customerResourceModel = $customerResourceModel;
        $this->customerRepository = $customerRepository;
        $this->companyRepository = $companyRepository;
        $this->customerLinkRepository = $customerLinkRepository;
        $this->logger = $logger;
    }

    /**
     * @param CustomerInterface $customer
     * @param string $customerNum
     * @return bool|string
     */
    public function saveSalesPadCustomerNumber(CustomerInterface $customer, $customerNum)
    {
        try {
            // CRC-638 Moved sales_pad_customer_num customer attribute from a custom to an extension one.
            // set attribute directly to prevent sync entries from being created
            $extensionAttributes = $customer->getExtensionAttributes();
            $extensionAttributes->setSalesPadCustomerNum($customerNum);
            $customer->setExtensionAttributes($extensionAttributes);
        } catch (\Exception $e) {
            $this->logger->debug($e);
            return false;
        }
        return $customerNum;
    }

    public function getCustomerModel(CustomerInterface $customer)
    {
        try {
            return $this->customerRegistry->retrieve($customer->getId());
        } catch (\Exception $e) {
            $this->logger->debug($e);
            return false;
        }
    }

    public function getCompany(CustomerInterface $customer)
    {
        $extAttrs = $customer->getExtensionAttributes();
        if (!$extAttrs) {
            return null;
        }
        $companyAttributes = $extAttrs->getCompanyAttributes();
        if (!$companyAttributes) {
            return null;
        }
        $companyId = $companyAttributes->getCompanyId();

        try {
            $company = $this->companyRepository->get($companyId);
        } catch (NoSuchEntityException $e) {
            $company = null;
        }

        return $company;
    }

    public function getCustomerNum($customerId)
    {
        try {
            $customer = $this->customerRepository->getbyId($customerId);
            // CRC-638 Moved sales_pad_customer_num customer attribute from a custom to an extension one.
            if ($salesPadCustomerNum = $customer->getExtensionAttributes()->getSalesPadCustomerNum()) {

                return $salesPadCustomerNum;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getCompanyCustomerNumForCustomer(CustomerInterface $customer)
    {
        $company = $this->getCompany($customer);
        if ($company && $company->getId()) {
            try {
                $admin = $this->customerRepository->getById($company->getSuperUserId());
                // CRC-638 Moved sales_pad_customer_num customer attribute from a custom to an extension one.
                if ($customerNum = $admin->getExtensionAttributes()->getSalesPadCustomerNum()) {

                    return $customerNum;
                }
            } catch (\Exception $e) {
            }
        }
        return false;
    }
}
