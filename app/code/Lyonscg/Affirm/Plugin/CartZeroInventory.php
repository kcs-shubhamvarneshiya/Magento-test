<?php

namespace Lyonscg\Affirm\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Model\Quote;

class CartZeroInventory
{
    /**
     * @var CartInterface|Quote
     */
    private $quote;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var StockRegistryInterface
     */
    private $stockRegistry;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        ProductRepositoryInterface $productRepository,
        StockRegistryInterface $stockRegistry
    ) {
        try {
            $this->quote = $checkoutSession->getQuote();
        } catch (NoSuchEntityException | LocalizedException $e) {
            $this->quote = null;
        }
        $this->stockRegistry = $stockRegistry;
        $this->productRepository = $productRepository;
    }

    /**
     * Adds to Cart on the Storefront items with zero inventory.
     *
     * @param \Magento\Checkout\CustomerData\Cart $subject
     * @param array $result
     * @return array
     */
    public function afterGetSectionData(\Magento\Checkout\CustomerData\Cart $subject, array $result)
    {
        if ($this->quote === null) {

            return $result;
        }

        $zeros = [];
        $items = $this->quote->getItems();

        if (!$items) {

            return $result;
        }

        foreach ($items as $item) {
            /** @var $item CartItemInterface */
            $itemSimpleProdSku = $item ->getSku();
            try {
                $itemSimpleProdId = $this->productRepository->get($itemSimpleProdSku)->getId();
            } catch (NoSuchEntityException $e) {

                continue;
            }
            $stockItem  =  $this ->stockRegistry->getStockItem($itemSimpleProdId);

            if ($stockItem->getQty() <= 0) {
                $zeros[] = $item->getItemId();
            }
        }

        $result['zero_inventory_items_ids'] = $zeros;

        return $result;
    }
}
