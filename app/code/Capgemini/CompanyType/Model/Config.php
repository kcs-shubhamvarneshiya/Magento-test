<?php
/**
 * Capgemini_CompanyType
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\CompanyType\Model;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Customer\Model\Session;
use Magento\CustomerSegment\Model\ResourceModel\Segment\CollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Config
{
    public const XML_CONFIG_WHOLESALE_TYPE_PATH = 'company/company_types/wholesale_company';
    public const XML_CONFIG_TRADE_TYPE_PATH = 'company/company_types/trade_company';

    public const WHOLESALE = 'wholesale';
    public const TRADE = 'trade';
    public const RETAIL = 'retail';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Session
     */
    protected Session $customerSession;
    /**
     * @var CustomerRegistry
     */
    private CustomerRegistry $customerRegistry;
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $segmentCollectionFactory;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param Session $customerSession
     * @param CustomerRegistry $customerRegistry
     * @param CollectionFactory $segmentCollectionFactory
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        Session $customerSession,
        CustomerRegistry $customerRegistry,
        CollectionFactory $segmentCollectionFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->customerRegistry = $customerRegistry;
        $this->segmentCollectionFactory = $segmentCollectionFactory;
    }

    /**
     * Get customer company type for specified customer.
     * If the customer is not specified then it returns the current customer company type.
     *
     * @param Customer | CustomerInterface $customer
     * @param int|null $websiteId
     * @return string|array
     */
    public function getCustomerCompanyType($customer = null, $websiteId = null)
    {
        try {
            if (!$customer) {

                return $this->getCompanyTypeByCustomerId($this->customerSession->getId());
            }

            $customer = $customer instanceof Customer
                ? $customer
                : $this->customerRegistry->retrieve($customer->getId());
            $types = [];
            foreach ($customer->getSharedWebsiteIds() as $siteId) {
                $segmentCollection = $this->segmentCollectionFactory->create();
                $segmentCollection->getSelect()->joinLeft(
                    ['customer_id_table' => $segmentCollection->getTable('magento_customersegment_customer')],
                    'customer_id_table.segment_id = main_table.segment_id',
                    ['customer_id' => 'customer_id']
                );
                $segmentCollection
                    ->addWebsiteFilter($siteId)
                    ->addFieldToFilter('customer_id', $customer->getId());
                $types[(int) $siteId] = $this->getCompanyTypeBySegmentIds($segmentCollection->getAllIds(), (int) $siteId);
            }

            if ($websiteId !== null) {

                return $types[(int) $websiteId] ?? self::RETAIL;
            }

            return match (count($types)) {
                0 => self::RETAIL,
                1 => current($types),
                default => $types,
            };
        } catch (\Exception $exception) {
            return self::RETAIL;
        }
    }

    public function getCompanyTypeByCustomerId($customerId, $storeId = null)
    {
        try {
            $websiteId = $storeId === null ? null : $this->storeManager->getStore($storeId)->getWebsiteId();
            $customer = $this->customerRegistry->retrieve($customerId);
        } catch (\Exception $exception) {

            return self::RETAIL;
        }

        return $this->getCustomerCompanyType($customer, $websiteId);
    }

    /**
     * @param int[] $segmentIds
     * @param int|null $websiteId
     * @return string
     */
    private function getCompanyTypeBySegmentIds(array $segmentIds, ?int $websiteId = null): string
    {
        if (!empty($segmentIds)) {
            try {
                $websiteId = $websiteId ?? $this->storeManager->getWebsite()->getId();
            } catch (\Exception $exception) {
                $websiteId = 0;
            }

            if (in_array($this->getWholesaleSegmentConfigValue((int) $websiteId), $segmentIds)) {

                return self::WHOLESALE;
            } elseif (in_array($this->getTradeSegmentConfigValue((int) $websiteId), $segmentIds)) {

                return self::TRADE;
            }
        }

        return self::RETAIL;
    }

    private function getWholesaleSegmentConfigValue(int $websiteId = 0)
    {
        return (int) $this->scopeConfig->getValue(
            self::XML_CONFIG_WHOLESALE_TYPE_PATH,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    private function getTradeSegmentConfigValue(int $websiteId = 0)
    {
        return (int) $this->scopeConfig->getValue(
            self::XML_CONFIG_TRADE_TYPE_PATH,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }
}
