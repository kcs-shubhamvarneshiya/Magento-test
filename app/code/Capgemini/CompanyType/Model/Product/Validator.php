<?php
/**
 * Capgemini_CompanyType
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\CompanyType\Model\Product;

use Capgemini\CompanyType\Model\Config;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\NoSuchEntityException;

class Validator
{
    /**
     * @var CustomerSession
     */
    protected CustomerSession $customerSession;

    /**
     * @var PurchasePermission
     */
    protected PurchasePermission $purchasePermission;

    /**
     * @var Config
     */
    protected Config $companyTypeConfig;

    /**
     * @param CustomerSession $customerSession
     * @param PurchasePermission $purchasePermission
     * @param Config $companyTypeConfig
     */
    public function __construct(
        CustomerSession $customerSession,
        PurchasePermission $purchasePermission,
        Config $companyTypeConfig
    ) {
        $this->customerSession = $customerSession;
        $this->purchasePermission = $purchasePermission;
        $this->companyTypeConfig = $companyTypeConfig;
    }

    /**
     * @param $productId
     * @return bool
     * @throws NoSuchEntityException
     */
    public function validate($productId): bool
    {
        $customer = $this->customerSession->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);

        if ($customerType == Config::WHOLESALE) {
            return $this->purchasePermission->validateProductById(
                $productId,
                $this->customerSession->getCustomer()
            );
        }

        return true;
    }
}
