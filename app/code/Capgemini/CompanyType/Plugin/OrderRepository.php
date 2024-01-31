<?php
/**
 * Capgemini_CompanyType
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\CompanyType\Plugin;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Plugin for \Magento\Sales\Api\OrderRepositoryInterface
 */
class OrderRepository
{
    /**
     * @var OrderExtensionFactory
     */
    protected $extensionFactory;
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;
    /**
     * @var SearchCriteriaInterface
     */
    protected $searchCriteria;
    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;
    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBulder;

    /**
     * Constructor
     *
     * @param OrderExtensionFactory $extensionFactory
     */
    public function __construct(
        OrderExtensionFactory $extensionFactory,
        CustomerRepositoryInterface $customerRepository,
        SearchCriteriaBuilder $searchCriteriaBulder,
        FilterBuilder $filterBuilder
    ) {
        $this->extensionFactory = $extensionFactory;
        $this->customerRepository = $customerRepository;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBulder = $searchCriteriaBulder;
    }

    /**
     * Add company type to the extension attributes
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterGet(
        OrderRepositoryInterface $subject,
        OrderInterface $order
    ) {
        $this->addExtensionAttributes($order);
        $customerId = $order->getCustomerId();
        if ($customerId) {
            $customer = $this->customerRepository->getById($customerId);
        } else {
            $customer = null;
        }
        $this->addCustomerAttributes($order, $customer);
        return $order;
    }

    /**
     * Add company type to the extension attributes
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $orders
     *
     * @return OrderSearchResultInterface
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderSearchResultInterface $orders
    ) {
        $customerIds = [];

        /* @var OrderInterface $entity */
        foreach ($orders->getItems() as $entity) {
            $this->addExtensionAttributes($entity);
            $customerIds[] = $entity->getCustomerId();
        }
        $customerIds = array_unique($customerIds);

        $customers = $this->getCustomers($customerIds);
        foreach ($orders->getItems() as $entity) {
            if ($entity->getCustomerId() && isset($customers[$entity->getCustomerId()])) {
                $customer = $customers[$entity->getCustomerId()];
            } else {
                $customer = null;
            }
            $this->addCustomerAttributes($entity, $customer);
        }

        return $orders;
    }

    /**
     * Add extension attributes to order
     *
     * @param OrderInterface $order
     */
    protected function addExtensionAttributes(OrderInterface $order)
    {
        $extensionAttributes = $order->getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->extensionFactory->create();
        }
        $extensionAttributes->setCompanyType($order->getCompanyType());
        $order->setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get customers array by ids
     *
     * @param $customerIds
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getCustomers($customerIds)
    {
        $customers = [];
        if (count($customerIds) > 0) {
            $filters = [];
            $filters[] = $this->filterBuilder->setField('entity_id')
                ->setConditionType('in')
                ->setValue($customerIds)
                ->create();
            $criteria = $this->searchCriteriaBulder->addFilters($filters)->create();
            $list = $this->customerRepository->getList($criteria);
            foreach ($list->getItems() as $customer) {
                $customers[$customer->getId()] = $customer;
            }
        }
        return $customers;
    }

    /**
     * Add extension attributes from customer
     *
     * @param OrderInterface $order
     * @param CustomerInterface $customer
     */
    protected function addCustomerAttributes(OrderInterface $order, CustomerInterface $customer = null)
    {
        $extensionAttributes = $order->getExtensionAttributes();
        if ($customer) {
            $extensionAttributes->setCustomerNumberVc($customer->getCustomAttribute('customer_number_vc')
                ? $customer->getCustomAttribute('customer_number_vc')->getValue() : '');
            $extensionAttributes->setCustomerNumberTech($customer->getCustomAttribute('customer_number_tech')
                ? $customer->getCustomAttribute('customer_number_tech')->getValue() : '');
            $extensionAttributes->setCustomerNumberGl($customer->getCustomAttribute('customer_number_gl')
                ? $customer->getCustomAttribute('customer_number_gl')->getValue() : '');
        } else {
            $extensionAttributes->setCustomerNumberVc('');
            $extensionAttributes->setCustomerNumberTech('');
            $extensionAttributes->setCustomerNumberGl('');
        }
    }
}
