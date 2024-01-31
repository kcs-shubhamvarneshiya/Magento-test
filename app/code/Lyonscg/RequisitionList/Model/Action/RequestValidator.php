<?php

namespace Lyonscg\RequisitionList\Model\Action;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\RequisitionList\Api\RequisitionListRepositoryInterface;
use Magento\RequisitionList\Model\Action\RequestValidator as BaseRequestValidator;
use Magento\RequisitionList\Model\Config as ModuleConfig;

/**
 * Controller action request validator.
 */
class RequestValidator extends BaseRequestValidator
{

    /**
     * @var \Magento\RequisitionList\Model\Config
     */
    private $moduleConfig;

    /**
     * @var \Magento\Authorization\Model\UserContextInterface
     */
    private $userContext;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    private $formKeyValidator;

    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    private $resultFactory;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlBuilder;

    /**
     * @var \Magento\RequisitionList\Api\RequisitionListRepositoryInterface
     */
    private $requisitionListRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * RequestValidator constructor.
     * @param ModuleConfig $moduleConfig
     * @param UserContextInterface $userContext
     * @param Validator $formKeyValidator
     * @param ResultFactory $resultFactory
     * @param UrlInterface $urlBuilder
     * @param RequisitionListRepositoryInterface $requisitionListRepository
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        ModuleConfig $moduleConfig,
        UserContextInterface $userContext,
        Validator $formKeyValidator,
        ResultFactory $resultFactory,
        UrlInterface $urlBuilder,
        RequisitionListRepositoryInterface $requisitionListRepository,
        CustomerRepositoryInterface $customerRepository
    ) {
        parent::__construct(
            $moduleConfig,
            $userContext,
            $formKeyValidator,
            $resultFactory,
            $urlBuilder,
            $requisitionListRepository
        );
        $this->moduleConfig = $moduleConfig;
        $this->userContext = $userContext;
        $this->formKeyValidator = $formKeyValidator;
        $this->resultFactory = $resultFactory;
        $this->urlBuilder = $urlBuilder;
        $this->requisitionListRepository = $requisitionListRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Get validator result.
     *
     * @param RequestInterface $request
     * @return ResultInterface
     */
    public function getResult(RequestInterface $request)
    {
        $result = null;

        if ($this->userContext->getUserType() !== UserContextInterface::USER_TYPE_CUSTOMER) {
            /** @var \Magento\Framework\Controller\Result\Redirect $result */
            $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $result->setPath('customer/account/login');
        } elseif (!$this->isActionAllowed($request)) {
            /** @var \Magento\Framework\Controller\Result\Redirect $result */
            $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $result->setRefererUrl();
        }

        return $result;
    }

    /**
     * Is action allowed.
     *
     * @param RequestInterface $request
     * @return bool
     */
    private function isActionAllowed(RequestInterface $request)
    {
        return $this->moduleConfig->isActive() &&
            $this->isListAllowed($request) &&
            $this->isPostValid($request);
    }

    /**
     * Is action allowed for customer.
     *
     * @param RequestInterface $request
     * @return bool
     */
    private function isListAllowed(RequestInterface $request)
    {
        $listId = $request->getParam('requisition_id') ? : $request->getParam('list_id');
        if ($listId) {
            $customerId = $this->userContext->getUserId();
            try {
                $list = $this->requisitionListRepository->get($listId);
            } catch (NoSuchEntityException $e) {
                return false;
            }
            $customerMatch = $list->getCustomerId() == $customerId;
            if (!$customerMatch) {
                $currentSPNum = $this->getSalesPadCustomerNumber($customerId);
                $ownerSPNum = $this->getSalesPadCustomerNumber($list->getCustomerId());
                return $currentSPNum == $ownerSPNum;
            }
        }
        return true;
    }

    /**
     * Is post request valid.
     *
     * @param RequestInterface $request
     * @return bool
     */
    private function isPostValid(RequestInterface $request)
    {
        return !$request->isPost() || $this->formKeyValidator->validate($request);
    }

    private function getSalesPadCustomerNumber($customerId)
    {
        try {
            $customer = $this->customerRepository->getById($customerId);
            // CRC-638 Moved sales_pad_customer_num customer attribute from a custom to an extension one.
            if ($salesPadCustomerNum = $customer->getExtensionAttributes()->getSalesPadCustomerNum()) {

                return $salesPadCustomerNum;
            }
        } catch (\Exception $e) {
        }
        return false;
    }
}
