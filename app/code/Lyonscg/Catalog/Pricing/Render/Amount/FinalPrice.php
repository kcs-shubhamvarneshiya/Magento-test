<?php

namespace Lyonscg\Catalog\Pricing\Render\Amount;

use Capgemini\BloomreachThematic\Model\TechnicalProduct;
use Lyonscg\Catalog\Plugin\App\Action\ContextPlugin;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolverInterface;
use Magento\Catalog\Pricing\Price\MinimalPriceCalculatorInterface;
use Magento\Catalog\Pricing\Render\FinalPriceBox;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Customer\Model\Session;
use Magento\Eav\Model\ResourceModel\Entity\Attribute;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\State;
use Magento\Framework\Phrase;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\Pricing\Render\RendererPool;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\View\Element\Template\Context;

class FinalPrice extends FinalPriceBox
{
    const BASECODE_ATTRIBUTE = 'basecodedisplayimage';

    /**
     * @var SaleableInterface
     */
    protected $basecodeChildItem = null;

    /**
     * @var Attribute
     */
    protected $eavAttribute;

    /**
     * @var HttpContext
     */
    private $httpContext;

    /**
     * @param Context $context
     * @param SaleableInterface $saleableItem
     * @param PriceInterface $price
     * @param RendererPool $rendererPool
     * @param Attribute $eavAttribute
     * @param array $data
     * @param SalableResolverInterface|null $salableResolver
     * @param MinimalPriceCalculatorInterface|null $minimalPriceCalculator
     * @param HttpContext|null $httpContext
     */
    public function __construct(
        Context $context,
        SaleableInterface $saleableItem,
        PriceInterface $price,
        RendererPool $rendererPool,
        Attribute $eavAttribute,
        array $data = [],
        SalableResolverInterface $salableResolver = null,
        MinimalPriceCalculatorInterface $minimalPriceCalculator = null,
        HttpContext $httpContext = null
    ) {
        parent::__construct($context, $saleableItem, $price, $rendererPool, $data, $salableResolver, $minimalPriceCalculator);

        $this->eavAttribute = $eavAttribute;
        $this->basecodeChildItem = $this->getChildItem($saleableItem);
        $this->httpContext = $httpContext ?: ObjectManager::getInstance()->get(HttpContext::class);
    }

    /**
     * @return SaleableInterface
     */
    public function getChildItem(SaleableInterface $saleableItem)
    {
        /** @var $saleableItem Product */
        if ($saleableItem->getTypeId() !== Configurable::TYPE_CODE) {
            return $saleableItem;
        }

        /** @var Configurable $configType */
        $configType = $saleableItem->getTypeInstance();

        if ($saleableItem->getData(TechnicalProduct::IS_THEMATIC_PRODUCT_DATA_KEY)) {
            if (isset($configType->getUsedProducts($saleableItem)[0])) {
                return $configType->getUsedProducts($saleableItem)[0];
            }
        }

        $basecodeId = $this->eavAttribute->getIdByCode(Product::ENTITY, self::BASECODE_ATTRIBUTE);
        $children = $configType->getUsedProducts($saleableItem, [$basecodeId]);
        foreach ($children as $childProduct) {
            /** @var Product $childProduct */
            if ($childProduct->getAttributeText(self::BASECODE_ATTRIBUTE)) {
                return $childProduct;
            }
        }
        return $saleableItem;
    }

    /**
     * @param $priceCode
     * @return PriceInterface
     */
    public function getBasecodePriceType($priceCode)
    {
        return $this->basecodeChildItem->getPriceInfo()->getPrice($priceCode);
    }

    /**
     * Add is_trade to cache key info to make it unique based on customer being a trade customer
     *
     * @inheritdoc
     */
    public function getCacheKeyInfo()
    {
        $cacheKeyInfo = parent::getCacheKeyInfo();

        $cacheKeyInfo['is_trade'] = $this->httpContext->getValue(ContextPlugin::IS_TRADE_CUSTOMER_CONTEXT);

        return $cacheKeyInfo;
    }

    /**
     * Get label to display for special price
     *
     * @param $default
     * @return Phrase
     */
    public function getSpecialPriceLabel($default)
    {
        if ($this->httpContext->getValue(ContextPlugin::IS_TRADE_CUSTOMER_CONTEXT)) {
            return __('Trade');
        }

        return $default;
    }

    /**
     * @param $priceCode
     * @return PriceInterface
     */
    public function getPriceType($priceCode)
    {
        if ($priceCode === 'final_price') {
            if ($finalPriceModel = $this->saleableItem->getData('final_price_model')) {

                return $finalPriceModel;
            }
        }

        return parent::getPriceType($priceCode);
    }
}
