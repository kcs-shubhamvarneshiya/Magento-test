<?php

namespace Capgemini\CustomHeight\Plugin;

use Magento\Quote\Api\Data\TotalsItemInterface;
use Magento\Quote\Model\Cart\Totals\ItemConverter;
use Magento\Quote\Api\Data\TotalsItemExtensionInterfaceFactory;
use Magento\Quote\Model\Quote\Item;

class TotalsItemConverterPlugin
{
    /**
     * @var TotalsItemExtensionInterfaceFactory
     */
    private $totalsItemExtensionFactory;

    public function __construct(
        TotalsItemExtensionInterfaceFactory $totalsItemExtensionFactory
    ) {
        $this->totalsItemExtensionFactory = $totalsItemExtensionFactory;
    }

    /**
     * @param ItemConverter $subject
     * @param TotalsItemInterface $totalsItem
     * @param Item $item
     */
    public function afterModelToDataObject(ItemConverter $subject, TotalsItemInterface $totalsItem, Item $item): TotalsItemInterface
    {
        try {
            $extensionAttributes = $totalsItem->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ?? $this->totalsItemExtensionFactory->create();
            $extensionAttributes->setCustomHeightValue($item->getExtensionAttributes()->getCustomHeightValue());
            $totalsItem->setExtensionAttributes($extensionAttributes);
        } catch (\Exception $e) {

        }
        return $totalsItem;
    }
}
