<?php

namespace Lyonscg\RequisitionList\Block\Requisition\Item;

use Magento\Catalog\Api\ProductrepositoryInterface;
use Magento\RequisitionList\Api\Data\RequisitionListItemInterface;

class Sku extends \Magento\Framework\View\Element\AbstractBlock
{
    /**
     * @var RequisitionListItemInterface
     */
    protected $item;

    /**
     * @var ProductrepositoryInterface
     */
    protected $productRepository;

    /**
     * Sku constructor.
     * @param \Magento\Framework\View\Element\Context $context
     * @param ProductrepositoryInterface $productRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        ProductrepositoryInterface $productRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->productRepository = $productRepository;
    }

    /**
     * @param RequisitionListItemInterface $item
     * @return $this
     */
    public function setItem(RequisitionListItemInterface $item)
    {
        $this->item = $item;
        return $this;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        if (!$this->item) {
            return '';
        }

        // get child sku
        $options = $this->item->getOptions();
        if (!isset($options['simple_product'])) {
            return $this->item->getSku();
        }
        $simpleProductId = $options['simple_product'];
        try {
            $product = $this->productRepository->getById($simpleProductId);
            return $product->getSku();
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return $this->item->getSku();
        }
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        return $this->escapeHtml($this->getSku());
    }
}
