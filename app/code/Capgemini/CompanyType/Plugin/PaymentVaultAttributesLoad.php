<?php

namespace Capgemini\CompanyType\Plugin;

use Magento\Sales\Api\Data\OrderPaymentExtensionInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Vault\Api\PaymentTokenManagementInterface;
use Magento\Sales\Api\Data\OrderPaymentExtensionFactory;
use Magento\Vault\Api\Data\PaymentTokenInterface;
use Magento\Vault\Model\PaymentTokenFactory;

class PaymentVaultAttributesLoad
{
    /**
     * @var OrderPaymentExtensionFactory
     */
    protected $paymentExtensionFactory;

    /**
     * @var PaymentTokenManagementInterface
     */
    protected $paymentTokenManagement;

    /**
     * @var PaymentTokenFactory
     */
    protected $paymentTokenFactory;

    /**
     * @param OrderPaymentExtensionFactory $paymentExtensionFactory
     * @param PaymentTokenManagementInterface $paymentTokenManagement
     * @param PaymentTokenFactory $paymentTokenFactory
     */
    public function __construct(
        OrderPaymentExtensionFactory $paymentExtensionFactory,
        PaymentTokenManagementInterface $paymentTokenManagement,
        PaymentTokenFactory $paymentTokenFactory,
    ) {
        $this->paymentExtensionFactory = $paymentExtensionFactory;
        $this->paymentTokenManagement = $paymentTokenManagement;
        $this->paymentTokenFactory = $paymentTokenFactory;
    }


    /**
     * Load vault payment extension attribute to order/payment entity
     *
     * If the vault payment data does not exist for the order payment, add the vault payment data to the order
     * response with null data.
     *
     * This is to facilitate order processing on the client application side.
     *
     * @param OrderPaymentInterface $payment
     * @param OrderPaymentExtensionInterface|null $paymentExtension
     * @return OrderPaymentExtensionInterface
     */
    public function afterGetExtensionAttributes(
        OrderPaymentInterface $payment,
        OrderPaymentExtensionInterface $paymentExtension = null
    ) {
        if ($paymentExtension === null) {
            $paymentExtension = $this->paymentExtensionFactory->create();
        }

        // If there is no payment token, create an empty one
        $paymentToken = $paymentExtension->getVaultPaymentToken();
        if ($paymentToken === null) {
            $paymentToken = $this->paymentTokenFactory->create(['data' => ['gateway_token' => '']]);
            if ($paymentToken instanceof PaymentTokenInterface) {
                $paymentExtension->setVaultPaymentToken($paymentToken);
            }
            $payment->setExtensionAttributes($paymentExtension);
        }

        return $paymentExtension;
    }

}
