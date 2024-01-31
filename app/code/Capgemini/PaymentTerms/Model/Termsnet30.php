<?php

namespace Capgemini\PaymentTerms\Model;

class Termsnet30 extends \Magento\OfflinePayments\Model\Purchaseorder
{
    const PAYMENT_METHOD_TERMSNET30_CODE = 'termsnet30';

    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD_TERMSNET30_CODE;
}
