<?php

namespace Capgemini\FedEx\Plugin;

use Magento\Framework\Registry;
use Magento\Quote\Model\Quote\Address\Rate;
use Magento\Quote\Model\Quote\Address\RateResult\AbstractResult;

class RatePlugin
{
    /**
     * @var Registry
     */
    protected $registry;

    public function __construct(
        Registry $registry
    ) {
        $this->registry = $registry;
    }

    public function afterImportShippingRate(Rate $subject, Rate $result, AbstractResult $rate)
    {
        if ($rate instanceof \Magento\Quote\Model\Quote\Address\RateResult\Method) {
            $overWeightFlag = intval($this->registry->registry('cartonOverWeightFlag'));
            // TODO - do we care about the contiguous US code from M1?

            $method = $rate->getMethod();
            $checkMethods = ['STANDARD_OVERNIGHT', 'FEDEX_2_DAY', 'PRIORITY_OVERNIGHT'];
            if ($overWeightFlag > 0 && in_array($method, $checkMethods)) {
                $result->setPrice(0.00);
            }
        }
        return $result;
    }
}
