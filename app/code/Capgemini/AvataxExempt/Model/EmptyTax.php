<?php
/**
 * Capgemini_AvataxExempt
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\AvataxExempt\Model;

use ClassyLlama\AvaTax\Framework\Interaction\Rest\Tax\ResultFactory as TaxResultFactory;

/**
 * Class to prepare empty tax details based on quote tax details
 */
class EmptyTax
{
    /**
     * @var \Magento\Tax\Api\Data\TaxDetailsInterfaceFactory
     */
    protected $taxDetailsFactory;

    /**
     * @var \Magento\Tax\Api\Data\TaxDetailsItemInterfaceFactory
     */
    protected $taxDetailsItemFactory;

    /**
     * @param \Magento\Tax\Api\Data\TaxDetailsInterfaceFactory $taxDetailsFactory
     * @param \Magento\Tax\Api\Data\TaxDetailsItemInterfaceFactory $taxDetailsItemFactory
     */
    public function __construct(
        \Magento\Tax\Api\Data\TaxDetailsInterfaceFactory $taxDetailsFactory,
        \Magento\Tax\Api\Data\TaxDetailsItemInterfaceFactory $taxDetailsItemFactory
    ) {
        $this->taxDetailsFactory = $taxDetailsFactory;
        $this->taxDetailsItemFactory = $taxDetailsItemFactory;
    }

    /**
     * Convert quote object to empty tax
     *
     * @param \Magento\Tax\Api\Data\QuoteDetailsInterface $quoteDetails
     * @return \Magento\Tax\Api\Data\TaxDetailsInterface
     */
    public function getEmptyTaxDetails(\Magento\Tax\Api\Data\QuoteDetailsInterface $quoteDetails)
    {

        $details = $this->taxDetailsFactory->create();

        $subtotal = 0;
        $items = [];

        foreach ($quoteDetails->getItems() as $quoteItem) {
            $taxItem = $this->getEmptyDetailsTaxItem($quoteItem);
            $subtotal += $taxItem->getRowTotal();
            $items[$taxItem->getCode()] = $taxItem;
        }

        $details->setSubtotal($subtotal)
            ->setTaxAmount(0)
            ->setDiscountTaxCompensationAmount(0)
            ->setAppliedTaxes([])
            ->setItems($items);

        return $details;
    }

    /**
     * Get empty tax details for item
     *
     * @param \Magento\Tax\Api\Data\QuoteDetailsItemInterface $quoteDetailsItem
     * @return \Magento\Tax\Api\Data\TaxDetailsItemInterface
     */
    private function getEmptyDetailsTaxItem(\Magento\Tax\Api\Data\QuoteDetailsItemInterface $quoteDetailsItem)
    {
        $taxDetailsItem = $this->taxDetailsItemFactory->create();

        $rowTotal = (
            $quoteDetailsItem->getUnitPrice()
            * $quoteDetailsItem->getQuantity()
        );

        $taxDetailsItem
            ->setCode($quoteDetailsItem->getCode())
            ->setType($quoteDetailsItem->getType())
            ->setRowTax(0)
            ->setPrice($quoteDetailsItem->getUnitPrice())
            ->setPriceInclTax($quoteDetailsItem->getUnitPrice())
            ->setRowTotal($rowTotal)
            ->setRowTotalInclTax($rowTotal)
            ->setDiscountTaxCompensationAmount(0)
            ->setDiscountAmount($quoteDetailsItem->getDiscountAmount())
            ->setAssociatedItemCode($quoteDetailsItem->getAssociatedItemCode())
            ->setTaxPercent(0)
            ->setAppliedTaxes([]);

        return $taxDetailsItem;
    }
}