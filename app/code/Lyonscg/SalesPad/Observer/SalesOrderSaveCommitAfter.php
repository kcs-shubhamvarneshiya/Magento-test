<?php


namespace Lyonscg\SalesPad\Observer;

use Capgemini\CompanyType\Model\Config;
use Lyonscg\SalesPad\Api\SyncRepositoryInterface;
use Lyonscg\SalesPad\Model\Api\Logger;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class SalesOrderSaveCommitAfter implements ObserverInterface
{
    /**
     * @var SyncRepositoryInterface
     */
    protected $syncRepository;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Config
     */
    protected $companyTypeConfig;

    /**
     * CustomerSaveCommitAfter constructor.
     * @param SyncRepositoryInterface $syncRepository
     */
    public function __construct(
        SyncRepositoryInterface $syncRepository,
        Logger $logger,
        Config $companyTypeConfig
    ) {
        $this->syncRepository = $syncRepository;
        $this->logger = $logger;
        $this->companyTypeConfig = $companyTypeConfig;
    }

    /**
     * @param EventObserver $observer
     */
    public function execute(EventObserver $observer)
    {
        $order = $observer->getEvent()->getOrder();
        if ($order->getData('_salespad_no_sync')) {
            return;
        }
        //Exclude wholesale orders from Salespad export
        if ($this->companyTypeConfig
            ->getCompanyTypeByCustomerId(
                $order->getCustomerId(),
                $order->getStoreId()) == Config::WHOLESALE) {
            return;
        }
        $extensionAttribues = $order->getExtensionAttributes();
        if ($extensionAttribues && $extensionAttribues->getSalesPadSalesDocNum()) {
            // order already has a salespad number, so it does not need to be synced
            return;
        }
        try {
            $this->syncRepository->addOrder($order);
        } catch (\Exception $e) {
            $this->logger->debug(__('Error creating sync entry for order %1: %2', $order->getId(), strval($e)));
        }
    }
}
