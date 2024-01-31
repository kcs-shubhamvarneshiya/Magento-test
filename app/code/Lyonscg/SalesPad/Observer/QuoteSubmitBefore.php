<?php

namespace Lyonscg\SalesPad\Observer;

use Exception;
use Lyonscg\SalesPad\Helper\Sales as SalesHelper;
use Lyonscg\SalesPad\Model\Api\Logger;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;

class QuoteSubmitBefore implements ObserverInterface
{
    /**
     * @var OrderExtensionFactory
     */
    protected $orderExtensionFactory;

    protected $salesHelper;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        SalesHelper $salesHelper,
        Logger $logger,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->salesHelper = $salesHelper;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->_addQuoteNum($observer);
        $this->_applyCustomerNumber($observer);
        return $this;
    }

    protected function _addQuoteNum(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getData('order');
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getData('quote');

        $quoteNum = $quote->getSalespadQuoteNum();

        $order->setSalespadQuoteNum($quoteNum);
        $extensionAttributes = $this->_getExtensionAttributes($order);
        $extensionAttributes->setSalespadQuoteNum($quoteNum);
        $order->setExtensionAttributes($extensionAttributes);
    }

    /**
     * @throws Exception
     */
    protected function _applyCustomerNumber(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getData('order');
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getData('quote');

        $email = $order->getCustomerEmail();
        if (!$email) {
            $this->logger->debug('before order place, order has no email.  Quote id: ' . $quote->getId());
            return;
        }
        $customerEntityId = $order->getCustomerId();
        try {
            $websiteId = $this->storeManager->getStore($order->getStoreId())->getWebsiteId();
        } catch (Exception $exception) {
            $websiteId = null;
        }
        $customerNum = $this->salesHelper->getSalespadCustomerNumberByIdentifiers($customerEntityId, $email, $websiteId);
        if ($customerNum === false) {
            // try to create a new customer
            $name = $order->getCustomerName();
            $customer = $quote->getCustomer();
            $customerNum = $this->salesHelper->createCustomer($email, $name, $customer);
            if ($customerNum === false) {
                $this->logger->debug('could not create SalesPad customer for quote id: ' . $quote->getId());
                return;
            }
        }
        $extensionAttributes = $this->_getExtensionAttributes($order);
        $extensionAttributes->setSalesPadCustomerNum($customerNum);
        $order->setExtensionAttributes($extensionAttributes);
    }

    protected function _getExtensionAttributes(\Magento\Sales\Model\Order $order)
    {
        $extensionAttributes = $order->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->orderExtensionFactory->create();
        $order->setExtensionAttributes($extensionAttributes);
        return $extensionAttributes;
    }
}
