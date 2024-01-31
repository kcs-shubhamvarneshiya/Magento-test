<?php

namespace Capgemini\CustomHeight\CustomerData;

use Capgemini\CustomHeight\Helper\PriceHeight;
use Magento\Customer\CustomerData\SectionSourceInterface;

class AvailabilityMessage implements SectionSourceInterface
{
    /**
     * @var PriceHeight
     */
    private $helper;

    /**
     * @param PriceHeight $helper
     */
    public function __construct(PriceHeight $helper)
    {
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getSectionData()
    {
        return ["message" => $this->helper->getAvailabilityMessage()];
    }
}
