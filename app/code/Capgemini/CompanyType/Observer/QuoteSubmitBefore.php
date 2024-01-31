<?php
/**
 * Capgemini_CompanyType
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\CompanyType\Observer;

use Capgemini\CompanyType\Model\Config;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Observer for the event sales_model_service_quote_submit_before to set company_type value
 */
class QuoteSubmitBefore implements ObserverInterface
{
    /**
     * @var Config
     */
    protected $companyConfig;

    public function __construct(Config $companyConfig) {
        $this->companyConfig = $companyConfig;
    }

    /**
     * Set company type
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        /* @var $order \Magento\Sales\Model\Order */
        $order = $observer->getEvent()->getOrder();

        /* @var $quote \Magento\Quote\Model\Quote */
        $quote = $observer->getEvent()->getQuote();
        $customer = $quote->getCustomer();

        $order->setCompanyType($this->companyConfig->getCustomerCompanyType($customer));
    }
}
