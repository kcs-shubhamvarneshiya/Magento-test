<?php

namespace Capgemini\Company\Plugin\Customer;

use Capgemini\Company\Helper\CustomerAddress;
use Lyonscg\SalesPad\Helper\SyncStoreManager;
use Magento\Company\Model\CompanySuperUserGet;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\LocalizedException;

class AddAddressPlugin
{
    /**
     * @var CustomerAddress
     */
    private $addressHelper;
    /**
     * @var SyncStoreManager
     */
    private $syncStoreManager;

    public function __construct(CustomerAddress $addressHelper, SyncStoreManager $syncStoreManager)
    {
        $this->addressHelper = $addressHelper;
        $this->syncStoreManager = $syncStoreManager;
    }

    /**
     * @param CompanySuperUserGet $subject
     * @param CustomerInterface $result
     * @return CustomerInterface
     * @throws LocalizedException
     */
    public function afterGetUserForCompanyAdmin(CompanySuperUserGet $subject, CustomerInterface $result): CustomerInterface
    {
        if (!$result->getExtensionAttributes()->getCompanyAttributes()->getCompanyId()) {
            $this->syncStoreManager->setResolvedStoreId($result->getStoreId());

            $address = $this->addressHelper->createCustomerAddress(
                $result,
                'address',
                'general'
            );

            if (!$this->addressHelper->syncCustomerAddress($result, $address)) {
                $this->addressHelper->logDebug(sprintf($this->addressHelper::SYNC_ERROR_PATTERN, $result->getemail()));
            }

            $this->syncStoreManager->setResolvedStoreId();
        }

        return $result;
    }
}
