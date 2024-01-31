<?php

namespace Lyonscg\AddressRestrictions\Observer;

use Lyonscg\AddressRestrictions\Api\AddressDeleteLogRepositoryInterface;
use Lyonscg\AddressRestrictions\Model\Config;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Event\ObserverInterface;

class CustomerAddressDeleteCommitAfter implements ObserverInterface
{
    /**
     * @var AddressDeleteLogRepositoryInterface
     */
    protected $addressDeleteLogRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * CustomerAddressDeleteCommitAfter constructor.
     * @param AddressDeleteLogRepositoryInterface $addressDeleteLogRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        AddressDeleteLogRepositoryInterface $addressDeleteLogRepository,
        CustomerRepositoryInterface $customerRepository,
        Config $config,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->addressDeleteLogRepository = $addressDeleteLogRepository;
        $this->customerRepository = $customerRepository;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var AddressInterface $address */
        $address = $observer->getEvent()->getCustomerAddress();
        try {
            $customerId = $address->getCustomerId();
            if (!$customerId) {
                $customerId = $address->getParentId();
            }
            $customer = $this->customerRepository->getById($customerId);
            $groups = $this->config->getCustomerGroups();
            $groupId = (string)$customer->getGroupId();
            if (!$groupId || !in_array($groupId, $groups)) {
                return;
            }
        } catch (\Exception $e) {
            $this->logger->error('Exception getting customer for address: ' . strval($e));
            return;
        }
        try {
            $addressDeleteLog = $this->addressDeleteLogRepository->createFromAddress($address);
            if (!$addressDeleteLog || !$addressDeleteLog->getId()) {
                $this->logger->error(
                    'Unable to save address delete log for address: ' .
                    $address->getId() .
                    ', customer: ' .
                    $address->getCustomerId()
                );
            }
        } catch (\Exception $e) {
            $this->logger->error('Exception saving address delete log: ' . strval($e));
        }
    }
}
