<?php

namespace Lyonscg\SalesPad\Helper;

use Exception;
use Lyonscg\SalesPad\Api\Data\AbstractSyncInterface;
use Lyonscg\SalesPad\Api\Data\CustomerSyncInterface;
use Lyonscg\SalesPad\Api\Data\OrderSyncInterface;
use Lyonscg\SalesPad\Api\Data\QuoteSyncInterface;
use Lyonscg\SalesPad\Model\Sync\Quote;
use Lyonscg\SalesPad\Model\Sync\QuoteItem;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Store\Model\StoreManagerInterface;

class SyncStoreManager
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var State
     */
    private $appState;
    /**
     * @var int|bool
     */
    private $resolvedStoreId;

    public function __construct(StoreManagerInterface $storeManager, State $appState)
    {
        $this->storeManager = $storeManager;
        $this->appState = $appState;
    }

    /**
     * @param int|null $id
     * @return void
     */
    public function setResolvedStoreId(int $id = null)
    {
        $this->resolvedStoreId = $id;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function resolveStoreForSync(): array
    {
        $storeId = $this->storeManager->getStore()->getId();

        if ($this->resolvedStoreId !== null) {

            return [
                'current'  => $storeId,
                'resolved' => $this->resolvedStoreId
            ];
        }

        $areaCode = $this->appState->getAreaCode();

        if ($areaCode === Area::AREA_CRONTAB || $areaCode === Area::AREA_FRONTEND) {

            return [
                'current'  => $storeId,
                'resolved' => $storeId
            ];
        }

        /** @var AbstractSyncInterface $lastSyncEntry */
        $lastSyncEntry = SyncRegistry::getLastEntry()['value'];

        if ($lastSyncEntry) {
            if ($lastSyncEntry->getSyncedEntityType() !== QuoteItem::SYNCED_ENTITY_TYPE) {

                /** @var CustomerSyncInterface|OrderSyncInterface|QuoteSyncInterface $lastSyncEntry */
                return [
                    'current'  => $this->storeManager->getStore()->getId(),
                    'resolved' => $lastSyncEntry->getStoreId()
                ];
            } else {
                /** @var AbstractSyncInterface $lastButOneSyncEntry */
                $lastButOneSyncEntry = SyncRegistry::getLastButOneEntry()['value'];

                /** @var QuoteItem $lastSyncEntry */ /** @var Quote $lastButOneSyncEntry*/
                if ($lastButOneSyncEntry->getSyncedEntityType() === Quote::SYNCED_ENTITY_TYPE &&
                    $lastSyncEntry->getQuoteId() === $lastButOneSyncEntry->getQuoteId()) {

                    return [
                        'current'  => $this->storeManager->getStore()->getId(),
                        'resolved' => $lastButOneSyncEntry->getStoreId()
                    ];
                }
            }
        }

        throw new Exception('Can not resolve store for the sync.');
    }

    /**
     * @param callable $callback
     * @param mixed $storeId
     * @param mixed $initialStoreId
     * @return mixed
     */
    public function emulate(callable $callback, $storeId, $initialStoreId)
    {
        if ($initialStoreId === $storeId) {

            return $callback();
        }

        $this->storeManager->setCurrentStore($storeId);
        $result = $callback();
        $this->storeManager->setCurrentStore($initialStoreId);

        return $result;
    }
}
