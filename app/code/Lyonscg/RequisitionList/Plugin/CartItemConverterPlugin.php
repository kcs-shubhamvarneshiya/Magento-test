<?php

namespace Lyonscg\RequisitionList\Plugin;

use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Api\Data\CartItemExtensionFactory;
use Magento\RequisitionList\Api\Data\RequisitionListItemInterface;
use Magento\RequisitionList\Model\RequisitionListItem\CartItemConverter;

class CartItemConverterPlugin
{
    /**
     * @var CartItemExtensionFactory
     */
    protected $cartItemExtensionFactory;

    public function __construct(
        CartItemExtensionFactory $cartItemExtensionFactory
    ) {
        $this->cartItemExtensionFactory = $cartItemExtensionFactory;
    }

    public function afterConvert(
        CartItemConverter $subject,
        CartItemInterface $result,
        RequisitionListItemInterface $requisitionListItem
    ) {
        $reqExtension = $requisitionListItem->getExtensionAttributes();
        if (!$reqExtension) {
            return $result;
        }
        $cartExtension = $result->getExtensionAttributes() ?? $this->cartItemExtensionFactory->create();

        $cartExtension->setSidemark($reqExtension->getSidemark());
        $cartExtension->setCommentsLineItem($reqExtension->getCommentsLineItem());
        $result->setData('sidemark', $reqExtension->getSidemark());
        $result->setData('comments_line_item', $reqExtension->getCommentsLineItem());

        $result->setExtensionAttributes($cartExtension);
        return $result;
    }
}
