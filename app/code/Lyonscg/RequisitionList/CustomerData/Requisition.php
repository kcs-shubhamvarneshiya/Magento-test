<?php

namespace Lyonscg\RequisitionList\CustomerData;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\RequisitionList\Api\RequisitionListRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\RequisitionList\Model\Config as ModuleConfig;
use Magento\RequisitionList\Api\Data\RequisitionListInterface;
use Magento\Framework\Api\SortOrderBuilder;

class Requisition extends \Magento\RequisitionList\CustomerData\Requisition
{
    /**
     * @var RequisitionListRepositoryInterface
     */
    private $requisitionListRepository;

    /**
     * @var \Magento\Authorization\Model\UserContextInterface
     */
    private $userContext;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var ModuleConfig
     */
    private $moduleConfig;

    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    private $httpContext;

    private $filterBuilder;

    private $filterGroupBuilder;

    private $customerRepository;

    /**
     * @param RequisitionListRepositoryInterface $requisitionListRepository
     * @param UserContextInterface $userContext
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ModuleConfig $moduleConfig
     * @param SortOrderBuilder $sortOrderBuilder
     * @param \Magento\Framework\App\Http\Context $httpContext
     */
    public function __construct(
        RequisitionListRepositoryInterface $requisitionListRepository,
        UserContextInterface $userContext,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ModuleConfig $moduleConfig,
        SortOrderBuilder $sortOrderBuilder,
        \Magento\Framework\App\Http\Context $httpContext,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->requisitionListRepository = $requisitionListRepository;
        $this->userContext = $userContext;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->moduleConfig = $moduleConfig;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->httpContext = $httpContext;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->customerRepository = $customerRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getSectionData()
    {
        return [
            'items' => $this->getRequisitionLists(),
            'max_allowed_requisition_lists' => $this->moduleConfig->getMaxCountRequisitionList(),
            'is_enabled' => (bool)$this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH)
        ];
    }

    /**
     * Get RequisitionList items
     *
     * @return array
     */
    private function getRequisitionLists()
    {
        $customerId = $this->userContext->getUserId();
        if (!$customerId) {
            return [];
        }
        /**@var \Magento\Framework\Api\SortOrder $nameSort */
        $nameSort = $this->sortOrderBuilder
            ->setField(RequisitionListInterface::UPDATED_AT)
            ->setDescendingDirection()
            ->create();

        $salesPadCustomerNum = false;
        try {
            $customer = $this->customerRepository->getById($customerId);
            // CRC-638 Moved sales_pad_customer_num customer attribute from a custom to an extension one.
            $salesPadCustomerNum = $customer->getExtensionAttributes()->getSalesPadCustomerNum() ?? false;
        } catch (\Exception $e) {
        }

        $salesPadFilter   = $this->filterBuilder->setField('sales_pad_customer_num')
                                                ->setvalue($salesPadCustomerNum)
                                                ->setConditionType('eq')
                                                ->create();

        $filterOr = $this->filterGroupBuilder->addFilter($salesPadFilter)
                                             ->create();

        $builder = $this->searchCriteriaBuilder->setFilterGroups([$filterOr])
                                               ->addSortOrder($nameSort);

        $lists = $this->requisitionListRepository->getList($builder->create())->getItems();
        if (empty($lists)) {
            return [];
        }

        $items = [];
        /**@var \Magento\RequisitionList\Api\Data\RequisitionListInterface $list */
        foreach ($lists as $list) {
            $items[] = [
                'id' => $list->getId(),
                'name' => $list->getName()
            ];
        }
        return $items;
    }
}
