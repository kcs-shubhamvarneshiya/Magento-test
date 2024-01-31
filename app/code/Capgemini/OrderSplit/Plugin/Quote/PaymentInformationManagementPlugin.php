<?php
/**
 * Capgemini_OrderSplit
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\OrderSplit\Plugin\Quote;

use Magento\Checkout\Api\PaymentInformationManagementInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Model\QuoteRepository;
use Psr\Log\LoggerInterface;

class PaymentInformationManagementPlugin
{
    /**
     * @var QuoteRepository
     */
    protected $quoteRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var Json
     */
    protected $serializer;

    /**
     * @param QuoteRepository $quoteRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        QuoteRepository    $quoteRepository,
        LoggerInterface    $logger,
        Json $serializer
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    /**
     * @param PaymentInformationManagementInterface $subject
     * @param $cartId
     * @param PaymentInterface $paymentMethod
     * @param AddressInterface|null $billingAddress
     * @return void
     */
    public function beforeSavePaymentInformation(
        PaymentInformationManagementInterface $subject,
                                              $cartId,
        PaymentInterface                      $paymentMethod,
        AddressInterface                      $billingAddress = null
    ): void
    {
        try {
            $quote = $this->quoteRepository->getActive($cartId);
            $poNumbers = $paymentMethod->getExtensionAttributes()->getCustomPoNumbers();
            $customPromoCodes = $paymentMethod->getExtensionAttributes()->getCustomPromoCodes();
            if ($poNumbers) {
                $quote->setCustomPoNumbers($this->serializer->serialize($poNumbers));
            }
            if ($customPromoCodes) {
                $quote->setCustomPromoCodes($this->serializer->serialize($customPromoCodes));
            }
            if ($poNumbers || $customPromoCodes) {
                $this->quoteRepository->save($quote);
            }
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}
