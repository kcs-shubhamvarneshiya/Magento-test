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

use Magento\Checkout\Api\GuestPaymentInformationManagementInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Quote\Model\QuoteRepository;
use Psr\Log\LoggerInterface;

class GuestPaymentInformationManagementPlugin
{
    /**
     * @var QuoteIdMaskFactory
     */
    protected $quoteIdMaskFactory;

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
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     * @param LoggerInterface $logger
     * @param Json $serializer
     */
    public function __construct(
        QuoteRepository    $quoteRepository,
        QuoteIdMaskFactory $quoteIdMaskFactory,
        LoggerInterface    $logger,
        Json $serializer
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    /**
     * @param GuestPaymentInformationManagementInterface $subject
     * @param $cartId
     * @param $email
     * @param PaymentInterface $paymentMethod
     * @param AddressInterface|null $billingAddress
     * @return void
     */
    public function beforeSavePaymentInformation(
        GuestPaymentInformationManagementInterface $subject,
                                                   $cartId,
                                                   $email,
        PaymentInterface                           $paymentMethod,
        AddressInterface                           $billingAddress = null
    ): void
    {
        try {
            $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
            $quote = $this->quoteRepository->getActive($quoteIdMask->getQuoteId());
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
