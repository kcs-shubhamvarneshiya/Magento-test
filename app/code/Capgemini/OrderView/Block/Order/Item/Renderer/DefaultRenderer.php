<?php

namespace Capgemini\OrderView\Block\Order\Item\Renderer;

use Capgemini\OrderView\Helper\Product as ProductHelper;

class DefaultRenderer extends \Magento\Sales\Block\Order\Item\Renderer\DefaultRenderer
{
    /**
     * @var ProductHelper
     */
    protected $productHelper;

    /**
     * DefaultRenderer constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory
     * @param ProductHelper $productHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory,
        ProductHelper $productHelper,
        array $data = []
    ) {
        parent::__construct($context, $string, $productOptionFactory, $data);
        $this->productHelper = $productHelper;
    }

    public function getProductImage()
    {
        $product = $this->getItem()->getProduct();
        return $this->productHelper->getProductImage($product);
    }
}
