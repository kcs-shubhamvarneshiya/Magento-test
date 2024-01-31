<?php

namespace Capgemini\CustomHeight\Plugin;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\RequestInterface;

/**
 * Class QuotePlugin
 * @author Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2021 Capgemini, Inc. (www.capgemini.com)
 */
class QuotePlugin
{
    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * QuotePlugin constructor.
     * @param Session $checkoutSession
     * @param RequestInterface $request
     */
    public function __construct(
        Session $checkoutSession,
        RequestInterface $request
    )
    {
        $this->checkoutSession = $checkoutSession;
        $this->request = $request;
    }

    /**
     * @param $subject
     * @param callable $proceed
     * @param $product
     * @return false|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function aroundGetItemByProduct($subject, callable $proceed, $product)
    {
        $currentQuote = $this->checkoutSession->getQuote();
        $customHeightValue = $this->request->getPost('custom_height_value');
        $customHeightPriceFromRequest = $this->request->getPost('custom_height_price');
        if ($customHeightValue) {
             if (($item = $proceed($product))) {
                foreach ($currentQuote->getAllItems() as $item) {
                    if ($item->getProduct()->getId() == $product->getId()) {
                        $customHeightPrice  = $item->getCustomHeightPrice();
                        if ($customHeightValue &&  $customHeightValue == $item->getCustomHeightValue()){
                            return $item;
                        } elseif ((int)$customHeightPriceFromRequest == 0 && !$customHeightPrice){
                            return  $item;
                        }
                    }
                 }
                 return false;
             } else {
                 return false;
             }
        } else {
            if (($item = $proceed($product)) && $item->getCustomHeightValue()){
                return  false;
            }
            return $proceed($product);
        }
    }
}
