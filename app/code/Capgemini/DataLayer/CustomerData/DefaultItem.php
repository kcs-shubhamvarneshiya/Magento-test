<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Capgemini\DataLayer\CustomerData;

use Magento\Framework\App\ObjectManager;
use Magento\Catalog\Model\Product\Configuration\Item\ItemResolverInterface;

/**
 * Default item
 */
class DefaultItem extends \Magento\Checkout\CustomerData\DefaultItem
{
    /**
     * @var \Capgemini\DataLayer\Helper\Data
     */
    protected $helper;

    /**
     * @var mixed
     */
    protected $escaper;

    /**
     * @var mixed
     */
    protected $itemResolver;

    /**
     * DefaultItem constructor.
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\Msrp\Helper\Data $msrpHelper
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Catalog\Helper\Product\ConfigurationPool $configurationPool
     * @param \Magento\Checkout\Helper\Data $checkoutHelper
     * @param \Capgemini\DataLayer\Helper\Data $helper
     * @param \Magento\Framework\Escaper|null $escaper
     * @param ItemResolverInterface|null $itemResolver
     */
    public function __construct(
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Msrp\Helper\Data $msrpHelper,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Catalog\Helper\Product\ConfigurationPool $configurationPool,
        \Magento\Checkout\Helper\Data $checkoutHelper,
        \Capgemini\DataLayer\Helper\Data $helper,
        \Magento\Framework\Escaper $escaper = null,
        ItemResolverInterface $itemResolver = null
    )
    {
        $this->escaper = ObjectManager::getInstance()->get(\Magento\Framework\Escaper::class);
        $this->itemResolver = ObjectManager::getInstance()->get(ItemResolverInterface::class);
        $this->helper = $helper;
        parent::__construct($imageHelper, $msrpHelper, $urlBuilder, $configurationPool, $checkoutHelper, $escaper, $itemResolver);
    }

    /**
     * {@inheritdoc}
     */
    protected function doGetItemData()
    {
        $imageHelper = $this->imageHelper->init($this->getProductForThumbnail(), 'mini_cart_product_thumbnail');
        $productName = $this->escaper->escapeHtml($this->item->getProduct()->getName());
        $productStyle = $this->escaper->escapeHtml($this->getProductStyle($this->item->getProduct()));

        return [
            'options' => $this->getOptionList(),
            'qty' => $this->item->getQty() * 1,
            'item_id' => $this->item->getId(),
            'configure_url' => $this->getConfigureUrl(),
            'style' => $productStyle,
            'is_visible_in_site_visibility' => $this->item->getProduct()->isVisibleInSiteVisibility(),
            'product_id' => $this->item->getProduct()->getId(),
            'product_name' => $productName,
            'product_sku' => $this->item->getProduct()->getSku(),
            'product_url' => $this->getProductUrl(),
            'product_has_url' => $this->hasProductUrl(),
            'product_price' => $this->checkoutHelper->formatPrice($this->item->getCalculationPrice()),
            'product_price_value' => $this->item->getCalculationPrice(),
            'full_price' => number_format($this->item->getProduct()->getPrice(), 2),
            'category' => $this->helper->getProductCategories($this->item->getProduct()),
            'product_image' => [
                'src' => $imageHelper->getUrl(),
                'alt' => $imageHelper->getLabel(),
                'width' => $imageHelper->getWidth(),
                'height' => $imageHelper->getHeight(),
            ],
            'canApplyMsrp' => $this->msrpHelper->isShowBeforeOrderConfirm($this->item->getProduct())
                && $this->msrpHelper->isMinimalPriceLessMsrp($this->item->getProduct()),
            'brand' => $this->helper->getProductBrand($this->item->getProduct())
        ];
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return array|string|null
     */
    protected function getProductStyle(\Magento\Catalog\Model\Product $product) {
        return $this->helper->getProductStyle($product);
    }
}
