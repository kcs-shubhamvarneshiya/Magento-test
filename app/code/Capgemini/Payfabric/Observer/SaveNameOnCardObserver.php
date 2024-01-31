<?php
/**
 * Capgemini_Payfabric
 *
 * @category   Capgemini
 * @author    Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2020 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\Payfabric\Observer;

use Capgemini\Payfabric\Helper\IgnoreValidatedRecaptcha;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Capgemini\Payfabric\Model\Payfabric;

class SaveNameOnCardObserver implements ObserverInterface
{
    /**
     * @var \Magento\Webapi\Controller\Rest\InputParamsResolver
     */
    protected $_inputParamsResolver;
    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $_quoteRepository;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    /**
     * @var \Magento\Framework\App\State
     */
    protected $_state;

    /**
     * SaveNameOnCardObserver constructor.
     * @param \Magento\Webapi\Controller\Rest\InputParamsResolver $inputParamsResolver
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\App\State $state
     */
    public function __construct(\Magento\Webapi\Controller\Rest\InputParamsResolver $inputParamsResolver,
                                \Magento\Quote\Model\QuoteRepository $quoteRepository,
                                \Psr\Log\LoggerInterface $logger, \Magento\Framework\App\State $state)
    {
        $this->_inputParamsResolver = $inputParamsResolver;
        $this->_quoteRepository = $quoteRepository;
        $this->logger = $logger;
        $this->_state = $state;
    }

    /**
     * @param EventObserver $observer
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(EventObserver $observer)
    {
       try {
           $inputParams = IgnoreValidatedRecaptcha::wrap($this->_inputParamsResolver, 'resolve');
       } catch (\Exception $e) {
           return;
       }
        if ($this->_state->getAreaCode() != \Magento\Framework\App\Area::AREA_ADMINHTML) {
            foreach ($inputParams as $inputParam) {
                if ($inputParam instanceof \Magento\Quote\Model\Quote\Payment) {
                    $paymentData = $inputParam->getData('additional_data');
                    $paymentOrder = $observer->getEvent()->getPayment();
                    $order = $paymentOrder->getOrder();
                    $quote = $this->_quoteRepository->get($order->getQuoteId());
                    $paymentQuote = $quote->getPayment();
                    $method = $paymentQuote->getMethodInstance()->getCode();
                    if ($method == Payfabric::METHOD_CODE) {
                        if (isset($paymentData['name_on_card'])) {
                            $paymentQuote->setData('cc_owner', $paymentData['name_on_card']);
                            $paymentOrder->setData('cc_owner', $paymentData['name_on_card']);
                        }
                    }
                }
            }
        }
    }
}
