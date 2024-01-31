<?php
/**
 * Capgemini_PaymentTerms
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\PaymentTerms\Model;

/**
 * Wholesale Terms payment method
 */
class Wholesale extends \Magento\OfflinePayments\Model\Purchaseorder
{
    const PAYMENT_METHOD_TERMWHOLESALE_CODE = 'termswholesale';

    /**
     * @var bool
     */
    protected $_canAuthorize = true;

    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD_TERMWHOLESALE_CODE;
}
