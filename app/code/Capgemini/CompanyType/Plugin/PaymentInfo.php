<?php

namespace Capgemini\CompanyType\Plugin;

use Magento\Framework\Serialize\SerializerInterface as Serializer;
use Magento\Sales\Model\Order\Payment\Info as OrigInfo;
use Magento\Framework\Serialize\SerializerInterface;

class PaymentInfo
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    public function __construct(
        Serializer $serializer
    ) {
        $this->serializer = $serializer;
    }

    protected $requiredInfoFields = [
        "maskedPan",
        "cardType",
        "expDate",
        "method_title",
        "cardNumber",
    ];

    /**
     * Include the $requiredInfoFields in all requests
     *
     * Request by suchowdhury@visualcomfortco.com and jduke@visualcomfort.com add the required info fields to the
     * payment_additional_info for all orders regardless of payment type.
     *
     * In addition, it is required to have card data for maskedPan and expDate for Cybersource stored card / vault
     * payments in order to match Cybersource credit card payments.
     *
     * This is to facilitate order processing on the client application side.
     *
     * See: OrigInfo::getAdditionalInformation()
     *
     * @param OrigInfo $subject
     * @param array|null|mixed $results
     * @return mixed
     */
    public function afterGetAdditionalInformation(OrigInfo $subject, $result, $key = null)
    {
        if (null !== $key) {
            return $result;
        }

        foreach ($this->requiredInfoFields as $field) {
            if (!isset($result[$field])) {
                $result[$field] = "";

                // Add expDate if needed
                if ($field === 'expDate' && $subject->getMethod() === 'chcybersource') {
                    $result[$field] = $this->getExpDate($subject);
                }

                // Add maskedPan if needed. Use cardBin and maskedCc if available
                if ($field === 'maskedPan' && $subject->getMethod() === 'chcybersource') {
                    $result[$field] = $this->getMaskedPan($subject);
                }
            }
        }

        return $result;
    }

    /**
     * @param OrigInfo $subject
     * @return string
     */
    protected function getExpDate(OrigInfo $subject)
    {
        return $subject->getCcExpMonth() . '-' . $subject->getCcExpYear();
    }

    /**
     * @param OrigInfo $subject
     * @return string
     */
    protected function getMaskedPan(OrigInfo $subject)
    {
        $extensionAttributes = $subject->getExtensionAttributes();
        $vault = $extensionAttributes->getVaultPaymentToken();

        $cardBin = '';
        $maskedCc = $subject->getCcLast4();

        if ($vault && isset($vault['details'])) {
            try {
                $details = $this->serializer->unserialize($vault['details']);
                $cardBin = $details['cardBIN'] ?? '';
                $maskedCc = $details['maskedCC'] ?? $subject->getCcLast4();
            } catch (\Exception $e) {

            }
        }
        $maskedPan = str_pad($cardBin, 12, 'X', STR_PAD_RIGHT) . $maskedCc;
        return $maskedPan;
    }
}
