<?php


namespace Capgemini\Company\Model\Company;


use Magento\Company\Api\Data\CompanyInterface;
use Magento\Company\Model\Company;
use Magento\Company\Model\ResourceModel\Company\CollectionFactory as CompanyCollectionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\AttributeMetadataResolver;
use Magento\Eav\Model\Config;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class DataProvider extends \Magento\Company\Model\Company\DataProvider
{
    const WEBSITE = 'website';
    const BUSINESS_TYPE = 'business_type';
    const CELL_PHONE = 'cell_phone';
    const FAX = 'fax';
    const MEMBER_STATE = 'member_state';
    const VAT_NUMBER = 'vat_number';
    const SALESPAD_CUSTOMER_NUM = 'sales_pad_customer_num';

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CompanyCollectionFactory $companyCollectionFactory,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        CustomerRepositoryInterface $customerRepository,
        Config $eavConfig,
        AttributeMetadataResolver $attributeMetadataResolver,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $companyCollectionFactory,
            $extensionAttributesJoinProcessor,
            $customerRepository,
            $eavConfig,
            $attributeMetadataResolver,
            $meta,
            $data
        );

        $this->customerRepository = $customerRepository;
    }

    /**
     * Get company information data.
     *
     * @param \Magento\Company\Api\Data\CompanyInterface $company
     * @return array
     */
    public function getInformationData(CompanyInterface $company)
    {
        return [
            Company::LEGAL_NAME => $company->getLegalName(),
            Company::VAT_TAX_ID => $company->getVatTaxId(),
            Company::RESELLER_ID => $company->getResellerId(),
            Company::COMMENT => $company->getComment(),
            self::WEBSITE => $company->getWebsite(),
            self::BUSINESS_TYPE => $company->getBusinessType(),
            self::MEMBER_STATE => $company->getMemberState(),
            self::VAT_NUMBER => $company->getVatNumber(),
        ];
    }

    /**
     * Get address data.
     *
     * @param \Magento\Company\Api\Data\CompanyInterface $company
     * @return array
     */
    public function getAddressData(CompanyInterface $company)
    {
        return [
            Company::STREET => $company->getStreet(),
            Company::CITY => $company->getCity(),
            Company::COUNTRY_ID => $company->getCountryId(),
            Company::REGION => $company->getRegion(),
            Company::REGION_ID => $company->getRegionId(),
            Company::POSTCODE => $company->getPostcode(),
            Company::TELEPHONE => $company->getTelephone(),
            self::CELL_PHONE => $company->getCellPhone(),
            self::FAX => $company->getFax(),
        ];
    }

    /**
     * Get company admin data.
     *
     * @param CompanyInterface $company
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCompanyAdminData(CompanyInterface $company)
    {
        $data = parent::getCompanyAdminData($company);
        $customer = $this->customerRepository->getById($company->getSuperUserId());
        $data['extension_attributes'][self::SALESPAD_CUSTOMER_NUM] = (string) $customer->getExtensionAttributes()->getSalesPadCustomerNum();

        return $data;
    }
}
