<?php

namespace Lyonscg\SalesPad\Plugin;

use Capgemini\Company\Plugin\Customer\UpdateSalesPadNum;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Company\Api\Data\CompanyInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class CustomerRepositoryPlugin
{
    /**
     * @var \Lyonscg\SalesPad\Helper\Customer
     */
    protected $customerHelper;

    /**
     * @var \Lyonscg\SalesPad\Api\CustomerLinkRepositoryInterface
     */
    protected $customerLinkRepository;

    /**
     * @var \Magento\Company\Model\ResourceModel\Customer
     */
    protected $customerResource;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var string
     */
    private $customerNumTransportValue;

    /**
     * @param \Lyonscg\SalesPad\Helper\Customer $customerHelper
     * @param \Lyonscg\SalesPad\Api\CustomerLinkRepositoryInterface $customerLinkRepository
     * @param \Magento\Company\Model\ResourceModel\Customer $customerResource
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        \Lyonscg\SalesPad\Helper\Customer $customerHelper,
        \Lyonscg\SalesPad\Api\CustomerLinkRepositoryInterface $customerLinkRepository,
        \Magento\Company\Model\ResourceModel\Customer $customerResource,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerHelper = $customerHelper;
        $this->customerLinkRepository = $customerLinkRepository;
        $this->customerResource = $customerResource;
        $this->customerRepository = $customerRepository;
    }

    public function afterGet(CustomerRepositoryInterface $subject, CustomerInterface $result)
    {
        return $this->extendWithSalesPadCustomerNum($result);
    }

    public function afterGetById(CustomerRepositoryInterface $subject, CustomerInterface $result)
    {
        return $this->extendWithSalesPadCustomerNum($result);
    }

    /**
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $customer
     * @param $passwordHash
     * @return array
     */
    public function beforeSave(CustomerRepositoryInterface $subject, CustomerInterface $customer, $passwordHash = null)
    {
        $company = $this->customerHelper->getCompany($customer);
        if ($company !== null) {
            $this->_updateCustomerNum($customer, $company);
        }
        $this->customerNumTransportValue = UpdateSalesPadNum::getSpCustomerNum() ?? $customer->getExtensionAttributes()->getSalesPadCustomerNum();
        if ($this->customerNumTransportValue !== null) {
            $customer->getExtensionAttributes()->setSalesPadCustomerNum(null);
        }
        return [$customer, $passwordHash];
    }

    /**
     * @param CustomerInterface $customer
     * @param CompanyInterface $company
     * @return void
     */
    protected function _updateCustomerNum(CustomerInterface $customer, CompanyInterface $company)
    {
        $customerNum = $company->getExtensionAttributes()->getSalesPadCustomerNum();
        if ($customer->getId() == $company->getSuperUserId()) {
            // we are saving the admin customer, so we can update the customer number
            return;
        }
        if ($customerNum) {
            // CRC-638 Moved sales_pad_customer_num customer attribute from a custom to an extension one.
            if ($customer->getExtensionAttributes()->getSalesPadCustomerNum() === $customerNum) {
                // customer num matches company already
                return;
            }
            if ($this->customerHelper->saveSalesPadCustomerNumber($customer, $customerNum)) {
                $this->customerLinkRepository->add(
                    $customer->getId(),
                    $customer->getEmail(),
                    $customer->getWebsiteId(),
                    $customerNum
                );
            }
        }
    }

    /**
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $savedCustomer
     * @return CustomerInterface
     * @throws \Exception
     */
    public function afterSave(CustomerRepositoryInterface $subject, CustomerInterface $savedCustomer)
    {
        if ($this->customerNumTransportValue !== null) {
            $this->customerLinkRepository->add(
                $savedCustomer->getId(),
                $savedCustomer->getEmail(),
                $savedCustomer->getWebsiteId(),
                $this->customerNumTransportValue
            );
        }

        $this->extendWithSalesPadCustomerNum($savedCustomer, $this->customerNumTransportValue);
        $company = $this->customerHelper->getCompany($savedCustomer);

        if ($company !== null) {
            $this->_updateCompanyCustomers($savedCustomer, $company);
        }

        $this->customerNumTransportValue = null;
        return $savedCustomer;
    }

    /**
     * @param CustomerInterface $customer
     * @param CompanyInterface $company
     * @return void
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _updateCompanyCustomers(CustomerInterface $customer, CompanyInterface $company)
    {
        // CRC-638 Moved sales_pad_customer_num customer attribute from a custom to an extension one.
        $customerNum = $customer->getExtensionAttributes()->getSalesPadCustomerNum() ?? false;
        if (!$customerNum) {
            // no customer number, don't update anything
            return;
        }
        $customerIds = $this->customerResource->getCustomerIdsByCompanyId($company->getId());
        foreach ($customerIds as $customerId) {
            if ($customerId == $customer->getId()) {
                // don't need to modify the admin customer
                continue;
            }
            try {
                // update with helper, will not trigger these observers since it updates the attribute directly
                $companyCustomer = $this->customerRepository->getById($customerId);
                if ($this->customerHelper->saveSalesPadCustomerNumber($companyCustomer, $customerNum)) {
                    $this->customerLinkRepository->add(
                        $companyCustomer->getId(),
                        $companyCustomer->getEmail(),
                        $companyCustomer->getWebsiteId(),
                        $customerNum
                    );
                }
            } catch (NoSuchEntityException $e) {
                // customer doesn't exist, kinda weird but no problem here
                continue;
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }

    private function extendWithSalesPadCustomerNum(CustomerInterface $customer, string $customerNum = null)
    {
        $extensionAttributes = $customer->getExtensionAttributes();
        $customerNum = $customerNum ?? $this->customerLinkRepository->get($customer->getId(), null, null);
        $extensionAttributes->setSalesPadCustomerNum($customerNum);
        $customer->setExtensionAttributes($extensionAttributes);

        return $customer;
    }
}
