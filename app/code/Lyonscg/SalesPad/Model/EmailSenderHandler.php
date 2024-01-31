<?php

namespace Lyonscg\SalesPad\Model;

use Magento\Sales\Model\Order\Email\Container\IdentityInterface;

class EmailSenderHandler extends \Magento\Sales\Model\EmailSenderHandler
{
    /**
     * @var IdentityInterface
     */
    private $identityContainer;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param \Magento\Sales\Model\Order\Email\Sender $emailSender
     * @param \Magento\Sales\Model\ResourceModel\EntityAbstract $entityResource
     * @param \Magento\Sales\Model\ResourceModel\Collection\AbstractCollection $entityCollection
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $globalConfig
     * @param IdentityInterface|null $identityContainer
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @throws \InvalidArgumentException
     */
    public function __construct(
        \Magento\Sales\Model\Order\Email\Sender $emailSender,
        \Magento\Sales\Model\ResourceModel\EntityAbstract $entityResource,
        \Magento\Sales\Model\ResourceModel\Collection\AbstractCollection $entityCollection,
        \Magento\Framework\App\Config\ScopeConfigInterface $globalConfig,
        IdentityInterface $identityContainer = null,
        \Magento\Store\Model\StoreManagerInterface $storeManager = null
    ) {
        parent::__construct(
            $emailSender,
            $entityResource,
            $entityCollection,
            $globalConfig,
            $identityContainer,
            $storeManager
        );
        $this->identityContainer = $identityContainer ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Sales\Model\Order\Email\Container\NullIdentity::class);
        $this->storeManager = $storeManager ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Store\Model\StoreManagerInterface::class);

    }

    /**
     * Handles asynchronous email sending
     * @return void
     */
    public function sendEmails()
    {
        if ($this->globalConfig->getValue('sales_email/general/async_sending')) {
            $this->entityCollection->addFieldToFilter('send_email', ['eq' => 1]);
            $this->entityCollection->addFieldToFilter('email_sent', ['null' => true]);
            $this->entityCollection->setPageSize(
                $this->globalConfig->getValue('sales_email/general/sending_limit')
            );

            /** @var \Magento\Store\Api\Data\StoreInterface[] $stores */
            $stores = $this->getStores(clone $this->entityCollection);

            /** @var \Magento\Store\Model\Store $store */
            foreach ($stores as $store) {
                $this->identityContainer->setStore($store);
                if (!$this->identityContainer->isEnabled()) {
                    continue;
                }
                $entityCollection = clone $this->entityCollection;
                $entityCollection->addFieldToFilter('store_id', $store->getId());

                // per di.xml, these will be orders
                /** @var \Magento\Sales\Model\Order $item */
                foreach ($entityCollection->getItems() as $item) {

                    // CLMI-807 - skip sending the email if the order has no sales doc num
                    if (!$this->_hasSalesDocNum($item)) {
                        continue;
                    }

                    if ($this->emailSender->send($item, true)) {
                        $this->entityResource->save(
                            $item->setEmailSent(true)
                        );
                    }
                }
            }
        }
    }

    /**
     * @param $order \Magento\Sales\Model\Order
     */
    protected function _hasSalesDocNum($order)
    {
        $extAttrs = $order->getExtensionAttributes();
        $salesDocNum = false;
        if ($extAttrs) {
            $salesDocNum = trim($extAttrs->getSalespadSalesDocNum() ?? '');
        }
        if (!$salesDocNum) {
            // in case extension attributes were not loaded
            $salesDocNum = trim($order->getSalespadSalesDocNum() ?? '');
        }
        return $salesDocNum;
    }

    /**
     * Get stores for given entities.
     *
     * @param ResourceModel\Collection\AbstractCollection $entityCollection
     * @return \Magento\Store\Api\Data\StoreInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getStores(
        \Magento\Sales\Model\ResourceModel\Collection\AbstractCollection $entityCollection
    ): array {
        $stores = [];

        $entityCollection->addAttributeToSelect('store_id')->getSelect()->group('store_id');
        /** @var \Magento\Sales\Model\EntityInterface $item */
        foreach ($entityCollection->getItems() as $item) {
            /** @var \Magento\Store\Model\StoreManagerInterface $store */
            $store = $this->storeManager->getStore($item->getStoreId());
            $stores[$item->getStoreId()] = $store;
        }

        return $stores;
    }
}
