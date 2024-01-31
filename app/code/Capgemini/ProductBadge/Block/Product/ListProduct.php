<?php

namespace Capgemini\ProductBadge\Block\Product;

use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Template;

class ListProduct extends Template
{
    /**
     * @var Product
     */
    protected $currentProduct;

    public function __construct(
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function setProduct(Product $product)
    {
        $this->currentProduct = $product;
        return $this;
    }
}
