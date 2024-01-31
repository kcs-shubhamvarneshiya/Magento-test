<?php

namespace Capgemini\ProductBadge\Block\Product;

use Magento\Catalog\Model\Product;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Framework\Logger\Monolog;

class View extends Template
{
    const BADGE_ATTRIBUTE_CODE = 'badge';

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var ProductAttributeRepositoryInterface
     */
    protected $productAttributeRepository;

    /**
     * @var Monolog
     */
    private $logger;

    public function __construct(
        Template\Context $context,
        \Magento\Catalog\Block\Product\Context $productContext,
        ProductRepositoryInterface $productRepository,
        ProductAttributeRepositoryInterface $productAttributeRepository,
        Monolog $logger,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->productRepository = $productRepository;
        $this->coreRegistry = $productContext->getRegistry();
        $this->productAttributeRepository = $productAttributeRepository;
        $this->logger = $logger;
    }

    public function getBadgeAttribute()
    {
        try {
            $attribute = $this->productAttributeRepository->get(self::BADGE_ATTRIBUTE_CODE);
            if ($attribute instanceof AbstractAttribute) {
                return $attribute;
            }
        } catch (NoSuchEntityException $e) {
            // Handle exception
            return false;
        }
    }

    /**
     * Retrieve current product model
     *
     * @return Product
     */
    public function getProduct()
    {
        if (!$this->coreRegistry->registry('product') && $this->getProductId()) {
            $product = $this->productRepository->getById($this->getProductId());
            $this->coreRegistry->register('product', $product);
        }
        return $this->coreRegistry->registry('product');
    }

    /**
     * Get product id
     *
     * @return int|null
     */
    public function getProductId()
    {
        $product = $this->coreRegistry->registry('product');
        return $product ? $product->getId() : null;
    }

    /**
     * @return array
     */
    public function getBadges()
    {
        $badges = [];

        try {
            $product = $this->getProduct();
            $badgeAttribute = $this->getBadgeAttribute();

            if ($product->getTypeId() === 'configurable') {
                foreach ($product->getTypeInstance()->getUsedProducts($product, [$badgeAttribute->getId()]) as $simpleProduct) {
                    if ($simpleProduct->getBadge()) {
                        $badges[$simpleProduct->getEntityId()] = [
                            'value' => $simpleProduct->getBadge(),
                            'label' => $badgeAttribute->getFrontend()->getValue($simpleProduct),
                            'html_class' => 'badge-' . implode('-', explode(' ', strtolower($badgeAttribute->getFrontend()->getValue($simpleProduct)))),
                            'isShown' => false
                        ];
                    }
                }
            } else {
                // Product is not configurable
                if ($product->getBadge()) {
                    $badges[$product->getEntityId()] = [
                        'value' => $product->getBadge(),
                        'label' => $badgeAttribute->getFrontend()->getValue($product),
                        'html_class' => implode('-', explode(' ', strtolower($badgeAttribute->getFrontend()->getValue($product)))),
                        'isShown' => true
                    ];
                }
            }
        } catch (\Exception $e) {
            // Handle exception
            $this->logger->critical($e);
        }

        return $badges;
    }
}
