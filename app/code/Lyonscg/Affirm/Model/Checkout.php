<?php

namespace Lyonscg\Affirm\Model;

class Checkout extends \Astound\Affirm\Model\Checkout
{
    /**
     * Place order based on prepared quote
     */
    public function place($token)
    {
        if (!$this->quote->getGrandTotal()) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __(
                    'Affirm can\'t process orders with a zero balance due. '
                    . 'To finish your purchase, please go through the standard checkout process.'
                )
            );
        }
        if (!$token) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __(
                    'Token is absent, some problem with response from Affirm happened.'
                )
            );
        }
        $this->initToken($token);
        if ($this->getCheckoutMethod() == \Magento\Checkout\Model\Type\Onepage::METHOD_GUEST) {
            $this->prepareGuestQuote();
        }
        $this->quote->collectTotals();
        $this->ignoreAddressValidation();
        $this->order = $this->quoteManagement->submit($this->quote);

        switch ($this->order->getState()) {
            // even after placement paypal can disallow to authorize/capture, but will wait until bank transfers money
            case \Magento\Sales\Model\Order::STATE_PENDING_PAYMENT:
                // TODO
                break;
            // regular placement, when everything is ok
            case \Magento\Sales\Model\Order::STATE_PROCESSING:
            case \Magento\Sales\Model\Order::STATE_COMPLETE:
            case \Magento\Sales\Model\Order::STATE_PAYMENT_REVIEW:
                //https://lyonscg.atlassian.net/browse/CRC-275
                //$this->orderSender->send(($this->order));
                $this->checkoutSession->start();
                break;
            default:
                break;
        }
    }
}
