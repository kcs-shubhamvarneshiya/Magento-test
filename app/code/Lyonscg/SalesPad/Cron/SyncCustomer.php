<?php

namespace Lyonscg\SalesPad\Cron;

use Lyonscg\SalesPad\Api\Data\CustomerSyncInterface;
use Lyonscg\SalesPad\Api\SyncRepositoryInterface;
use Lyonscg\SalesPad\Helper\Email as EmailHelper;
use Lyonscg\SalesPad\Model\Api\Customer as CustomerApi;
use Lyonscg\SalesPad\Model\Api\CustomerAddr as CustomerAddrApi;
use Lyonscg\SalesPad\Model\Api\Logger;
use Lyonscg\SalesPad\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

class SyncCustomer extends AbstractSync
{
    /**
     * @var CustomerApi
     */
    protected $customerApi;

    /**
     * @var CustomerAddrApi
     */
    protected $customerAddrApi;

    /**
     * SyncCustomer constructor.
     * @param Config $config
     * @param SyncRepositoryInterface $syncRepository
     * @param EmailHelper $emailHelper
     * @param Logger $logger
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param CustomerApi $customerApi
     * @param CustomerAddrApi $customerAddrApi
     */
    public function __construct(
        Config $config,
        SyncRepositoryInterface $syncRepository,
        EmailHelper $emailHelper,
        Logger $logger,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        CustomerApi $customerApi,
        CustomerAddrApi $customerAddrApi
    ) {
        parent::__construct(
            $config,
            $syncRepository,
            $emailHelper,
            $logger,
            $storeManager,
            $scopeConfig
        );

        $this->customerApi = $customerApi;
        $this->customerAddrApi = $customerAddrApi;
    }

    /**
     * Process synchronization of customers
     * belonging to the current store.
     *
     * @param int|null $storeId
     */
    protected function processSync(int $storeId = null)
    {
        if (!$this->config->getCustomerSyncEnabled()) {

            return;
        }

        $limit = intval($this->config->getCustomerSyncLimit());
        $customersToSync = $this->syncRepository->getCustomersToSync($limit, $storeId);

        /** @var CustomerSyncInterface $customerSync */
        foreach ($customersToSync as $customerSync) {
            if (!$customerSync->canSync()) {

                continue;
            }

            $failed = false;
            try {
                if (!$customerSync->sync()) {
                    $failed = true;
                }
            } catch (NoSuchEntityException $e) {
                // customer entry does not exist in Magento, delete the sync entry
                $this->logger->debug($e);
                $this->syncRepository->deleteCustomerEntry($customerSync);
                continue;
            } catch (\Exception $e) {
                $this->logger->debug($e);
                $failed = true;
            }

            if ($failed) {
                // increment attempts counter
                $customerSync->setSyncAttempts($customerSync->getSyncAttempts() + 1);
                $this->syncRepository->saveCustomerEntry($customerSync);
                if ($customerSync->getSyncAttempts() == 1) {
                    $this->emailHelper->sendCustomerErrorEmail($customerSync);
                }
            } else {
                // success, so we can delete the entry now
                $this->syncRepository->deleteCustomerEntry($customerSync);
            }
        }
    }
}
