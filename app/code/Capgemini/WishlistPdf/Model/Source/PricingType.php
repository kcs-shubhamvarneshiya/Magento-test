<?php
/**
 * Capgemini_WishlistPdf
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\WishlistPdf\Model\Source;

/**
 * Pricing type source
 */
class PricingType implements \Magento\Framework\Option\ArrayInterface
{
    public const WITH_PRICING = 1;
    public const WITHOUT_PRICING = 2;
    public const TRADE_PRICING = 3;
    public const WITH_MARKUP_PRICE = 4;

    protected $options;

    public function __construct()
    {
        $this->options = [
            self::WITH_PRICING => __('With Pricing'),
            self::WITHOUT_PRICING => __('Without Pricing'),
            self::TRADE_PRICING => __('With Trade Pricing'),
            self::WITH_MARKUP_PRICE => __('With % Markup Price'),
        ];
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        foreach ($this->options as $label => $value) {
            $options[] = ['label' => $label, 'value' => $value];
        }
        return $options;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return $this->options;
    }
}