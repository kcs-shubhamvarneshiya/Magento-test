<?php
namespace Capgemini\Company\Helper;

use Lyonscg\SalesPad\Helper\Customer;
use Lyonscg\SalesPad\Model\Api\CustomerAddr;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\RegionInterfaceFactory;
use Magento\Customer\Model\Metadata\FormFactory;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Customer\Api\Data\RegionInterface;

class CustomerAddress extends AbstractHelper
{
    const SYNC_ERROR_PATTERN = 'There was a problem syncing the address for customer %s';
    /**
     * @var DataObjectHelper
     */
    private $objectHelper;
    /**
     * @var FormFactory
     */
    private $formFactory;
    /**
     * @var RegionFactory
     */
    private $regionFactory;
    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepository;
    /**
     * @var AddressInterfaceFactory
     */
    private $addressDataFactory;
    /**
     * @var RegionInterfaceFactory
     */
    private $regionDataFactory;
    /**
     * @var Customer
     */
    private $customerHelper;
    /**
     * @var CustomerAddr
     */
    private $customerAddrApi;

    /**
     * @param Context $context
     * @param DataObjectHelper $objectHelper
     * @param FormFactory $formFactory
     * @param RegionFactory $regionFactory
     * @param RegionInterfaceFactory $regionInterfaceFactory
     * @param AddressInterfaceFactory $addressDataFactory
     * @param AddressRepositoryInterface $addressRepository
     */
    public function __construct(
        Context $context,
        DataObjectHelper $objectHelper,
        FormFactory $formFactory,
        RegionFactory $regionFactory,
        RegionInterfaceFactory $regionInterfaceFactory,
        AddressInterfaceFactory $addressDataFactory,
        AddressRepositoryInterface $addressRepository,
        Customer $customerHelper,
        CustomerAddr $customerAddrApi
    ) {
        parent::__construct($context);
        $this->objectHelper = $objectHelper;
        $this->formFactory = $formFactory;
        $this->regionFactory = $regionFactory;
        $this->regionDataFactory = $regionInterfaceFactory;
        $this->addressRepository = $addressRepository;
        $this->addressDataFactory = $addressDataFactory;
        $this->customerHelper = $customerHelper;
        $this->customerAddrApi = $customerAddrApi;
    }

    /**
     * @param CustomerInterface $customer
     * @param string $addressSourceParamName
     * @param string $companyDataSourceParamName
     * @return AddressInterface
     * @throws LocalizedException
     */
    public function createCustomerAddress(
        CustomerInterface $customer,
        string $addressSourceParamName,
        string $companyDataSourceParamName
    ): AddressInterface {
        $addressForm = $this->formFactory->create(
            'customer_address',
            'customer_address_edit',
            []
        );
        $addressData = $addressForm->extractData($this->_getRequest(), $addressSourceParamName);
        $attributeValues = $addressForm->compactData($addressData);

        $this->updateRegionData($attributeValues);

        $addressDataObject = $this->addressDataFactory->create();
        $this->objectHelper->populateWithArray(
            $addressDataObject,
            $attributeValues,
            AddressInterface::class
        );
        $companyData = $this->_getRequest()->getParam($companyDataSourceParamName, false);
        $companyName = '';
        if ($companyData) {
            $companyName = $companyData['company_name'] ?? '';
        }
        $addressDataObject
            ->setCustomerId($customer->getId())
            ->setFirstname($customer->getFirstname())
            ->setLastname($customer->getLastname())
            ->setCompany($companyName)
            ->setIsDefaultBilling(true)
            ->setIsDefaultShipping(true);

        return $this->addressRepository->save($addressDataObject);
    }

    /**
     * @param CustomerInterface $customer
     * @param AddressInterface $address
     * @return bool
     */
    public function syncCustomerAddress(CustomerInterface $customer, AddressInterface $address): bool
    {
        $customerNum = $this->customerHelper->getCustomerNum($customer->getId());
        if (!$customerNum) {
            return false;
        }
        if (!$this->customerAddrApi->checkAddress($this->customerHelper->getCustomerModel($customer), $address, $customerNum)) {
            $addressCode = 'PRIMARY';
            return $this->customerAddrApi->create(
                $this->customerHelper->getcustomerModel($customer),
                $address,
                $customerNum,
                $addressCode
            );
        }
        return false;
    }

    public function logDebug($message)
    {
        $this->_logger->debug($message);
    }

    /**
     * Update region data
     *
     * @param array $attributeValues
     * @return void
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    private function updateRegionData(array &$attributeValues)
    {
        if (!empty($attributeValues['region_id'])) {
            $newRegion = $this->regionFactory->create()->load($attributeValues['region_id']);
            $attributeValues['region_code'] = $newRegion->getCode();
            $attributeValues['region'] = $newRegion->getDefaultName();
        }

        $regionData = [
            RegionInterface::REGION_ID => !empty($attributeValues['region_id']) ? $attributeValues['region_id'] : null,
            RegionInterface::REGION => !empty($attributeValues['region']) ? $attributeValues['region'] : null,
            RegionInterface::REGION_CODE => !empty($attributeValues['region_code'])
                ? $attributeValues['region_code']
                : null,
        ];

        $region = $this->regionDataFactory->create();
        $this->objectHelper->populateWithArray(
            $region,
            $regionData,
            RegionInterface::class
        );
        $attributeValues['region'] = $region;
    }
}
