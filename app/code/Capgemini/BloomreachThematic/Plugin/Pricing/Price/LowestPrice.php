<?php

namespace Capgemini\BloomreachThematic\Plugin\Pricing\Price;

use Capgemini\BloomreachThematic\Model\TechnicalProduct;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\ConfigurableProduct\Pricing\Price\LowestPriceOptionsProvider ;
use Magento\Customer\Model\Session as CustomerSession;

class LowestPrice
{
    private CustomerSession $customerSession;

    public function __construct(CustomerSession $customerSession)
    {
        $this->customerSession = $customerSession;
    }

    public function aroundGetProducts(LowestPriceOptionsProvider $subject, callable $proceed, ProductInterface $product)
    {
        try {
            $customerGroupId = (int) $this->customerSession->getCustomerGroupId();
        } catch (\Exception $exception) {
            $customerGroupId = null;
        }

        if (!$product->getData(TechnicalProduct::IS_THEMATIC_PRODUCT_DATA_KEY)
            || $customerGroupId !== 0
        ) {

            return $proceed($product);
        } else {
            $simples = $product->getTypeInstance()->getUsedProducts($product);
            $lowestSalesPrice = null;
            $winnerIndex = null;
            foreach ($simples as $index => $simple) {
                $price = $simple->getPrice();
                $specialPrice = $simple->getSpecialPrice();
                if ($specialPrice < $price) {
                    $lowestSalesPrice = isset($lowestSalesPrice)
                        ? min($specialPrice, $lowestSalesPrice)
                        : $specialPrice;
                    if ($lowestSalesPrice === $specialPrice) {
                        $winnerIndex = $index;
                    }
                }
            }

            return $winnerIndex !== null ? [$simples[$winnerIndex]] : [];
        }
    }
}
