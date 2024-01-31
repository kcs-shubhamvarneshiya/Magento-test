<?php

namespace Lyonscg\SalesPad\Helper;

use Laminas\Mail\Message as Message;
use Laminas\Mail\Transport\Sendmail as SendmailTransport;
use Lyonscg\SalesPad\Api\Data\CustomerSyncInterface;
use Lyonscg\SalesPad\Api\Data\OrderSyncInterface;
use Lyonscg\SalesPad\Api\Data\QuoteSyncInterface;
use Lyonscg\SalesPad\Model\Config;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\RequisitionList\Api\RequisitionListRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class Email extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var RequisitionListRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * Email constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param Config $config
     * @param OrderRepositoryInterface $orderRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param RequisitionListRepositoryInterface $quoteRepository
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        Config $config,
        OrderRepositoryInterface $orderRepository,
        CustomerRepositoryInterface $customerRepository,
        RequisitionListRepositoryInterface $quoteRepository
    ) {
        parent::__construct($context);
        $this->config = $config;
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
        $this->quoteRepository = $quoteRepository;
    }

    protected function _sendErrorEmail($fromAddress, $addresses, $subject, $body)
    {
        if (empty($addresses) || empty($fromAddress)) {
            return;
        }

        $message = new Message();
        $message->setSubject($subject);
        $message->setFrom($fromAddress);
        $first = true;
        foreach ($addresses as $address) {
            if ($first) {
                $message->addTo($address);
                $first = false;
            } else {
                $message->addCc($address);
            }
        }
        $message->setBody($body);

        $transport = new SendmailTransport();
        $transport->send($message);
    }

    public function sendOrderErrorEmail(OrderSyncInterface $orderSync)
    {
        try {
            $this->_sendOrderErrorEmail($orderSync);
        } catch (\Throwable $e) {
        }
    }

    protected function _sendOrderErrorEmail(OrderSyncInterface $orderSync)
    {
        $addresses = $this->config->getOrderErrorEmails();
        $fromEmail = $this->config->getOrderErrorEmailFrom();
        $orderId = strval($orderSync->getOrderId());

        try {
            $order = $this->orderRepository->get($orderSync->getOrderId());
            $orderId = strval($order->getIncrementId());
        } catch (\Exception $e) {
        }

        $subject = __('Order Sync Failure: ' . $orderId);

        $body = __('There was an error syncing order %1 with SalesPad:  Error details: %2', $orderId, $orderSync->getFailures());

        $this->_sendErrorEmail($fromEmail, $addresses, $subject, $body);
    }

    public function sendQuoteErrorEmail(QuoteSyncInterface $quoteSync)
    {
        try {
            $this->_sendQuoteErrorEmail($quoteSync);
        } catch (\Throwable $e) {
        }
    }

    protected function _sendQuoteErrorEmail(QuoteSyncInterface $quoteSync)
    {
        $addresses = $this->config->getOrderErrorEmails();
        $fromEmail = $this->config->getOrderErrorEmailFrom();
        $quoteId = strval($quoteSync->getQuoteId());
        $customerId = strval($quoteSync->getCustomerId());
        $salesDocNum = $quoteSync->getSalesDocNum() ?? 'not defined';
        $name = '';

        try {
            $quote = $this->quoteRepository->get($quoteSync->getQuoteId());
            $name = strval($quote->getName());
        } catch (\Exception $e) {
        }

        $subject = __('Quote Sync Failure: ' . $name . ' (' . $quoteId . ')');

        $body = __(
            'There was an error syncing quote %1, customer %2, sales document %3 with SalesPad:  Error details: %4',
            $quoteId,
            $customerId,
            $salesDocNum,
            $quoteSync->getFailures()
        );

        $this->_sendErrorEmail($fromEmail, $addresses, $subject, $body);
    }

    public function sendCustomerErrorEmail(CustomerSyncInterface $customerSync)
    {
        try {
            $this->_sendCustomerErrorEmail($customerSync);
        } catch (\Throwable $e) {
        }
    }

    protected function _sendCustomerErrorEmail(CustomerSyncInterface $customerSync)
    {
        $addresses = $this->config->getOrderErrorEmails();
        $fromEmail = $this->config->getOrderErrorEmailFrom();
        $customerId = strval($customerSync->getCustomerId());
        $customerEmail = '';

        try {
            $customer = $this->customerRepository->getById($customerSync->getCustomerId());
            $customerEmail = strval($customer->getEmail());
        } catch (\Exception $e) {
        }

        $subject = __('Customer Sync Failure: ' . $customerEmail . ' (' . $customerId . ')');

        $body = __('There was an error syncing customer %1 with SalesPad:  Error details: %2', $customerId, $customerSync->getFailures());

        $this->_sendErrorEmail($fromEmail, $addresses, $subject, $body);
    }
}
