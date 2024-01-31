<?php

namespace Lyonscg\SalesPad\Observer;

use Capgemini\CompanyType\Model\Config;
use Lyonscg\SalesPad\Api\CustomerLinkRepositoryInterface;
use Lyonscg\SalesPad\Api\SyncRepositoryInterface;
use Lyonscg\SalesPad\Helper\Email as EmailHelper;
use Lyonscg\SalesPad\Model\Api\Logger;
use Magento\Customer\Model\Customer;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;

class CustomerSaveCommitAfter implements ObserverInterface
{
    /**
     * @var SyncRepositoryInterface
     */
    protected $syncRepository;

    protected $customerLinkRepository;

    protected $logger;

    protected $emailHelper;
    /**
     * @var Config
     */
    protected Config $companyTypeConfig;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * CustomerSaveCommitAfter constructor.
     * @param SyncRepositoryInterface $syncRepository
     */
    public function __construct(
        SyncRepositoryInterface $syncRepository,
        CustomerLinkRepositoryInterface $customerLinkRepository,
        RequestInterface $request,
        EmailHelper $emailHelper,
        Logger $logger,
        Config $companyTypeConfig
    ) {
        $this->syncRepository = $syncRepository;
        $this->customerLinkRepository = $customerLinkRepository;
        $this->request = $request;
        $this->emailHelper = $emailHelper;
        $this->logger = $logger;
        $this->companyTypeConfig = $companyTypeConfig;
    }

    /**
     * Only executes if the object is new or was changed
     *
     * Creates a sync entry for the customer and attempts to sync immediately.  If the sync fails, the entry will
     * remain in the DB later to be picked up by the cron.
     *
     * @param EventObserver $observer
     */
    public function execute(EventObserver $observer)
    {
        /** @var Customer $customer */
        $customer = $observer->getEvent()->getCustomer();
        //Exclude wholesale customers from Salespad sync
        if ($this->companyTypeConfig->getCustomerCompanyType($customer) == Config::WHOLESALE) {
            return;
        }
        if ($this->request->getActionName() != 'createpost' || !$this->getCustomerNum($customer)) {
            // create sync record
            $customerSync = $this->syncRepository->addCustomer($customer);
            try {
                if (!$customerSync->canSync()) {

                    return;
                }

                // try to sync immediately, requirement in confluence: SalesPad integration for customers is real time
                if ($salespadCustomerNum = $customerSync->sync()) {
                    // successful sync, so delete the sync entry
                    $this->syncRepository->deleteCustomerEntry($customerSync);
                    $this->_maybeUpdateSalesPadCustomerNum($customer, $salespadCustomerNum);
                } else {
                    $this->emailHelper->sendCustomerErrorEmail($customerSync);
                }
            } catch (\Exception $e) {
                // if the sync fails or throws an exception, the sync entry will still exist
                // in the database and the sync will be attempted later by the cron
                $this->logger->debug(__('Exception when trying to sync customer: %1: %2', $customer->getId(), strval($e)));
            }
        }
    }

    /**
     * @param $customer Customer
     */
    protected function _maybeUpdateSalesPadCustomerNum(Customer $customer, $salespadCustomerNum = null)
    {
        if ($customerNum = (!empty($salespadCustomerNum) && $salespadCustomerNum !== true) ? $salespadCustomerNum : $this->getCustomerNum($customer)) {
            $this->customerLinkRepository->add(
                $customer->getId(),
                $customer->getEmail(),
                $customer->getWebsiteId(),
                $customerNum
            );
        }

    }

    /**
     * @param Customer $customer
     * @return false|string
     */
    protected function getCustomerNum(Customer $customer)
    {
        // CRC-638 Moved sales_pad_customer_num customer attribute from a custom to an extension one.
        $customerNum = $customer->getDataModel()->getExtensionAttributes()->getSalesPadCustomerNum();
        if (!$customerNum) {
            $customerNum = $customer->getSalesPadCustomerNum();
        }
        return $customerNum;
    }
}
