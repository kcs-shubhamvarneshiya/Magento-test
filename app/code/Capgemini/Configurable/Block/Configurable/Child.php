<?php

namespace Capgemini\Configurable\Block\Configurable;

use Magento\Catalog\Helper\Image as ImageHelper;

use Magento\Catalog\Model\Product\Image\ParamsBuilder;
use Magento\Catalog\Model\View\Asset\ImageFactory as AssetImageFactory;
use Magento\Catalog\Model\View\Asset\PlaceholderFactory;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Catalog\Pricing\Price\RegularPrice;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Pricing\Render;
use Magento\Framework\View\ConfigInterface;

class Child extends \Magento\Framework\View\Element\Template
{
    const LIST_IMAGE_ID = 'product_variation_item';
    const SWATCH_IMAGE_ID = 'category_swatch';
    const AVAILABILITY_ATTRIBUTE_CODE = 'availability_message';
    protected $imageParamsBuilder;
    protected $viewAssetImageFactory;
    protected $viewAssetPlaceholderFactory;
    protected $presentationConfig;
    protected $priceCurrency;
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $childProduct;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        ParamsBuilder $imageParamsBuilder,
        AssetImageFactory $viewAssetImageFactory,
        PlaceholderFactory $viewAssetPlaceholderFactory,
        ConfigInterface $presentationConfig,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->imageParamsBuilder = $imageParamsBuilder;
        $this->viewAssetImageFactory = $viewAssetImageFactory;
        $this->viewAssetPlaceholderFactory = $viewAssetPlaceholderFactory;
        $this->presentationConfig = $presentationConfig;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @param \Magento\Catalog\Model\Product $childProduct
     * @return $this
     */
    public function setChildProduct(\Magento\Catalog\Model\Product $childProduct)
    {
        $this->childProduct = $childProduct;
        return $this;
    }

    /**
     * @return \Magento\Catalog\Model\Product
     */
    public function getChildProduct()
    {
        return $this->childProduct;
    }

    public function getProductImageData(\Magento\Catalog\Model\Product $product, $mediaId = self::LIST_IMAGE_ID)
    {
        $viewImageConfig = $this->presentationConfig->getViewConfig()->getMediaAttributes(
            'Magento_Catalog',
            ImageHelper::MEDIA_TYPE_CONFIG_NODE,
            $mediaId
        );

        $imageMiscParams = $this->imageParamsBuilder->build($viewImageConfig);
        $originalFilePath = $product->getData($imageMiscParams['image_type']);

        if ($imageData = $product->getData('br_product_image')) {
            $imageAsset = $this->viewAssetImageFactory->create(
                [
                    'miscParams' => $imageMiscParams,
                    'filePath' => '',
                ]

            );

            return [
                'src' => $imageData[0],
                'alt' => $product->getName()
            ];
        } elseif ($originalFilePath === null || $originalFilePath === 'no_selection') {
            $imageAsset = $this->viewAssetPlaceholderFactory->create(
                [
                    'type' => $imageMiscParams['image_type']
                ]
            );
        } else {
            $imageAsset = $this->viewAssetImageFactory->create(
                [
                    'miscParams' => $imageMiscParams,
                    'filePath' => $originalFilePath,
                ]
            );
        }

        return [
            'src' => $imageAsset->getUrl(),
            'alt' => $product->getName()
        ];
    }

    public function getCLPPrice(\Magento\Catalog\Model\Product $product)
    {
        $regularPrice = $product->getPriceInfo()->getPrice(RegularPrice::PRICE_CODE)->getAmount()->getValue();
        $finalPrice = $product->getPriceInfo()->getPrice(FinalPrice::PRICE_CODE)->getAmount()->getValue();
        $prices = [];
        if ($finalPrice < $regularPrice) {
            $prices['data-clp-price'] = $this->priceCurrency->format($regularPrice, false);
            $prices['data-clp-trade-price'] = $this->priceCurrency->format($finalPrice, false);
        } else {
            $prices['data-clp-price'] = $this->priceCurrency->format($finalPrice, false);
        }
        return $prices;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return array|string|null
     */
    public function getProductStyle(\Magento\Catalog\Model\Product $product) {
        $style = $product->getStyle();
        if (!empty($style) && $style) {
            $productStyle = $product->getAttributeText('style');
            return (is_array($productStyle)) ?
                implode(',', $productStyle) : $productStyle;
        }
        return '';
    }

    public function getChildUrl(\Magento\Catalog\Model\Product $product)
    {
        $parentProduct = $this->getParentBlock()->getProduct();
        $parentUrl = $parentProduct->getProductUrl();
        $separator = strpos($parentUrl, '?') === false ? '?' : '&';
        return $parentUrl . $separator . 'selected_product=' . $product->getId();
    }

    /**
     * @param $infoType
     * @return null
     */
    public function getAvailabilityFrontendInfo($infoType)
    {
        /** @var Attribute $attribute */
        $attribute = $this->childProduct
            ->getResource()
            ->getAttribute(self::AVAILABILITY_ATTRIBUTE_CODE);

        switch ($infoType) {
            case 'label':

                return $attribute->getStoreLabel();
            case 'value':

                return $attribute->getFrontend()->getValue($this->childProduct);
            default:

                return null;
        }
    }
    public function getDetailDescription(\Magento\Catalog\Model\Product $product): string
    {
        // try to get Bloomreach value first, if nothing, try to get
        $detailDescription = '';
        if (is_array($product->getData('detail_description')) && !empty($product->getData('detail_description'))) {
        // get first badge
        $badgeArray = $product->getData('detail_description');
        $detailDescription = reset($badgeArray);
        }

        if ($detailDescription === '') {
        if ($product->getAttributeText('detail_description')) {
        $detailDescription = $product->getAttributeText('detail_description');
        }
        }
        return $detailDescription;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    public function getBadgeData(\Magento\Catalog\Model\Product $product): array
    {
        // try to get Bloomreach value first, if nothing, try to get
        $badgeLabel = '';
        if (is_array($product->getData('badge')) && !empty($product->getData('badge'))) {
            // get first badge
            $badgeArray = $product->getData('badge');
            $badgeLabel = reset($badgeArray);
        }

        if ($badgeLabel === '') {
            if ($product->getAttributeText('badge')) {
                $badgeLabel = $product->getAttributeText('badge');
            }
        }

        $badgeClass = 'badge-' . implode('-', explode(' ', strtolower($badgeLabel)));

        return [
            $badgeLabel,
            $badgeClass
        ];
    }

    /**
     * Specifies that price rendering should be done for the list of products.
     * (rendering happens in the scope of product list, but not single product)
     *
     * @return Render
     */
    protected function getPriceRender()
    {
        return $this->getLayout()->getBlock('product.price.render.default')
            ->setData('is_product_list', true);
    }
}
