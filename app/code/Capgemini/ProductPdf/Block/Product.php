<?php
/**
 * Capgemini_ProductPdf
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\ProductPdf\Block;

use Capgemini\ProductPdf\Helper\Data;
use Capgemini\Dimensions\Model\Converter;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Catalog\Block\Product\ImageFactory;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\ProductRepository;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory as AttributeOptionCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Product extends Template
{
    public const LABEL = 'label';
    public const VALUE = 'value';
    public const CM_VALUE = 'cm_value';

    public const VALUE_PRICING_DISABLE = 1;
    public const VALUE_PRICING_TRADE  = 2;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var bool
     */
    protected $pricingEnable = true;

    /**
     * @var int
     */
    protected $priceType;

    /**
     * @var int
     */
    protected $productId;

    /**
     * @var ProductInterface
     */
    protected $product;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var ImageFactory
     */
    protected $imageFactory;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var AttributeOptionCollectionFactory
     */
    protected $attributeOptionCollectionFactory;

    /**
     * @var ProductAttributeRepositoryInterface $attributeRepository
     */
    protected $attributeRepository;

    /**
     * @var Configurable
     */
    protected $configurableType;

    /**
     * @var Converter
     */
    protected $converter;

    /**
     * @param Context $context
     * @param ProductRepository $productRepository
     * @param ImageFactory $imageFactory
     * @param Data $helper
     * @param AttributeOptionCollectionFactory $attributeOptionCollectionFactory
     * @param ProductAttributeRepositoryInterface $attributeRepository
     * @param Configurable $configurableType
     * @param Converter $converter
     * @param array $data
     */
    public function __construct(
        Context           $context,
        ProductRepository $productRepository,
        ImageFactory      $imageFactory,
        Data $helper,
        AttributeOptionCollectionFactory $attributeOptionCollectionFactory,
        ProductAttributeRepositoryInterface $attributeRepository,
        Configurable $configurableType,
        Converter $converter,
        array             $data = []
    ) {
        parent::__construct($context, $data);
        $this->productRepository = $productRepository;
        $this->imageFactory = $imageFactory;
        $this->helper = $helper;
        $this->attributeOptionCollectionFactory = $attributeOptionCollectionFactory;
        $this->attributeRepository = $attributeRepository;
        $this->configurableType = $configurableType;
        $this->converter = $converter;
    }

    /**
     * @return ProductInterface|mixed|null
     * @throws NoSuchEntityException
     */
    public function getProduct()
    {
        if ($this->product === null) {
            $this->product = $this->productRepository->getById($this->getProductId());
        }
        return $this->product;
    }

    /**
     * @return null|array
     */
    public function getOptions(): ?array
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    /**
     * @return int
     */
    public function getPriceType(): int
    {
        return $this->priceType;
    }

    /**
     * @param int $priceType
     */
    public function setPriceType(int $priceType): void
    {
        $this->priceType = $priceType;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return bool
     */
    public function isPricingEnable(): bool
    {
        return $this->pricingEnable;
    }

    /**
     * @param bool $pricingEnable
     */
    public function setPricingEnable(bool $pricingEnable): void
    {
        $this->pricingEnable = $pricingEnable;
    }

    /**
     * @param $type
     * @return string
     * @throws NoSuchEntityException
     */
    public function getImageUrl($type)
    {
        return $this->imageFactory->create($this->getProduct(), $type)->getImageUrl();
    }

    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getProductImages()
    {
        return $this->getProduct()->getMediaGalleryImages();
    }

    /**
     * @return array
     * @throws NoSuchEntityException
     */
    public function getSpecifications()
    {
        return $this->helper->getSpecificationsAttributes($this->getProduct());
    }

    /**
     * @return float|null
     * @throws NoSuchEntityException
     */
    public function getProductPrice()
    {
        if ($this->getPriceType() == self::VALUE_PRICING_TRADE) {
            return $this->getProduct()->getFinalPrice();
        }
        return $this->getProduct()->getPrice();
    }


    /**
     * Get Dimensions
     *
     * @return array
     */
    public function getDimensionsSpecifications()
    {
        $block = $this->getLayout()->createBlock('\Capgemini\ProductDimensions\Block\Dimensions');
        $block->setProduct($this->getProduct())
            ->setAttributeCodePrefix("dimension_");
        return $block->getDimensions();
    }

    /**
     * @return array
     */
    public function getSpecificationsBottom()
    {
        $block = $this->getLayout()->createBlock('\Capgemini\ProductDimensions\Block\SpecificationsBottom');
        $block->setProduct($this->getProduct())
            ->setAttributeCodePrefix("specification_bottom_");
        return $block->getDimensions();
    }

    /**
     * @return array
     */
    public function getRatingsData()
    {
        $block = $this->getLayout()->createBlock('\Capgemini\ProductRatings\Block\Product\Rating');
        $block->setProduct($this->getProduct());
        $data = $block->getRatingsData();
        foreach ($data as $key => $rating) {
            if ($rating['img']) {
                $rating['img'] = $this->getUrl('', ['_direct' => $rating['img']]);
            }
            $data[$key] = $rating;
        }
        return $data;
    }

    /**
     * @return array
     */
    public function getSelectedOptions()
    {
        try {
            $options = [];
            $optionsValues = $this->getOptions();
            if ($optionsValues) {
                $attributeCollection = $this->attributeOptionCollectionFactory->create();
                $optionsData = $attributeCollection->setIdFilter($optionsValues)->setStoreFilter()->load();
                foreach ($optionsData as $option) {
                    $attribute = $this->attributeRepository->get($option['attribute_id']);
                    $options[] = [
                        self::LABEL => $attribute->getFrontend()->getLocalizedLabel(),
                        self::VALUE => $option[self::VALUE]
                    ];
                }
            } else {
                if ($this->getProduct()->getTypeId() == Type::DEFAULT_TYPE) {
                    $parentsProducts = $this->configurableType->getParentIdsByChild($this->getProduct()->getId());
                    if (isset($parentsProducts[0])) {
                        $parentProduct = $this->productRepository->getById($parentsProducts[0]);
                        $configurableAttributes =
                            $parentProduct->getTypeInstance()->getConfigurableAttributes($parentProduct);
                        foreach ($configurableAttributes as $configurableAttribute) {
                            $attribute = $this->attributeRepository->get($configurableAttribute['attribute_id']);
                            $options[] = [
                                self::LABEL => $attribute->getFrontend()->getLocalizedLabel(),
                                self::VALUE => $attribute->getFrontend()->getValue($this->getProduct())
                            ];
                        }
                    }
                }
            }
            return $options;
        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * @return string
     */
    public function getStylesLink()
    {
        return '<link rel="stylesheet" type="text/css" href="'
            . $this->getViewFileUrl('Capgemini_ProductPdf/css/product-pdf.css'). '"/>';
    }
}
