<?php

namespace Capgemini\CybersourceZeroAuth\Plugin\Gateway\Request\Soap;

use CyberSource\SecureAcceptance\Gateway\Helper\SubjectReader;
use CyberSource\SecureAcceptance\Gateway\Request\Soap\PaymentDataBuilder;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\GroupInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Plugin to set zero auth amount
 */
class PaymentDataBuilderPlugin
{
    const XML_PATH_ENABLED = 'payment/chcybersource/zero_amount_authorization';
    const XML_PATH_CUSTOMER_GROUPS = 'payment/chcybersource/zero_amount_customer_groups';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var SubjectReader
     */
    protected $subjectReader;
    protected CustomerRepositoryInterface $customerRepository;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param SubjectReader $subjectReader
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        SubjectReader $subjectReader,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->subjectReader = $subjectReader;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param PaymentDataBuilder $subject
     * @param array $result
     * @param array $buildSubject
     * @return array
     */
    public function afterBuild(PaymentDataBuilder $subject, array $result, array $buildSubject): array
    {
        $paymentDO = $this->subjectReader->readPayment($buildSubject);
        $order = $paymentDO->getOrder();
        $storeId = $order->getStoreId();

        if ($this->scopeConfig->getValue(self::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE, $storeId) == '1') {
            $customerId = $order->getCustomerId();
            if ($customerId) {
                $customer = $this->customerRepository->getById($customerId);
                $customerGroupId = $customer->getGroupId();
            } else {
                $customerGroupId = GroupInterface::NOT_LOGGED_IN_ID;
            }
            $groups = $this->scopeConfig->getValue(self::XML_PATH_CUSTOMER_GROUPS, ScopeInterface::SCOPE_STORE, $storeId);
            if (!is_null($groups) && is_string($groups)) {
                $groups = explode(',', $groups);
                if (in_array($customerGroupId, $groups) || in_array(GroupInterface::CUST_GROUP_ALL, $groups)) {
                    $result['purchaseTotals']['grandTotalAmount'] = '0.00';
                }
            }
        }
        return $result;
    }
}