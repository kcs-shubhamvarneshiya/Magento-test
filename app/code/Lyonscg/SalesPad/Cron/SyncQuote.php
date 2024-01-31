<?php


namespace Lyonscg\SalesPad\Cron;

use Lyonscg\SalesPad\Api\Data\QuoteItemSyncInterface;
use Lyonscg\SalesPad\Api\Data\QuoteSyncInterface;
use Lyonscg\SalesPad\Api\SyncRepositoryInterface;
use Lyonscg\SalesPad\Helper\Email as EmailHelper;
use Lyonscg\SalesPad\Helper\Quote as QuoteHelper;
use Lyonscg\SalesPad\Model\Api\Quote as QuoteApi;
use Lyonscg\SalesPad\Model\Api\Logger;
use Lyonscg\SalesPad\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\StoreManagerInterface;

class SyncQuote extends AbstractSync
{
    /**
     * @var QuoteApi
     */
    protected $quoteApi;

    /**
     * @var QuoteHelper
     */
    protected $quoteHelper;

    /**
     * @var Emulation
     */
    protected $appEmulation;

    /**
     * SyncQuote constructor.
     * @param Config $config
     * @param SyncRepositoryInterface $syncRepository
     * @param EmailHelper $emailHelper
     * @param Logger $logger
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param QuoteApi $quoteApi
     * @param QuoteHelper $quoteHelper
     * @param Emulation $appEmulation
     */
    public function __construct(
        Config $config,
        SyncRepositoryInterface $syncRepository,
        EmailHelper $emailHelper,
        Logger $logger,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        QuoteApi $quoteApi,
        QuoteHelper $quoteHelper,
        Emulation $appEmulation
    ) {
        parent::__construct(
            $config,
            $syncRepository,
            $emailHelper,
            $logger,
            $storeManager,
            $scopeConfig
        );

        $this->quoteApi = $quoteApi;
        $this->quoteHelper = $quoteHelper;
        $this->appEmulation = $appEmulation;
    }

    /**
     * Process synchronization of quotes
     * belonging to the current store.
     *
     * @param int|null $storeId
     */
    protected function processSync(int $storeId = null)
    {
        if (!$this->config->getQuoteSyncEnabled()) {
            return;
        }
        $limit = intval($this->config->getQuoteSyncLimit());
        $quotesToSync = $this->syncRepository->getQuotesToSync($limit, $storeId);

        /** @var QuoteSyncInterface $quoteSync */
        foreach ($quotesToSync as $quoteSync) {
            if (!$quoteSync->canSync()) {

                continue;
            }

            $failed = false;
            $customer = null;
            try {
                if ($quoteSync->getSyncAction() != QuoteSyncInterface::ACTION_DELETE) {
                    $list = $quoteSync->getRequisitionList();
                    $customer = $this->quoteHelper->getCustomer($list);
                    if ($customer) {
                        $storeId = $customer->getStoreId();
                        $this->appEmulation->startEnvironmentEmulation($storeId);
                    }
                }
                if (!$quoteSync->sync()) {
                    $failed = true;
                }
            } catch (NoSuchEntityException $e) {
                $this->logger->debug(__('Quote %1 does not exist', $quoteSync->getQuoteId()));
                $this->syncRepository->deleteQuoteEntry($quoteSync);
                continue;
            } catch (\Exception $e) {
                $this->logger->debug($e);
                $failed = true;
            } finally {
                $this->appEmulation->stopEnvironmentEmulation();
            }

            if ($failed) {
                $quoteSync->setSyncAttempts($quoteSync->getSyncAttempts() + 1);
                $this->syncRepository->saveQuoteEntry($quoteSync);
                if ($quoteSync->getSyncAttempts() == 1) {
                    $this->emailHelper->sendQuoteErrorEmail($quoteSync);
                }
            } else {
                $this->_syncItemsForQuoteId($quoteSync->getQuoteId(), $customer);
                $this->syncRepository->deleteQuoteEntry($quoteSync);
            }
        }
    }

    protected function _syncItemsForQuoteId($quoteId, $customer)
    {
        $salesDocNum = $this->quoteHelper->getSalesDocNum($quoteId);
        $quoteItemsToSync = $this->syncRepository->getQuoteItemsToSyncForQuoteId($quoteId);
        /** @var QuoteItemSyncInterface $quoteItemSync */
        foreach ($quoteItemsToSync as $quoteItemSync) {
            if (!$quoteItemSync->canSync()) {

                continue;
            }

            $quoteItemSync->setSalesDocNum($salesDocNum);
            $failed = false;
            try {
                if ($customer) {
                    $storeId = $customer->getStoreId();
                    $this->appEmulation->startEnvironmentEmulation($storeId);
                }
                if (!$quoteItemSync->sync()) {
                    $failed = true;
                }
            } catch (NoSuchEntityException $e) {
                $this->logger->debug(__('Quote Item %1 does not exist', $quoteItemSync->getItemId()));
            } catch (\Exception $e) {
                $this->logger->debug($e);
                $failed = true;
            } finally {
                $this->appEmulation->stopEnvironmentEmulation();
            }

            if ($failed) {
                $quoteItemSync->setSyncAttempts($quoteItemSync->getSyncAttempts() + 1);
                $this->syncRepository->saveQuoteItemEntry($quoteItemSync);
            } else {
                $this->syncRepository->deleteQuoteItemEntry($quoteItemSync);
            }
        }
    }
}
