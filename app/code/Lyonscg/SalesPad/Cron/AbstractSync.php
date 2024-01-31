<?php


namespace Lyonscg\SalesPad\Cron;


use Lyonscg\SalesPad\Api\SyncRepositoryInterface;
use Lyonscg\SalesPad\Helper\Email as EmailHelper;
use Lyonscg\SalesPad\Model\Api\Logger;
use Lyonscg\SalesPad\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

abstract class AbstractSync
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var SyncRepositoryInterface
     */
    protected $syncRepository;

    /**
     * @var EmailHelper
     */
    protected $emailHelper;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * AbstractSync constructor.
     * @param Config $config
     * @param SyncRepositoryInterface $syncRepository
     * @param EmailHelper $emailHelper
     * @param Logger $logger
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Config $config,
        SyncRepositoryInterface $syncRepository,
        EmailHelper $emailHelper,
        Logger $logger,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->config = $config;
        $this->syncRepository = $syncRepository;
        $this->emailHelper = $emailHelper;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Sync entities (customers, orders, quotes)
     */
    public function execute()
    {
        $stores = $this->scopeConfig->getValue(\Magento\Customer\Model\Config\Share::XML_PATH_CUSTOMER_ACCOUNT_SHARE)
            ? $this->storeManager->getStores(true)
            : null;

        if (!$stores) {
            $this->processSync();

            return;
        }

        try {
            $initialStore = $this->storeManager->getStore();
        } catch (NoSuchEntityException $e) {
            $initialStore = false;
        }

        foreach ($stores as $store) {
            $this->storeManager->setCurrentStore($store);
            $this->processSync($store->getId());
        }

        if ($initialStore !== false) {
            $this->storeManager->setCurrentStore($initialStore);
        }
    }

    /**
     * Process synchronization of entities
     * belonging to the current store.
     *
     * @param int|null $storeId
     */
    protected abstract function processSync(int $storeId = null);
}
