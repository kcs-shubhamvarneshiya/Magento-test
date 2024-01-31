<?php
/**
 * Capgemini_AvataxExempt
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\AvataxExempt\Plugin;

use ClassyLlama\AvaTax\Framework\Interaction\Tax\Get;
use Magento\Customer\Api\Data\GroupInterface;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Store\Model\ScopeInterface;
use Magento\Tax\Api\Data\QuoteDetailsInterface;

/**
 * Tax Exempt plugin
 */
class TaxExempt
{
    const XML_PATH_EXEMPT_ENABLED = 'tax/avatax_advanced/enable_customer_group_exemption';
    const XML_PATH_EXEMPT_GROUPS = 'tax/avatax_advanced/exempt_customer_groups';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var \Capgemini\AvataxExempt\Model\EmptyTax
     */
    protected $emptyTax;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Capgemini\AvataxExempt\Model\EmptyTax $emptyTax
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Capgemini\AvataxExempt\Model\EmptyTax $emptyTax
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->emptyTax = $emptyTax;
    }

    /**
     * Disble tax calculation for selected customer groups
     *
     * @param Get $subject
     * @param callable $proceed
     * @param Quote $quote
     * @param QuoteDetailsInterface $taxQuoteDetails
     * @param QuoteDetailsInterface $baseTaxQuoteDetails
     * @param ShippingAssignmentInterface $shippingAssignment
     * @return array
     */
    public function aroundGetTaxDetailsForQuote(
        Get $subject,
        callable $proceed,
        Quote $quote,
        QuoteDetailsInterface $taxQuoteDetails,
        QuoteDetailsInterface $baseTaxQuoteDetails,
        ShippingAssignmentInterface $shippingAssignment
    ) {
        $storeId = $quote->getStoreId();
        $isEnabled = $this->scopeConfig->getValue(self::XML_PATH_EXEMPT_ENABLED, ScopeInterface::SCOPE_STORE, $storeId);
        if ($isEnabled) {
            $groups = $this->scopeConfig->getValue(self::XML_PATH_EXEMPT_GROUPS, ScopeInterface::SCOPE_STORE, $storeId);
            $groupId = $quote->getCustomerGroupId();
            if (!is_null($groups)) {
                $groups = explode(',', $groups);
                if (in_array($groupId, $groups) || in_array(GroupInterface::CUST_GROUP_ALL, $groups)) {
                    return [
                        $this->emptyTax->getEmptyTaxDetails($taxQuoteDetails),
                        $this->emptyTax->getEmptyTaxDetails($baseTaxQuoteDetails),
                        []
                    ];
                }
            }
        }
        return $proceed($quote, $taxQuoteDetails, $baseTaxQuoteDetails, $shippingAssignment);
    }
}